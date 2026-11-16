<?php
namespace App\Controllers;

use App\Models\JenisKendaraan;
use App\Models\Kendaraan;
use App\Services\ActivityLogger;
use Controller;
use Flash;
use Request;
use Validator;

class KendaraanController extends Controller
{
    private Kendaraan $kendaraan;
    private JenisKendaraan $jenis;

    public function __construct()
    {
        $this->kendaraan = new Kendaraan();
        $this->jenis = new JenisKendaraan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $q = trim((string)$request->input('q', ''));
        $status = trim((string)$request->input('status', ''));

        $rows = $this->kendaraan->getFiltered($q, $status);

        $this->view('kendaraan/index', [
            'title'   => 'Data Kendaraan — Rental Armada Bengkulu',
            'rows'    => $rows,
            'q'       => $q,
            'status'  => $status,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $jenisList = $this->jenis->getAll();

        $this->view('kendaraan/create', [
            'title'     => 'Tambah Kendaraan — Rental Armada Bengkulu',
            'jenisList' => $jenisList,
            'item'      => [],
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

        if ($this->kendaraan->isPlateTaken($data['plat_nomor'])) {
            Flash::set('error', 'Plat nomor sudah digunakan oleh kendaraan lain.');
            redirect('/kendaraan/create');
        }

        $this->kendaraan->create($data);
        ActivityLogger::log('TAMBAH', 'kendaraan', 'Menambah unit kendaraan: ' . strtoupper(trim($data['plat_nomor'])));

        Flash::set('success', 'Data kendaraan berhasil disimpan.');
        redirect('/kendaraan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->kendaraan->findById((int)$id);

        if (!$item) {
            Flash::set('error', 'Data kendaraan tidak ditemukan.');
            redirect('/kendaraan');
        }

        $jenisList = $this->jenis->getAll();

        $this->view('kendaraan/edit', [
            'title'     => 'Edit Kendaraan — ' . $item['plat_nomor'],
            'item'      => $item,
            'jenisList' => $jenisList,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
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
            redirect("/kendaraan/{$id}/edit");
        }

        if ($this->kendaraan->isPlateTaken($data['plat_nomor'], $intId)) {
            Flash::set('error', 'Plat nomor sudah digunakan oleh kendaraan lain.');
            redirect("/kendaraan/{$id}/edit");
        }

        $this->kendaraan->update($intId, $data);
        ActivityLogger::log('UPDATE', 'kendaraan', 'Memperbarui data unit ' . strtoupper(trim($data['plat_nomor'])));

        Flash::set('success', 'Data kendaraan berhasil diperbarui.');
        redirect('/kendaraan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->kendaraan->hasActiveRentals($intId)) {
            Flash::set('error', 'Kendaraan tidak dapat dihapus karena masih ada transaksi sewa aktif.');
            redirect('/kendaraan');
        }

        if ($this->kendaraan->hasRentals($intId)) {
            Flash::set('error', 'Kendaraan tidak dapat dihapus karena tersimpan dalam riwayat penyewaan.');
            redirect('/kendaraan');
        }

        $unit = $this->kendaraan->findById($intId);
        try {
            $this->kendaraan->delete($intId);
            ActivityLogger::log('HAPUS', 'kendaraan', 'Menghapus unit ' . ($unit['plat_nomor'] ?? "ID {$id}"));
            Flash::set('success', 'Data kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal menghapus kendaraan: unit masih terhubung dengan data lain.');
        }
        redirect('/kendaraan');
    }
}
