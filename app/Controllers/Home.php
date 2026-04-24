<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login/home');
    }

    public function reset($token = null)
    {
        if (!$token) {
            return redirect()->to('/login');
        }
        return view('login/reset', ['token' => $token]);
    }

    public function dashboard()
    {
        // tampilkan view dashboard
        return view('dashboard');
    }

    public function logout()
    {
        // logout cukup redirect ke login, token dihapus di frontend
        return redirect()->to('/login');
    }
}
