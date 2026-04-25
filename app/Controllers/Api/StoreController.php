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
        $storeModel = new StoreModel();
        // Menggunakan respond() lebih ringkas daripada setJSON
        return $this->respond($storeModel->findAll());
    }

    public function update($id)
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'admin') {
            return $this->failForbidden('Unauthorized'); // 3. Gunakan fail standar
        }

        $json = $this->request->getJSON(true);
        $storeModel = new StoreModel();

        $dataUpdate =    [
            'name'              => $json['name'],
            'email'             => $json['email'],
            'phone'             => $json['phone'],
            'address'           => $json['address'],
            'contact_person'    => $json['contact_person'],
            'contact_phone'     => $json['contact_phone']
        ];

        if($storeModel->update($id, $dataUpdate)) {
            return $this->respond(['message' => 'User berhasil diupdate']);
        }

        return $this->fail('Gagal mengupdate user');
    }

    public function create()
    {
        $payload    = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'admin') {
            return $this->failForbidden('Unauthorized');
        }
        $json       = $this->request->getJSON(true);
        $storeModel = new StoreModel();
        $code       = generateCode('ST', $json['name']);
        $result     = $storeModel->insert([
            'name'              => $json['name'],
            'email'             => $json['email'],
            'phone'             => $json['phone'],
            'address'           => $json['address'],
            'contact_person'    => $json['contact_person'],
            'contact_phone'     => $json['contact_phone'],
            'code'              => $code
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

        $storeModel = new StoreModel();
        if ($storeModel->delete($id)) {
            return $this->respondDeleted(['message' => 'User berhasil dihapus']);
        }

        return $this->failNotFound('User tidak ditemukan');
    }
}