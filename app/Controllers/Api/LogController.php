<?php

namespace App\Controllers\Api;

use App\Models\ApiLogModel;
use App\Models\UserModel;
use App\Models\ApiKeyModel;
use CodeIgniter\Controller;
use Config\Services;
use App\Libraries\SlackLogs;
use App\Libraries\TelegramLogs;

class LogController extends Controller
{
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
        $role               = $payload['role'] ?? 'staff';
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
        // Jika staff, filter hanya miliknya
        if ($role === 'staff') {
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
            // Admin saja yang bisa lihat total user
            'active_users'   => ($role === 'admin') ? $userModel->countAllResults() : 0,
            'today_requests' => $countToday,
            'yest_requests'  => $countYesterday,
            'diff'           => $diff,
        ];
        return $this->response->setJSON($data);
    }
}
