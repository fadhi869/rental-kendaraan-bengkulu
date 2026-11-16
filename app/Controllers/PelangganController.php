<?php
namespace App\Controllers;

use App\Services\ActivityLogger;
use Controller;
use Database;
use Flash;
use Request;
use Validator;

class PelangganController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $q = trim((string)$request->input('q', ''));
        $sql = "SELECT * FROM pelanggan";
        $params = [];
        if ($q !== '') {
            $sql .= " WHERE nama LIKE :q1 OR nik LIKE :q2 OR no_hp LIKE :q3 OR no_sim LIKE :q4";
            $like = "%{$q}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $this->view('master/index', [
            'title'   => 'Data Pelanggan — Rental Armada Bengkulu',
            'module'  => 'pelanggan',
            'heading' => 'Database Pelanggan & Penyewa',
            'sub'     => 'Informasi identitas NIK, SIM, dan kontak pelanggan terdaftar.',
            'rows'    => $stmt->fetchAll(),
            'q'       => $q,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $this->view('master/form', [
            'title'   => 'Tambah Pelanggan',
            'module'  => 'pelanggan',
            'heading' => 'Registrasi Pelanggan Baru',
            'action'  => base_url('pelanggan'),
            'item'    => [],
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $errors = Validator::validate($data, [
            'nik'    => 'required|min:16|max:16',
            'nama'   => 'required|max:100',
            'alamat' => 'required',
            'no_hp'  => 'required|max:15',
            'no_sim' => 'required|max:20',
        ]);
        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('/pelanggan/create');
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO pelanggan (nik, nama, alamat, no_hp, no_sim, email)
            VALUES (:nik, :nama, :alamat, :hp, :sim, :email)
        ");
        $stmt->execute([
            'nik'    => trim($data['nik']),
            'nama'   => trim($data['nama']),
            'alamat' => trim($data['alamat']),
            'hp'     => trim($data['no_hp']),
            'sim'    => trim($data['no_sim']),
            'email'  => trim($data['email'] ?? '') ?: null,
        ]);
        ActivityLogger::log('TAMBAH', 'pelanggan', 'Menambah pelanggan baru: ' . trim($data['nama']));
        Flash::set('success', 'Data pelanggan berhasil disimpan.');
        redirect('/pelanggan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $item = $stmt->fetch();
        if (!$item) {
            Flash::set('error', 'Pelanggan tidak ditemukan.');
            redirect('/pelanggan');
        }
        $this->view('master/form', [
            'title'   => 'Edit Pelanggan',
            'module'  => 'pelanggan',
            'heading' => 'Perbarui Data Pelanggan',
            'action'  => base_url("pelanggan/{$id}"),
            'item'    => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $pdo = Database::connection();
        $pdo->prepare("
            UPDATE pelanggan
            SET nik = :nik, nama = :nama, alamat = :alamat, no_hp = :hp, no_sim = :sim, email = :email
            WHERE id = :id
        ")->execute([
            'nik'    => trim($data['nik']),
            'nama'   => trim($data['nama']),
            'alamat' => trim($data['alamat']),
            'hp'     => trim($data['no_hp']),
            'sim'    => trim($data['no_sim']),
            'email'  => trim($data['email'] ?? '') ?: null,
            'id'     => (int)$id,
        ]);
        ActivityLogger::log('UPDATE', 'pelanggan', 'Memperbarui pelanggan: ' . trim($data['nama']));
        Flash::set('success', 'Data pelanggan berhasil diperbarui.');
        redirect('/pelanggan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $pdo = Database::connection();
            $pdo->prepare("DELETE FROM pelanggan WHERE id = :id")->execute(['id' => (int)$id]);
            ActivityLogger::log('HAPUS', 'pelanggan', 'Menghapus pelanggan ID ' . $id);
            Flash::set('success', 'Data pelanggan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Pelanggan tidak dapat dihapus karena masih memiliki catatan transaksi.');
        }
        redirect('/pelanggan');
    }
}
