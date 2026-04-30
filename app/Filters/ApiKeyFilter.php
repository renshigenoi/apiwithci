<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiKeyFilter implements \CodeIgniter\Filters\FilterInterface
{
// app/Filters/ApiKeyFilter.php
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');
        $model = new \App\Models\ApiKeyModel();
        $found = $model->where('key', $apiKey)
                    ->where('status', 'active')
                    ->first();

        if (!$found) {
            return service('response')->setJSON(['message' => 'Invalid API Key atau Store Inaktif'])->setStatusCode(401);
        }

        // Simpan store_id ke Service agar Controller tahu ini request milik Store mana
        // Kita bisa buat Service baru atau numpang di jwtPayload
        service('jwtPayload')->setStoreId($found['store_id']);
    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null) {

    }
}