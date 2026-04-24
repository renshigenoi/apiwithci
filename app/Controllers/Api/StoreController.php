<?php

namespace App\Controllers\Api;

use App\Models\StoreModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait; // 1. Tambahkan ini
use Config\Services;

class StoreController extends Controller
{
    use ResponseTrait; // 2. Gunakan Trait di sini

    public function index()
    {
        $userModel = new UserModel();
        // Menggunakan respond() lebih ringkas daripada setJSON
        return $this->respond($userModel->findAll());
    }

    public function update($id)
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'admin') {
            return $this->failForbidden('Unauthorized'); // 3. Gunakan fail standar
        }

        $json = $this->request->getJSON(true);
        $userModel = new UserModel();
        
        $dataUpdate = [
            'name'  => $json['name'],
            'email' => $json['email'],
            'role'  => $json['role']
        ];

        if (!empty($json['password'])) {
            $dataUpdate['password'] = password_hash($json['password'], PASSWORD_DEFAULT);
        }

        if($userModel->update($id, $dataUpdate)) {
            return $this->respond(['message' => 'User berhasil diupdate']);
        }
        
        return $this->fail('Gagal mengupdate user');
    }

    public function create()
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'admin') {
            return $this->failForbidden('Unauthorized');
        }
        
        $json = $this->request->getJSON(true);
        $userModel = new UserModel();
        
        $result = $userModel->insert([
            'name'     => $json['name'],
            'email'    => $json['email'],
            'password' => password_hash($json['password'], PASSWORD_DEFAULT),
            'role'     => $json['role']
        ]);

        if ($result) {
            return $this->respondCreated(['message' => 'User berhasil ditambahkan']);
        }
        
        return $this->fail('Gagal menambahkan user');
    }

    public function delete($id)
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'admin') {
            return $this->failForbidden('Unauthorized');
        }

        $userModel = new UserModel();
        if ($userModel->delete($id)) {
            return $this->respondDeleted(['message' => 'User berhasil dihapus']);
        }

        return $this->failNotFound('User tidak ditemukan');
    }
}