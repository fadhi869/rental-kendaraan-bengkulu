<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ActivityLogger;
use Auth;
use Controller;
use Flash;
use Request;
use Validator;

class UserController extends Controller
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index(Request $request): void
    {
        $this->requireAdmin();
        $q = trim((string)$request->input('q', ''));
        $users = $this->user->getAll($q);

        $this->view('admin/users/index', [
            'title' => 'Kelola Pengguna — Rental Armada Bengkulu',
            'users' => $users,
            'q'     => $q,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAdmin();
        $this->view('admin/users/create', [
            'title' => 'Tambah Pengguna — Rental Armada Bengkulu',
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $data = $request->all();

        $errors = Validator::validate($data, [
            'nama'     => 'required|max:100',
            'username' => 'required|min:4|max:50',
            'password' => 'required|min:6',
            'role'     => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('/admin/users/create');
        }

        if ($this->user->isUsernameTaken($data['username'])) {
            Flash::set('error', 'Username tersebut sudah digunakan.');
            redirect('/admin/users/create');
        }

        $this->user->create($data);
        ActivityLogger::log('TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: ' . trim($data['nama']));

        Flash::set('success', 'Akun pengguna berhasil ditambahkan.');
        redirect('/admin/users');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAdmin();
        $item = $this->user->findById((int)$id);

        if (!$item) {
            Flash::set('error', 'Pengguna tidak ditemukan.');
            redirect('/admin/users');
        }

        $this->view('admin/users/edit', [
            'title' => 'Edit Pengguna — ' . $item['nama'],
            'user'  => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        $rules = [
            'nama'     => 'required|max:100',
            'username' => 'required|min:4|max:50',
            'role'     => 'required',
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'min:6';
        }

        $errors = Validator::validate($data, $rules);
        if ($errors) {
            Flash::set('error', reset($errors));
            redirect("/admin/users/{$id}/edit");
        }

        if ($this->user->isUsernameTaken($data['username'], $intId)) {
            Flash::set('error', 'Username tersebut sudah digunakan oleh akun lain.');
            redirect("/admin/users/{$id}/edit");
        }

        $this->user->update($intId, $data);
        ActivityLogger::log('UPDATE_USER', 'user_management', 'Memperbarui data pengguna ' . trim($data['nama']));

        Flash::set('success', 'Data pengguna berhasil diperbarui.');
        redirect('/admin/users');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($intId === Auth::id()) {
            Flash::set('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
            redirect('/admin/users');
        }

        $newStatus = $this->user->toggleStatus($intId);
        $statusLabel = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log('TOGGLE_USER', 'user_management', "Mengubah status user ID {$id} menjadi {$newStatus}.");
        Flash::set('success', "Status akun berhasil {$statusLabel}.");
        redirect('/admin/users');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($intId === Auth::id()) {
            Flash::set('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('/admin/users');
        }

        $target = $this->user->findById($intId);
        try {
            $this->user->softDelete($intId);
            ActivityLogger::log('HAPUS_USER', 'user_management', 'Menghapus akun pengguna: ' . ($target['nama'] ?? "ID {$id}"));
            Flash::set('success', 'Akun pengguna berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal menghapus akun pengguna.');
        }
        redirect('/admin/users');
    }
}
