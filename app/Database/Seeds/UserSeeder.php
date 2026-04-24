<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'     => 'Admin User',
                'email'    => 'admin@example.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role'     => 'admin'
            ],
            [
                'name'     => 'Staff User',
                'email'    => 'staff@example.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role'     => 'staff'
            ]
        ];

        // insert batch
        $this->db->table('users')->insertBatch($data);
    }
}
