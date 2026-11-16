<?php
namespace App\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use App\Services\PenyewaanService;
use Controller;
use Flash;
use Request;

class PenyewaanController extends Controller
{
    private Penyewaan $penyewaan;
    private Pelanggan $pelanggan;
    private Kendaraan $kendaraan;
    private PenyewaanService $service;

    public function __construct()
    {
        $this->penyewaan = new Penyewaan();
        $this->pelanggan = new Pelanggan();
        $this->kendaraan = new Kendaraan();
        $this->service   = new PenyewaanService();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $status = trim((string)$request->input('status', ''));
        $rows = $this->penyewaan->getFiltered($status);

        $this->view('penyewaan/index', [
            'title'  => 'Transaksi Penyewaan — Rental Armada Bengkulu',
            'rows'   => $rows,
            'status' => $status,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $pelanggan = $this->pelanggan->getAll();
        $kendaraan = $this->kendaraan->getAvailable();

        $this->view('penyewaan/create', [
            'title'     => 'Buat Sewa Baru — Rental Armada Bengkulu',
            'pelanggan' => $pelanggan,
            'kendaraan' => $kendaraan,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);

        try {
            $id = $this->service->createRental($request->all());
            Flash::set('success', 'Transaksi sewa berhasil dibuat.');
            redirect('/penyewaan/' . $id);
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
            redirect('/penyewaan/create');
        }
    }

    public function show(Request $request, string $id): void
    {
        $this->requireAuth();
        $sewa = $this->penyewaan->findByIdWithDetails((int)$id);

        if (!$sewa) {
            Flash::set('error', 'Transaksi tidak ditemukan.');
            redirect('/penyewaan');
        }

        $this->view('penyewaan/show', [
            'title' => 'Detail Sewa ' . $sewa['kode_transaksi'],
            'sewa'  => $sewa,
        ]);
    }

    public function serahKunci(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);

        try {
            $this->service->serahKunci((int)$id);
            Flash::set('success', 'Kunci berhasil diserahkan. Masa sewa telah dimulai.');
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
            $this->service->batalkan((int)$id);
            Flash::set('success', 'Transaksi sewa berhasil dibatalkan.');
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
        }

        redirect('/penyewaan/' . $id);
    }
}
