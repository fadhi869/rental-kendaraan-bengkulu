<?php
namespace App\Controllers;

use App\Services\ActivityLogger;
use Controller;
use Database;
use Flash;
use Request;
use Validator;

class KendaraanController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $q = trim((string)$request->input('q', ''));
        $status = trim((string)$request->input('status', ''));

        $sql = "
            SELECT k.*, jk.nama_jenis
            FROM kendaraan k
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE 1=1
        ";
        $params = [];
        if ($q !== '') {
            $sql .= " AND (k.plat_nomor LIKE :q1 OR k.merek LIKE :q2 OR k.model LIKE :q3)";
            $like = "%{$q}%";
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
        }
        if ($status !== '') {
            $sql .= " AND k.status = :status";
            $params['status'] = $status;
        }
        $sql .= " ORDER BY k.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $this->view('master/index', [
            'title'   => 'Data Kendaraan — Rental Armada Bengkulu',
            'module'  => 'kendaraan',
            'heading' => 'Manajemen Armada Kendaraan',
            'sub'     => 'Daftar unit mobil & motor beserta tarif harian dan status ketersediaan.',
            'rows'    => $stmt->fetchAll(),
            'q'       => $q,
            'status'  => $status,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $jenisList = $pdo->query("SELECT * FROM jenis_kendaraan ORDER BY nama_jenis ASC")->fetchAll();
        $this->view('master/form', [
            'title'     => 'Tambah Kendaraan',
            'module'    => 'kendaraan',
            'heading'   => 'Tambah Unit Kendaraan Baru',
            'action'    => base_url('kendaraan'),
            'item'      => [],
            'jenisList' => $jenisList,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $errors = Validator::validate($data, [
            'plat_nomor'     => 'required|max:15',
            'merek'          => 'required|max:50',
            'model'          => 'required|max:50',
            'tahun'          => 'required|numeric',
            'warna'          => 'required|max:30',
            'tarif_per_hari' => 'required|numeric',
            'jenis_id'       => 'required|numeric',
        ]);
        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('/kendaraan/create');
        }
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            INSERT INTO kendaraan (plat_nomor, merek, model, tahun, warna, tarif_per_hari, status, jenis_id)
            VALUES (:plat, :merek, :model, :tahun, :warna, :tarif, :status, :jenis)
        ");
        $stmt->execute([
            'plat'   => strtoupper(trim($data['plat_nomor'])),
            'merek'  => trim($data['merek']),
            'model'  => trim($data['model']),
            'tahun'  => (int)$data['tahun'],
            'warna'  => trim($data['warna']),
            'tarif'  => (float)$data['tarif_per_hari'],
            'status' => $data['status'] ?? 'Tersedia',
            'jenis'  => (int)$data['jenis_id'],
        ]);
        ActivityLogger::log('TAMBAH', 'kendaraan', 'Menambah unit kendaraan: ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Unit kendaraan berhasil ditambahkan.');
        redirect('/kendaraan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM kendaraan WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $item = $stmt->fetch();
        if (!$item) {
            Flash::set('error', 'Kendaraan tidak ditemukan.');
            redirect('/kendaraan');
        }
        $jenisList = $pdo->query("SELECT * FROM jenis_kendaraan ORDER BY nama_jenis ASC")->fetchAll();
        $this->view('master/form', [
            'title'     => 'Edit Kendaraan',
            'module'    => 'kendaraan',
            'heading'   => 'Edit Data Unit Kendaraan',
            'action'    => base_url("kendaraan/{$id}"),
            'item'      => $item,
            'jenisList' => $jenisList,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();
        $pdo = Database::connection();
        $pdo->prepare("
            UPDATE kendaraan
            SET plat_nomor = :plat, merek = :merek, model = :model, tahun = :tahun,
                warna = :warna, tarif_per_hari = :tarif, status = :status, jenis_id = :jenis
            WHERE id = :id
        ")->execute([
            'plat'   => strtoupper(trim($data['plat_nomor'])),
            'merek'  => trim($data['merek']),
            'model'  => trim($data['model']),
            'tahun'  => (int)$data['tahun'],
            'warna'  => trim($data['warna']),
            'tarif'  => (float)$data['tarif_per_hari'],
            'status' => $data['status'] ?? 'Tersedia',
            'jenis'  => (int)$data['jenis_id'],
            'id'     => (int)$id,
        ]);
        ActivityLogger::log('UPDATE', 'kendaraan', 'Memperbarui kendaraan ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Data kendaraan berhasil diperbarui.');
        redirect('/kendaraan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $pdo = Database::connection();
            $pdo->prepare("DELETE FROM kendaraan WHERE id = :id")->execute(['id' => (int)$id]);
            ActivityLogger::log('HAPUS', 'kendaraan', 'Menghapus kendaraan ID ' . $id);
            Flash::set('success', 'Unit kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Kendaraan tidak dapat dihapus karena memiliki riwayat penyewaan.');
        }
        redirect('/kendaraan');
    }
}
