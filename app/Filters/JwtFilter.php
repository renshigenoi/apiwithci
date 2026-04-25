<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtFilter implements \CodeIgniter\Filters\FilterInterface
{
    public function before(\CodeIgniter\HTTP\RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')->setJSON(['message' => 'Unauthorized'])->setStatusCode(401);
        }

        $token = substr($authHeader, 7);
        try {
            $decoded = JWT::decode($token, new Key(getenv('jwt.secret'), 'HS256'));
            // simpan payload ke service
            Services::jwtPayload()->set((array) $decoded->data);
        } catch (\Exception $e) {
            return service('response')->setJSON(['message' => 'Invalid or expired token'])->setStatusCode(401);
        }
    }

    public function after(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, $arguments = null) {

    }
}