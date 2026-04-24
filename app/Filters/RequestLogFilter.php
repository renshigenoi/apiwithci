<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use App\Models\ApiLogModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class RequestLogFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null) {

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $logger = Services::logger();
        $logger->info('API Request', [
            'ip'            => $request->getIPAddress(),
            'method'        => $request->getMethod(),
            'uri'           => (string) $request->getUri(),
            'status'        => $response->getStatusCode(),
            'userAgent'     => $request->getUserAgent(),
        ]);

        $token              = $request->getHeaderLine('Authorization');
        $userId             = null;

        if ($token) {
            $jwt            = str_replace('Bearer ', '', $token);
            try {
                $decoded    = JWT::decode($jwt, new Key(getenv('jwt.secret'), 'HS256'));
                $userId     = $decoded->data->id;
                $userEmail  = $decoded->data->email;
            } catch (\Exception $e) {
                $userId     = null;
                $userEmail  = null;
            }
        }
        $logModel = new ApiLogModel();
        $logModel->insert([
            'ip_address'    => $request->getIPAddress(),
            'method'        => $request->getMethod(),
            'uri'           => (string) $request->getUri(),
            'status_code'   => $response->getStatusCode(),
            'user_agent'    => $request->getUserAgent(),
            'user_id'       => $userId, // tambahkan user_id
            'user_email'    => $userEmail, // tambahkan user_id
        ]);
    }
}
