<?php

namespace App\Controllers\Api;

use App\Models\ApiKeyModel;
use App\Models\ApiLogModel;
use CodeIgniter\Controller; // Bisa tetap pakai Controller biasa
use CodeIgniter\API\ResponseTrait; // WAJIB TAMBAH INI
use Config\Services;

class ApiKeyController extends Controller
{
    use ResponseTrait; // WAJIB AKTIFKAN DI SINI

    public function index()
    {
        $payload    = Services::jwtPayload()->get();
        $role       = $payload['role'] ?? 'staff';
        $email      = $payload['email'] ?? '';
        $model      = new ApiKeyModel();
        if ($role === 'admin') {
            $data   = $model->findAll();
        } else {
            $data   = $model->where('user_email', $email)->findAll();
        }

        return $this->respond($data);
    }

    public function create()
    {
        $model = new ApiKeyModel();
        $json = $this->request->getJSON();
        if (!$json || empty($json->label)) {
            return $this->fail('Label wajib diisi');
        }

        $plainKey = 'api_live_' . bin2hex(random_bytes(20));
        $data = [
            'user_email'   => $json->email,
            'key_label'    => $json->label,
            'ip_whitelist' => $json->ip ?? null,
            'api_key'      => hash('sha256', $plainKey),
            'is_active'    => 1
        ];

        if ($model->insert($data)) {
            return $this->respond([
                'status'  => 200,
                'message' => 'Key generated successfully',
                'key'     => $plainKey
            ]);
        }
        return $this->fail('Gagal menyimpan ke database');
    }

    public function bulkStatus()
    {
        $json   = $this->request->getJSON();
        $ids    = $json->ids ?? []; // Kasih default array kosong jika null
        $status = $json->status;
        // VALIDASI KRUSIAL: Pastikan ada ID yang dikirim
        if (empty($ids) || !is_array($ids)) {
            return $this->respond([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih!'
            ], 400);
        }
        $model  = new ApiKeyModel();
        // Update query
        $updated = $model->whereIn('id', $ids)
                        ->set(['is_active' => $status])
                        ->update();
        if ($updated) {
            $payload    = Services::jwtPayload()->get();
            $userId     = $payload['user_id'] ?? 'staff';
            $userEmail  = $payload['email'] ?? '';
            $items      = $model->whereIn('id', $ids)->select('id, key_label')->findAll();
            $details    = array_map(function($item) {
                return "Identity : ".$item['key_label'] . " (#" . $item['id'] . ")";
            }, $items);

            $logModel   = new ApiLogModel();
            $logModel->insert([
                'ip_address' => $this->request->getIPAddress(),
                'method'     => 'BULK_STATUS',
                'uri'        => (string) $this->request->getUri(),
                'status_code'=> 200,
                'user_agent' => $this->request->getUserAgent(),
                'user_id'    => $userId,
                'user_email' => $userEmail,
                'payload'    => json_encode([
                    'action'    => 'UPDATE_STATUS',
                    'count'     => count($ids),
                    'items'     => $details
                ])
            ]);

            return $this->respond([
                'success' => true,
                'message' => count($ids) . ' API Keys status updated successfully'
            ]);
        }
        return $this->fail('Failed to update database.');
    }

    public function bulkDelete()
    {
        $payload        = Services::jwtPayload()->get();
        $userId         = $payload['user_id'] ?? 'staff';
        $userEmail      = $payload['email'] ?? '';
        $json           = $this->request->getJSON();
        $ids            = $json->ids;
        if (empty($ids)) {
            return $this->fail('No API Keys selected.');
        }
        $model          = new ApiKeyModel();
        $items          = $model->whereIn('id', $ids)->select('id, key_label')->findAll();
        $details        = array_map(function($item) {
            return "Identity : ".$item['key_label'] . " (#" . $item['id'] . ")";
        }, $items);
        $deleted        = $model->delete($ids); // TRUE jika berhasil
        if ($deleted) {
            $payload    = Services::jwtPayload()->get();
            $userId     = $payload['user_id'] ?? 'staff';
            $userEmail  = $payload['email'] ?? '';

            $logModel   = new ApiLogModel();
            $logModel->insert([
                'ip_address' => $this->request->getIPAddress(),
                'method'     => 'BULK_DELETE',
                'uri'        => (string) $this->request->getUri(),
                'status_code'=> 200,
                'user_agent' => $this->request->getUserAgent(),
                'user_id'    => $userId,
                'user_email' => $userEmail,
                'payload'    => json_encode([
                    'action'    => 'REVOKE_KEYS',
                    'count'     => count($ids),
                    'items'     => $details
                ])
            ]);

            return $this->respond([
                'success' => true,
                'message' => 'API Key berhasil di Revoke'
            ]);
        }
        return $this->fail('Failed to delete database.');
    }

    public function delete($id = null)
    {
        $payload    = Services::jwtPayload()->get();
        $role       = $payload['role'] ?? 'staff';
        $email      = $payload['email'] ?? '';
        $model      = new ApiKeyModel();
        $key        = $model->find($id);
        if (!$key) {
            return $this->failNotFound('API Key tidak ditemukan');
        }
        // Proteksi: Jika bukan admin, pastikan email pemilik key sama dengan email login
        if ($role !== 'admin' && $key['user_email'] !== $email) {
            return $this->failForbidden('Anda tidak memiliki akses untuk menghapus key ini');
        }
        if ($model->delete($id)) {
            return $this->respondDeleted(['message' => 'API Key berhasil direvoke']);
        }
        return $this->fail('Gagal menghapus API Key');
    }
}