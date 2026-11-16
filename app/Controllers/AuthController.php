<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Auth;
use Controller;
use Flash;
use Request;

class AuthController extends Controller
{
    public function loginForm(Request $request): void
    {
        if (Auth::check()) {
            redirect('/dashboard');
        }
        $this->view('auth/login', [
            'title' => 'Masuk — Rental Armada Bengkulu',
        ], 'layouts/auth');
    }

    public function login(Request $request): void
    {
        $this->verifyCsrf($request);
        $username = trim((string)$request->input('username'));
        $password = (string)$request->input('password');

        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            Flash::set('error', 'Username atau password salah.');
            redirect('/login');
        }

        if (($user['status'] ?? 'active') !== 'active') {
            Flash::set('error', 'Akun dinonaktifkan. Hubungi admin.');
            redirect('/login');
        }

        $userModel->updateLastLogin((int)$user['id']);
        Auth::login($user);
        ActivityLogger::log('LOGIN', 'auth', "Pengguna {$user['nama']} masuk ke sistem.", (int)$user['id']);

        Flash::set('success', "Selamat datang, {$user['nama']}!");
        redirect('/dashboard');
    }

    public function logout(Request $request): void
    {
        $this->verifyCsrf($request);
        if (Auth::check()) {
            ActivityLogger::log('LOGOUT', 'auth', 'Pengguna keluar dari sistem.');
        }
        Auth::logout();
        Flash::set('success', 'Anda telah keluar.');
        redirect('/login');
    }
}
