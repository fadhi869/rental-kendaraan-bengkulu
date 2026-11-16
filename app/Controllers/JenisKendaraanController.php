<?php
namespace App\Controllers;

use App\Models\JenisKendaraan;
use App\Services\ActivityLogger;
use Controller;
use Flash;
use Request;
use Validator;

class JenisKendaraanController extends Controller
{
    private JenisKendaraan $jenis;

    public function __construct()
    {
        $this->jenis = new JenisKendaraan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $rows = $this->jenis->getAllWithUnitCount();

        $this->view('jenis-kendaraan/index', [
            'title' => 'Jenis Kendaraan — Rental Armada Bengkulu',
            'rows'  => $rows,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $this->view('jenis-kendaraan/create', [
            'title' => 'Tambah Jenis Kendaraan — Rental Armada Bengkulu',
            'item'  => [],
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

        $this->jenis->create($data);
        ActivityLogger::log('TAMBAH', 'jenis_kendaraan', 'Menambah jenis kendaraan: ' . trim($data['nama_jenis']));

        Flash::set('success', 'Jenis kendaraan berhasil disimpan.');
        redirect('/jenis-kendaraan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->jenis->findById((int)$id);

        if (!$item) {
            Flash::set('error', 'Jenis kendaraan tidak ditemukan.');
            redirect('/jenis-kendaraan');
        }

        $this->view('jenis-kendaraan/edit', [
            'title' => 'Edit Jenis Kendaraan — ' . $item['nama_jenis'],
            'item'  => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        $errors = Validator::validate($data, ['nama_jenis' => 'required|max:50']);
        if ($errors) {
            Flash::set('error', reset($errors));
            redirect("/jenis-kendaraan/{$id}/edit");
        }

        $this->jenis->update($intId, $data);
        ActivityLogger::log('UPDATE', 'jenis_kendaraan', 'Memperbarui jenis kendaraan ID ' . $id);

        Flash::set('success', 'Jenis kendaraan berhasil diperbarui.');
        redirect('/jenis-kendaraan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->jenis->hasUnits($intId)) {
            Flash::set('error', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh unit armada.');
            redirect('/jenis-kendaraan');
        }

        try {
            $this->jenis->delete($intId);
            ActivityLogger::log('HAPUS', 'jenis_kendaraan', 'Menghapus jenis kendaraan ID ' . $id);
            Flash::set('success', 'Jenis kendaraan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal menghapus jenis kendaraan: data masih terhubung dengan unit armada.');
        }
        redirect('/jenis-kendaraan');
    }
}
