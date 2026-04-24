<?php

namespace App\Models;

use CodeIgniter\Model;

class ApiLogModel extends Model
{
    protected $useTimestamps    = true;
    protected $table            = 'api_logs';
    protected $allowedFields    = ['ip_address', 'method', 'uri', 'payload', 'status_code', 'user_agent', 'user_id', 'user_email'];

    public function AllData($role = null, $email = null, $startDate = null, $endDate = null)
    {
        $builder    = $this->orderBy('created_at', 'DESC');
        if ($role === "staff") {
            $builder->where('user_email', $email);
        }
        if ($startDate && $endDate) {
            $builder->where('DATE(created_at) >=', $startDate)
                    ->where('DATE(created_at) <=', $endDate);
        }
        return $builder->findAll();
    }

    // Per hari berdasarkan status_code
    public function getLogsPerDayByStatus($role = null, $email = null)
    {
        $builder    = $this->select('DATE(created_at) as log_date, status_code, COUNT(*) as total')
                            ->where('DATE(created_at)', date('Y-m-d'))
                            ->groupBy('DATE(created_at), status_code')
                            ->orderBy('log_date', 'ASC');
        if($role === "staff") {
            $builder->where('user_email', $email);
        }
        return $builder->findAll();
    }

    // Per hari berdasarkan user_email
    public function getLogsPerDayByEmail($role = null, $email = null)
    {
        $builder    = $this->select('DATE(created_at) as log_date, user_email, COUNT(*) as total')
                            ->where('DATE(created_at)', date('Y-m-d'))
                            ->groupBy('DATE(created_at), user_email')
                            ->orderBy('log_date', 'ASC');
        // if($role === "staff") {
        //     $builder->where('user_email', $email);
        // }
        return $builder->findAll();
    }
}
