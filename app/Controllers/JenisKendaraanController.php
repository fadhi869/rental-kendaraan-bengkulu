<?php
namespace App\Controllers;

use App\Services\ActivityLogger;
use Controller;
use Database;
use Flash;
use Request;
use Validator;

class JenisKendaraanController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $rows = $pdo->query("
            SELECT jk.*, COUNT(k.id) AS jumlah_unit
            FROM jenis_kendaraan jk
            LEFT JOIN kendaraan k ON k.jenis_id = jk.id
            GROUP BY jk.id
            ORDER BY jk.id ASC
        ")->fetchAll();

        $this->view('master/index', [
            'title'   => 'Jenis Kendaraan — Rental Armada Bengkulu',
            'module'  => 'jenis-kendaraan',
            'heading' => 'Kategori & Jenis Kendaraan',
            'sub'     => 'Kelola klasifikasi tipe kendaraan armada beserta deskripsi layanan.',
            'rows'    => $rows,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $this->view('master/form', [
            'title'   => 'Tambah Jenis Kendaraan',
            'module'  => 'jenis-kendaraan',
            'heading' => 'Tambah Jenis Kendaraan Baru',
            'action'  => base_url('jenis-kendaraan'),
            'item'    => [],
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $errors = Validator::validate($data, ['nama_jenis' => 'required|max:50']);
        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('/jenis-kendaraan/create');
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare("INSERT INTO jenis_kendaraan (nama_jenis, deskripsi) VALUES (:n, :d)");
        $stmt->execute(['n' => trim($data['nama_jenis']), 'd' => trim($data['deskripsi'] ?? '')]);
        ActivityLogger::log('TAMBAH', 'jenis_kendaraan', 'Menambah jenis kendaraan: ' . trim($data['nama_jenis']));
        Flash::set('success', 'Jenis kendaraan berhasil ditambahkan.');
        redirect('/jenis-kendaraan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM jenis_kendaraan WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $item = $stmt->fetch();
        if (!$item) {
            Flash::set('error', 'Data tidak ditemukan.');
            redirect('/jenis-kendaraan');
        }
        $this->view('master/form', [
            'title'   => 'Edit Jenis Kendaraan',
            'module'  => 'jenis-kendaraan',
            'heading' => 'Edit Jenis Kendaraan',
            'action'  => base_url("jenis-kendaraan/{$id}"),
            'item'    => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $pdo = Database::connection();
        $pdo->prepare("UPDATE jenis_kendaraan SET nama_jenis = :n, deskripsi = :d WHERE id = :id")
            ->execute(['n' => trim($data['nama_jenis']), 'd' => trim($data['deskripsi'] ?? ''), 'id' => (int)$id]);
        ActivityLogger::log('UPDATE', 'jenis_kendaraan', 'Memperbarui jenis kendaraan ID ' . $id);
        Flash::set('success', 'Jenis kendaraan berhasil diperbarui.');
        redirect('/jenis-kendaraan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $pdo = Database::connection();
            $pdo->prepare("DELETE FROM jenis_kendaraan WHERE id = :id")->execute(['id' => (int)$id]);
            ActivityLogger::log('HAPUS', 'jenis_kendaraan', 'Menghapus jenis kendaraan ID ' . $id);
            Flash::set('success', 'Jenis kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Tidak dapat menghapus jenis kendaraan yang masih memiliki unit terkait.');
        }
        redirect('/jenis-kendaraan');
    }
}
