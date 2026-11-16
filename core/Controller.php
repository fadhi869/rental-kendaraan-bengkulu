<?php
abstract class Controller
{
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Flash::set('error', 'Silakan login terlebih dahulu.');
            redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!Auth::isAdmin()) {
            Flash::set('error', 'Akses ditolak. Halaman ini khusus Administrator.');
            redirect('/dashboard');
        }
    }

    protected function verifyCsrf(Request $request): void
    {
        if (!Csrf::verify($request->input('_csrf_token'))) {
            Flash::set('error', 'Sesi formulir kadaluarsa (CSRF tidak valid). Silakan ulangi.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
        }
    }

    protected function view(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        View::render($view, $data, $layout);
    }
}
