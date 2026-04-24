<?php

namespace App\Controllers\Api;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends ResourceController
{
    private function generateAccessToken($user)
    {
        $issuedAt   = time();
        $expireAt   = $issuedAt + 3600; // 1 jam

        $payload = [
            'iss'  => 'apici',        // issuer
            'iat'  => $issuedAt,      // issued at
            'nbf'  => $issuedAt,      // not before
            'exp'  => $expireAt,      // expired at
            'data' => [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role']
            ]
        ];

        $secretKey = getenv('jwt.secret');
        return JWT::encode($payload, $secretKey, 'HS256');
    }

    private function generateRefreshToken($user)
    {
        $issuedAt   = time();
        $expireAt   = $issuedAt + (7 * 24 * 3600); // 7 hari

        $payload = [
            'iss'  => 'apici',
            'iat'  => $issuedAt,
            'nbf'  => $issuedAt,
            'exp'  => $expireAt,
            'data' => [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role']
            ]
        ];

        $secretKey = getenv('jwt.refresh_secret');
        return JWT::encode($payload, $secretKey, 'HS256');
    }

    public function login()
    {
        $json       = $this->request->getJSON(true);
        $email      = $json['email'] ?? '';
        $password   = $json['password'] ?? '';

        $userModel  = new UserModel();
        $user       = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Email atau password salah'
            ])->setStatusCode(401);
        }

        $accessToken    = $this->generateAccessToken($user);
        $refreshToken   = $this->generateRefreshToken($user);

        return $this->response->setJSON([
            'status'        => 'success',
            'message'       => 'Login berhasil',
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'user'          => [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role']
            ]
        ]);
    }

    public function refresh()
    {
        $json           = $this->request->getJSON(true);
        $refreshToken   = $json['refresh_token'] ?? '';

        if (!$refreshToken) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Refresh token tidak ditemukan'
            ])->setStatusCode(400);
        }

        try {
            $decoded    = JWT::decode($refreshToken, new Key(getenv('jwt.refresh_secret'), 'HS256'));
            $userId     = $decoded->data->id;

            $userModel  = new UserModel();
            $user       = $userModel->find($userId);

            if (!$user) {
                return $this->response->setJSON([
                    'status'    => 'error',
                    'message'   => 'User tidak ditemukan'
                ])->setStatusCode(404);
            }

            $newAccessToken = $this->generateAccessToken($user);
            return $this->response->setJSON([
                'status'        => 'success',
                'message'       => 'Access token diperbarui',
                'access_token'  => $newAccessToken
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'    => 'error',
                'message'   => 'Refresh token invalid atau expired'
            ])->setStatusCode(401);
        }
    }

    public function forgotPassword()
    {
        // FIX: Ambil data JSON jika getPost kosong
        $json       = $this->request->getJSON();
        $email      = $json->email ?? $this->request->getPost('email');
        if (!$email) {
            return $this->fail('Email harus diisi.');
        }
        $userModel  = new UserModel();
        // 1. Cek User berdasarkan email
        $user       = $userModel->where('email', $email)->first();
        // Tetap berikan 200 OK meskipun email tidak ada demi keamanan
        if (!$user) {
            return $this->respond(['message' => 'Jika email terdaftar, instruksi reset akan dikirim.'], 200);
        }
        // 2. Generate Token Unik
        $token      = bin2hex(random_bytes(32));
        $expires    = date('Y-m-d H:i:s', strtotime('+1 hour'));
        // 3. Simpan ke Database
        // Pastikan field 'reset_token' dan 'reset_expires' sudah ada di $allowedFields di UserModel
        $userModel->update($user['id'], [
            'reset_token'   => $token,
            'reset_expires' => $expires
        ]);
        // 4. Kirim Email
        $emailService   = \Config\Services::email();
        $resetLink      = base_url("reset-password/" . $token);
        $emailService->setTo($email);
        $emailService->setFrom('renshigenoi@gmail.com', 'API Management');
        $emailService->setSubject('Reset Password - API Management');

        // Tambahkan styling sedikit agar email terlihat profesional
        $message = "
            <div style='font-family: sans-serif; padding: 20px; color: #333;'>
                <h2>Reset Password</h2>
                <p>Halo <b>{$user['name']}</b>,</p>
                <p>Kami menerima permintaan untuk mereset password akun Anda.</p>
                <a href='{$resetLink}' style='display: inline-block; padding: 12px 20px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;'>Reset Password Sekarang</a>
                <p style='margin-top: 20px; font-size: 12px; color: #777;'>Link ini akan kadaluarsa dalam 1 jam.</p>
            </div>
        ";
        $emailService->setMessage($message);

        if ($emailService->send()) {
            return $this->respond(['message' => 'Jika email terdaftar, instruksi reset akan dikirim.'], 200);
        } else {
            // Jika gagal, log error-nya untuk debugging
            log_message('error', $emailService->printDebugger(['headers']));
            return $this->fail('Gagal mengirim email. Pastikan konfigurasi SMTP di app/Config/Email.php sudah benar.');
        }
    }

    public function updatePassword()
    {
        $json           = $this->request->getJSON();
        $token          = $json->token ?? '';
        $newPassword    = $json->password ?? '';
        if (empty($token) || empty($newPassword)) {
            return $this->fail('Data tidak lengkap.');
        }
        $userModel      = new UserModel();
        // Debug: Cek apakah user ada tanpa filter tanggal
        $checkUser      = $userModel->where('reset_token', $token)->first();
        if (!$checkUser) {
            return $this->fail('Token tidak ditemukan di DB. Token di input: ' . $token);
        }
        $now = date('Y-m-d H:i:s');
        if ($checkUser['reset_expires'] < $now) {
            return $this->fail('Token ditemukan tapi kadaluarsa. Jam DB: ' . $checkUser['reset_expires'] . ' Jam Sekarang: ' . $now);
        }
        // 2. Update password dan hapus token
        $userModel->update($checkUser['id'], [
            'password'      => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token'   => null, // Hapus token agar tidak bisa dipakai lagi
            'reset_expires' => null
        ]);
        return $this->respond(['message' => 'Password berhasil diperbarui.'], 200);
    }
}
