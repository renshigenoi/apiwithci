<?php

namespace App\Libraries;

class JwtPayload
{
    protected array $data = [];

    public function set(array $payload)
    {
        $this->data = $payload;
    }

    public function get(): array
    {
        return $this->data;
    }
}
