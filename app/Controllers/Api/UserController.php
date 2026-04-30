<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use App\Models\StoreUserModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait; // 1. Tambahkan ini
use Config\Services;

class UserController extends Controller
{
    use ResponseTrait; // 2. Gunakan Trait di sini

    public function index()
    {
        $userModel = new UserModel();
        // Menggunakan respond() lebih ringkas daripada setJSON
        return $this->respond($userModel->findAll());
    }

    public function profile()
    {
        $payload = Services::jwtPayload()->get();
        return $this->respond([
            'status'    => 'success',
            'name'      => $payload['name'],
            'role'      => $payload['role']
        ]);
    }

    public function update($id = null)
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'superadmin') {
            return $this->failForbidden('Unauthorized');
        }
        $json           = $this->request->getJSON(true);
        $userModel      = new UserModel();
        $storeUserModel = new StoreUserModel();
        $db             = \Config\Database::connect();
        // 1. Cek apakah user ada
        $user           = $userModel->find($id);
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan.');
        }
        // 2. Mulai Transaksi
        $db->transStart();
        try {
            // A. Update data profil utama
            $userData = [
                'name'  => $json['name'],
                'role'  => $json['role'] ?? 'user',
            ];
            // Hanya update password jika diisi
            if (!empty($json['password'])) {
                $userData['password'] = password_hash($json['password'], PASSWORD_DEFAULT);
            }
            $userModel->update($id, $userData);
            // B. Sinkronisasi Akses Toko (Hanya jika role bukan superadmin)
            // Jika superadmin, biasanya kita hapus semua akses toko spesifik karena dia akses global
            $storeUserModel->where('user_id', $id)->delete();
            if (($json['role'] ?? 'user') !== 'superadmin' && !empty($json['stores'])) {
                foreach ($json['stores'] as $s) {
                    $storeUserModel->insert([
                        'user_id'    => $id,
                        'store_id'   => $s['store_id'],
                        'role'       => $s['role_in_store'] ?? 'staff',
                        'is_default' => (int)($s['is_default'] ?? 0) // <--- AMBIL DARI PAYLOAD
                    ]);
                }
            }
            // C. Selesaikan Transaksi
            $db->transComplete();
            if ($db->transStatus() === false) {
                return $this->fail('Gagal memperbarui data akses toko.');
            }
            return $this->respond([
                'status'  => 'success',
                'message' => 'User dan akses toko berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->failServerError('Terjadi kesalahan sistem.');
        }
    }

public function create()
{
    $payload = Services::jwtPayload()->get();
    if (!$payload || ($payload['role'] ?? 'guest') !== 'superadmin') {
        return $this->failForbidden('Unauthorized');
    }

    $json = $this->request->getJSON(true);
    
    if (empty($json['email']) || empty($json['password'])) {
        return $this->fail('Email dan password wajib diisi.');
    }

    $userModel      = new UserModel();
    $storeUserModel = new StoreUserModel();
    $db             = \Config\Database::connect();

    if ($userModel->where('email', $json['email'])->first()) {
        return $this->fail('Email sudah terdaftar. Silakan gunakan email lain.');
    }

    $db->transBegin();
    try {
        // A. Simpan ke tabel users
        $userData = [
            'name'      => $json['name'],
            'email'     => $json['email'],
            'password'  => password_hash($json['password'], PASSWORD_DEFAULT),
            'role'      => $json['role'] ?? 'user',
            'is_active' => 1
        ];

        if (!$userModel->insert($userData)) {
            $db->transRollback();
            return $this->fail($userModel->errors());
        }

        $newUserId = $userModel->getInsertID();

        // B. PERBAIKAN DI SINI: Loop array 'stores' dari JavaScript
        if (!empty($json['stores']) && is_array($json['stores'])) {
            foreach ($json['stores'] as $s) {
                $storeData = [
                    'user_id'    => $newUserId,
                    'store_id'   => $s['store_id'],
                    'role'       => $s['role_in_store'] ?? 'staff',
                    'is_default' => (int)($s['is_default'] ?? 0) // <--- AMBIL DARI PAYLOAD
                ];

                if (!$storeUserModel->insert($storeData)) {
                    $db->transRollback();
                    return $this->fail($storeUserModel->errors());
                }
            }
        }

        $db->transCommit();
        return $this->respondCreated([
            'status'  => 'success',
            'message' => 'User dan akses toko berhasil dibuat.',
            'data'    => ['id' => $newUserId]
        ]);

    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', '[CreateUser] Error: ' . $e->getMessage());
        return $this->failServerError('Terjadi kesalahan pada server.');
    }
}

    public function detail($id = null)
    {
        $userModel      = new UserModel();
        $storeUserModel = new StoreUserModel();
        $user = $userModel->find($id);
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }
        $user['stores'] = $storeUserModel->where('user_id', $id)->findAll();
        return $this->respond($user);
    }

    public function delete($id)
    {
        $payload = Services::jwtPayload()->get();
        if (!$payload || ($payload['role'] ?? 'guest') !== 'superadmin') {
            return $this->failForbidden('Unauthorized');
        }
        $userModel      = new UserModel();
        $storeUserModel = new StoreUserModel();
        $db             = \Config\Database::connect();
        $db->transBegin();
        try {
            $storeUserModel->where('user_id', $id)->delete();
            if ($userModel->delete($id)) {
                $db->transCommit();
                return $this->respondDeleted([
                    'status'   => 'success',
                    'messages' => 'User berhasil dihapus'
                ]);
            }
            return $this->failNotFound('User tidak ditemukan');
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->failServerError('Gagal menghapus user.');
        }
    }

    // --- TAMBAHKAN METHOD ini di bawah ---

    public function resetPassword($id)
    {
        $payload = Services::jwtPayload()->get();
        // Cek Payload & Role (Bisa diubah: Owner boleh reset user di toko sendiri)
        if (!$payload || ($payload['role'] ?? 'guest') !== 'superadmin') {
            return $this->failForbidden('Unauthorized');
        }
        $json = $this->request->getJSON(true);
        if (empty($json['password'])) {
            return $this->fail('Password baru wajib diisi.');
        }
        $userModel = new UserModel();
        // Cek User Ada?
        if (!$userModel->find($id)) {
            return $this->failNotFound('User tidak ditemukan');
        }
        // Update Password
        $userModel->update($id, [
            'password'  => password_hash($json['password'], PASSWORD_DEFAULT),
            'is_active' => 1 // Aktifkan kembali saat reset
        ]);
        return $this->respond([
            'status'  => 'success',
            'message' => 'Password berhasil di-reset.'
        ]);
    }
}