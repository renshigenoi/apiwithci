<?php

namespace App\Libraries;

class JwtPayload
{
    protected array $data = [];

    public function set(array $payload)
    {
        $this->data = $payload;
    }

    public function get($key = null)
    {
        if ($key === null) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }
}
