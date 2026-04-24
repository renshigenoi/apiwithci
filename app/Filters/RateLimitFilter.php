<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RateLimitFilter implements FilterInterface
{
    protected $limit    = 60; // max request per menit
    protected $window   = 60; // dalam detik

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip         = $request->getIPAddress();
        $cache      = cache();
        $key        = "rate_limit_" . $ip;
        $data       = $cache->get($key);

        if (!$data) {
            $data   = ['count' => 1, 'start' => time()];
            $cache->save($key, $data, $this->window);
        } else {
            if (time() - $data['start'] < $this->window) {
                if ($data['count'] >= $this->limit) {
                    return service('response')
                        ->setJSON(['message' => 'Too Many Requests'])
                        ->setStatusCode(429);
                }
                $data['count']++;
                $cache->save($key, $data, $this->window);
            } else {
                $data = ['count' => 1, 'start' => time()];
                $cache->save($key, $data, $this->window);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        
    }
}
