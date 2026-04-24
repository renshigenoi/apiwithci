<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ApiLogModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\Email\Email;
use Dompdf\Dompdf;

class ReportController extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ApiLogModel();
    }

    public function exportCsv()
    {
        $start  = $this->request->getGet('start');
        $end    = $this->request->getGet('end');
        $data   = $this->logModel->getLogsPerDayByStatus($start, $end);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="logs.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Tanggal', 'Status Code', 'Total']);
        foreach ($data as $row) {
            fputcsv($output, [$row['log_date'], $row['status_code'], $row['total']]);
        }
        fclose($output);
        exit;
    }

    // Export ke Excel berdasarkan date range
    public function exportExcel()
    {
        $start          = $this->request->getGet('start');
        $end            = $this->request->getGet('end');
        // ambil data logs sesuai filter tanggal
        $builder        = $this->logModel->orderBy('created_at', 'DESC');
        if ($start && $end) {
            $builder->where('DATE(created_at) >=', $start)
                    ->where('DATE(created_at) <=', $end);
        }
        $logs           = $builder->findAll();

        // buat spreadsheet
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();
        // header kolom
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'User Email');
        $sheet->setCellValue('C1', 'Status Code');
        $sheet->setCellValue('D1', 'Endpoint');
        $sheet->setCellValue('E1', 'Method');
        // isi data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue('A' . $row, $log['created_at']);
            $sheet->setCellValue('B' . $row, $log['user_email']);
            $sheet->setCellValue('C' . $row, $log['status_code']);
            $sheet->setCellValue('D' . $row, $log['uri']);
            $sheet->setCellValue('E' . $row, $log['method']);
            $row++;
        }
        // output file Excel
        $writer     = new Xlsx($spreadsheet);
        $filename   = 'api_logs_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');

        $builder    = $this->logModel->orderBy('created_at', 'DESC');
        if ($start && $end) {
            $builder->where('DATE(created_at) >=', $start)
                    ->where('DATE(created_at) <=', $end);
        }
        $logs       = $builder->findAll();

        // CSS styling laporan
        $html = '
        <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        p {
            text-align: center;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        thead {
            display: table-header-group; /* header muncul di setiap halaman */
        }
        tfoot {
            display: table-footer-group; /* footer muncul di setiap halaman */
        }
        </style>

        <h2>API Logs Report</h2>
        <p>Periode: ' . format_date($start,'d M Y') . ' s/d ' . format_date($end,'d M Y') . '</p>
        <table>
        <thead>
            <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>User Email</th>
            <th>Status Code</th>
            <th>Endpoint</th>
            <th>Method</th>
            </tr>
        </thead>
        <tbody>';
        $no         = 1;
        foreach ($logs as $log) {
            $html   .= '<tr>
                        <td align="center">' . $no . '.</td>
                        <td>' . format_datetime($log['created_at'],'d M Y H:i:s') . '</td>
                        <td>' . $log['user_email'] . '</td>
                        <td align="center">' . $log['status_code'] . '</td>
                        <td>' . $log['uri'] . '</td>
                        <td align="center">' . $log['method'] . '</td>
                      </tr>';
            $no++;
        }

        $html       .= '</tbody></table>';

        $dompdf     = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // A4 portrait
        $dompdf->render();

        $filename   = 'api_logs_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }

    // Kirim laporan via email (cron job)
    public function sendDailyReport()
    {
        $yesterday      = date('Y-m-d', strtotime('-1 day'));
        $data           = $this->logModel->getLogsPerDayByStatus($yesterday, $yesterday);
        // Buat file Excel sementara
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Status Code');
        $sheet->setCellValue('C1', 'Total');
        $row = 2;
        foreach ($data as $log) {
            $sheet->setCellValue('A' . $row, $log['log_date']);
            $sheet->setCellValue('B' . $row, $log['status_code']);
            $sheet->setCellValue('C' . $row, $log['total']);
            $row++;
        }
        $writer         = new Xlsx($spreadsheet);
        $filepath       = WRITEPATH . 'reports/daily_report_' . $yesterday . '.xlsx';
        $writer->save($filepath);

        // Kirim email
        $email          = \Config\Services::email();
        $email->setTo('admin@example.com');
        $email->setSubject('Daily API Logs Report - ' . $yesterday);
        $email->setMessage('Berikut laporan harian API logs untuk tanggal ' . $yesterday);
        $email->attach($filepath);
        if ($email->send()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Report sent']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => $email->printDebugger()]);
        }
    }
}
