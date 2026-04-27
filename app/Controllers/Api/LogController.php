<?php

namespace App\Controllers\Api;

use App\Models\ApiLogModel;
use App\Models\UserModel;
use App\Models\ApiKeyModel;
use CodeIgniter\Controller;
use Config\Services;
use App\Libraries\SlackLogs;
use App\Libraries\TelegramLogs;
use CodeIgniter\API\ResponseTrait;

class LogController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $role       = $this->request->getGet('role') ?? null;
        $email      = $this->request->getGet('email') ?? null;
        $start      = $this->request->getGet('start');
        $end        = $this->request->getGet('end');
        $logModel   = new ApiLogModel();
        $data       = $logModel->AllData($role, $email, $start, $end);
        return $this->response->setJSON($data);
    }

    public function dailyByStatus()
    {
        $role       = $this->request->getGet('role') ?? null;
        $email      = $this->request->getGet('email') ?? null;
        $logModel   = new ApiLogModel();
        $data       = $logModel->getLogsPerDayByStatus($role, $email);
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function dailyByEmail()
    {
        $logModel   = new ApiLogModel();
        $data       = $logModel->getLogsPerDayByEmail();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    private function sendAlert($message)
    {
        SlackLogs::send($message, 'error');
        TelegramLogs::send($message);
    }

    public function checkErrorSpike()
    {
        $logModel   = new ApiLogModel();
        $count      = $logModel->where('status_code', 500)
                        ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
                        ->countAllResults();
        if ($count > 10) {
            // trigger alert
            $this->sendAlert("Error spike detected: {$count} errors (500) in last 1 hour");
        }
    }

    public function getStats()
    {
        $payload            = \Config\Services::jwtPayload()->get();
        $role               = $payload['role'] ?? 'user';
        $email              = $payload['email'] ?? '';

        $userModel          = new UserModel();
        // Default Query API Keys
        $keyModel           = new ApiKeyModel();
        $keyQuery           = $keyModel;
        // Default Query API Logs
        $logModel           = new ApiLogModel();
        $logQuery           = $logModel;
        // Default Query API Logs Yesterday
        $logModelYest       = new ApiLogModel();
        $logQueryYest       = $logModelYest;
        // Jika user, filter hanya miliknya
        if ($role === 'user') {
            $logQuery       = $logModel->where('user_email', $email);
            $logQueryYest   = $logModelYest->where('user_email', $email);
            $keyQuery       = $keyModel->where('user_email', $email);
        }
        $totalRequests      = $logQuery->countAllResults(false); // false agar instance model tidak reset
        $totalKeys          = $keyQuery->countAllResults();
        $today              = date('Y-m-d');
        $yesterday          = date('Y-m-d', strtotime("-1 days"));
        $countToday         = $logQuery->where('DATE(created_at)', $today)->countAllResults();
        $countYesterday     = $logQueryYest->where('DATE(created_at)', $yesterday)->countAllResults();
        $diff               = $countYesterday > 0 ? (($countToday - $countYesterday) / $countYesterday) * 100 : 0;
        $data               = [
            'role'           => $role,
            'total_requests' => $totalRequests,
            'total_keys'     => $totalKeys,
            // Admin & Super Admin bisa lihat total user
            'active_users'   => ($role === 'superadmin' || $role === 'admin') ? $userModel->countAllResults() : 0,
            'today_requests' => $countToday,
            'yest_requests'  => $countYesterday,
            'diff'           => $diff,
        ];
        return $this->response->setJSON($data);
    }

    public function clear()
    {
        // 1. Proteksi: Hanya Superadmin yang boleh clear log
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'user') !== 'superadmin') {
            return $this->failForbidden('Hanya Superadmin yang dapat menghapus log.');
        }

        $db = \Config\Database::connect();
        try {
            // 2. Gunakan TRUNCATE untuk mengosongkan tabel dan mereset auto-increment
            // Jika ingin menghapus tapi ID tetap berlanjut, gunakan $db->table('api_logs')->emptyTable();
            $db->table('api_logs')->truncate();

            return $this->respond([
                'status'  => 'success',
                'message' => 'Seluruh log berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal mengosongkan log: ' . $e->getMessage());
        }
    }

    public function deleteRange()
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'user') !== 'superadmin') {
            return $this->failForbidden('Unauthorized');
        }

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        if (!$start || !$end) {
            return $this->fail('Tanggal start dan end diperlukan.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('api_logs');

        try {
            // Kita gunakan date() pada kolom created_at (atau nama kolom timestamp kamu)
            // Agar pencarian tanggal lebih akurat meskipun ada jamnya
            $builder->where("DATE(created_at) >=", $start);
            $builder->where("DATE(created_at) <=", $end);
            $builder->delete();

            $affectedRows = $db->affectedRows();

            return $this->respond([
                'status'  => 'success',
                'message' => "$affectedRows baris log berhasil dihapus."
            ]);
        } catch (\Exception $e) {
            return $this->failServerError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
