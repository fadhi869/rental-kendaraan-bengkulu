<?php
namespace App\Controllers;

use App\Services\PenyewaanService;
use Controller;
use Database;
use Flash;
use Request;

class PenyewaanController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $status = trim((string)$request->input('status', ''));

        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE 1=1
        ";
        $params = [];
        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        $sql .= " ORDER BY p.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $this->view('penyewaan/index', [
            'title'  => 'Transaksi Penyewaan — Rental Armada Bengkulu',
            'rows'   => $stmt->fetchAll(),
            'status' => $status,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $pelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll();
        $kendaraan = $pdo->query("
            SELECT k.*, jk.nama_jenis
            FROM kendaraan k
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE k.status = 'Tersedia'
            ORDER BY k.merek ASC, k.model ASC
        ")->fetchAll();

        $this->view('penyewaan/create', [
            'title'     => 'Buat Transaksi Sewa Baru',
            'pelanggan' => $pelanggan,
            'kendaraan' => $kendaraan,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $service = new PenyewaanService();
            $id = $service->createRental($request->all());
            Flash::set('success', 'Transaksi penyewaan berhasil dibuat.');
            redirect('/penyewaan/' . $id);
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
            redirect('/penyewaan/create');
        }
    }

    public function show(Request $request, string $id): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $stmt = $pdo->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.nik, pl.no_hp, pl.no_sim, pl.alamat,
                   k.plat_nomor, k.merek, k.model, k.warna, k.tahun, jk.nama_jenis,
                   u.nama AS petugas_nama
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            LEFT JOIN users u ON u.id = p.created_by
            WHERE p.id = :id
        ");
        $stmt->execute(['id' => (int)$id]);
        $sewa = $stmt->fetch();
        if (!$sewa) {
            Flash::set('error', 'Transaksi tidak ditemukan.');
            redirect('/penyewaan');
        }
        $this->view('penyewaan/show', [
            'title' => 'Detail Transaksi ' . $sewa['kode_transaksi'],
            'sewa'  => $sewa,
        ]);
    }

    public function serahKunci(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            (new PenyewaanService())->serahKunci((int)$id);
            Flash::set('success', 'Serah kunci berhasil. Status sewa kini Berjalan.');
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
        }
        redirect('/penyewaan/' . $id);
    }

    public function batal(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            (new PenyewaanService())->batalkan((int)$id);
            Flash::set('success', 'Transaksi penyewaan telah dibatalkan.');
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
        }
        redirect('/penyewaan/' . $id);
    }
}
