<?php
namespace App\Controllers;

use App\Services\ActivityLogger;
use Auth;
use Controller;
use Database;
use Flash;
use Request;
use Validator;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();
        $q = trim((string)$request->input('q', ''));
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        $params = [];
        if ($q !== '') {
            $sql .= " AND (nama LIKE :q1 OR username LIKE :q2 OR email LIKE :q3)";
            $like = "%{$q}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like];
        }
        $sql .= " ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $this->view('admin/users/index', [
            'title' => 'Manajemen User — Rental Armada Bengkulu',
            'users' => $stmt->fetchAll(),
            'q'     => $q,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAdmin();
        $this->view('admin/users/create', [
            'title' => 'Tambah Akun Pengguna',
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
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, nama, email, role, status)
            VALUES (:u, :p, :n, :e, :r, :s)
        ");
        $stmt->execute([
            'u' => trim($data['username']),
            'p' => password_hash($data['password'], PASSWORD_BCRYPT),
            'n' => trim($data['nama']),
            'e' => trim($data['email'] ?? '') ?: null,
            'r' => ($data['role'] === 'admin') ? 'admin' : 'petugas',
            's' => ($data['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
        ]);
        ActivityLogger::log('TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: ' . trim($data['nama']));
        Flash::set('success', 'Akun pengguna berhasil ditambahkan.');
        redirect('/admin/users');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAdmin();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute(['id' => (int)$id]);
        $user = $stmt->fetch();
        if (!$user) {
            Flash::set('error', 'User tidak ditemukan.');
            redirect('/admin/users');
        }
        $this->view('admin/users/edit', [
            'title' => 'Edit Akun Pengguna',
            'user'  => $user,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $data = $request->all();
        $pdo = Database::connection();

        if (!empty($data['password'])) {
            $stmt = $pdo->prepare("
                UPDATE users SET nama = :n, username = :u, email = :e, role = :r, status = :s, password = :p
                WHERE id = :id
            ");
            $stmt->execute([
                'n'  => trim($data['nama']),
                'u'  => trim($data['username']),
                'e'  => trim($data['email'] ?? '') ?: null,
                'r'  => ($data['role'] === 'admin') ? 'admin' : 'petugas',
                's'  => ($data['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
                'p'  => password_hash($data['password'], PASSWORD_BCRYPT),
                'id' => (int)$id,
            ]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users SET nama = :n, username = :u, email = :e, role = :r, status = :s
                WHERE id = :id
            ");
            $stmt->execute([
                'n'  => trim($data['nama']),
                'u'  => trim($data['username']),
                'e'  => trim($data['email'] ?? '') ?: null,
                'r'  => ($data['role'] === 'admin') ? 'admin' : 'petugas',
                's'  => ($data['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
                'id' => (int)$id,
            ]);
        }
        ActivityLogger::log('UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID ' . $id);
        Flash::set('success', 'Data pengguna berhasil diperbarui.');
        redirect('/admin/users');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        if ((int)$id === Auth::id()) {
            Flash::set('error', 'Tidak dapat menonaktifkan akun Anda sendiri.');
            redirect('/admin/users');
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT status, username FROM users WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $u = $stmt->fetch();
        if ($u) {
            $next = $u['status'] === 'active' ? 'inactive' : 'active';
            $pdo->prepare("UPDATE users SET status = :s WHERE id = :id")->execute(['s' => $next, 'id' => (int)$id]);
            ActivityLogger::log('STATUS_USER', 'user_management', "Mengubah status {$u['username']} menjadi {$next}");
            Flash::set('success', 'Status pengguna diperbarui.');
        }
        redirect('/admin/users');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        if ((int)$id === Auth::id()) {
            Flash::set('error', 'Tidak dapat menghapus akun yang sedang aktif digunakan.');
            redirect('/admin/users');
        }
        $pdo = Database::connection();
        $pdo->prepare("UPDATE users SET deleted_at = NOW(), status = 'inactive' WHERE id = :id")->execute(['id' => (int)$id]);
        ActivityLogger::log('HAPUS_USER', 'user_management', 'Menghapus akun pengguna ID ' . $id);
        Flash::set('success', 'Akun pengguna berhasil dihapus.');
        redirect('/admin/users');
    }
}
