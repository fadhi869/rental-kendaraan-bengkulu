<?php
declare(strict_types=1);

/**
 * Controller Autentikasi Pengguna (Login & Logout)
 */
class AuthController extends Controller
{
    public function loginForm(?Request $request = null): void
    {
        if (Auth::check()) {
            redirect('index.php');
        }
        $flash = Flash::get();
        require BASE_PATH . '/login.php';
    }

    public function login(Request $request): void
    {
        $this->verifyCsrf($request);
        $username = trim((string)$request->input('username'));
        $password = (string)$request->input('password');

        $adminModel = new Admin();
        $user = $adminModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            Flash::set('error', 'Username atau password yang dimasukkan salah.');
            redirect('login.php');
        }

        if (($user['status'] ?? 'active') !== 'active') {
            Flash::set('error', 'Akun Anda dinonaktifkan. Silakan hubungi Administrator.');
            redirect('login.php');
        }

        $adminModel->updateLastLogin((int)$user['id']);
        Auth::login($user);
        $adminModel->logActivity('LOGIN', 'auth', "Pengguna {$user['nama']} berhasil login.", (int)$user['id']);

        Flash::set('success', "Selamat datang kembali, {$user['nama']}!");
        redirect('index.php');
    }

    public function logout(Request $request): void
    {
        if (Auth::check()) {
            (new Admin())->logActivity('LOGOUT', 'auth', 'Pengguna keluar dari sesi.');
        }
        Auth::logout();
        Flash::set('success', 'Anda telah berhasil logout.');
        redirect('login.php');
    }
}
