<?php
namespace App\Controllers;

use App\Models\Pelanggan;
use App\Services\ActivityLogger;
use Controller;
use Flash;
use Request;
use Validator;

class PelangganController extends Controller
{
    private Pelanggan $pelanggan;

    public function __construct()
    {
        $this->pelanggan = new Pelanggan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $q = trim((string)$request->input('q', ''));
        $rows = $this->pelanggan->getFiltered($q);

        $this->view('pelanggan/index', [
            'title' => 'Data Pelanggan — Rental Armada Bengkulu',
            'rows'  => $rows,
            'q'     => $q,
        ]);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $this->view('pelanggan/create', [
            'title' => 'Tambah Pelanggan — Rental Armada Bengkulu',
            'item'  => [],
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

        if ($this->pelanggan->isNikTaken($data['nik'])) {
            Flash::set('error', 'NIK sudah terdaftar.');
            redirect('/pelanggan/create');
        }

        $this->pelanggan->create($data);
        ActivityLogger::log('TAMBAH', 'pelanggan', 'Menambah data pelanggan: ' . trim($data['nama']));

        Flash::set('success', 'Pelanggan berhasil disimpan.');
        redirect('/pelanggan');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->pelanggan->findById((int)$id);

        if (!$item) {
            Flash::set('error', 'Pelanggan tidak ditemukan.');
            redirect('/pelanggan');
        }

        $this->view('pelanggan/edit', [
            'title' => 'Edit Pelanggan — ' . $item['nama'],
            'item'  => $item,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
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
            redirect("/pelanggan/{$id}/edit");
        }

        if ($this->pelanggan->isNikTaken($data['nik'], $intId)) {
            Flash::set('error', 'NIK sudah terdaftar.');
            redirect("/pelanggan/{$id}/edit");
        }

        $this->pelanggan->update($intId, $data);
        ActivityLogger::log('UPDATE', 'pelanggan', 'Memperbarui data pelanggan ' . trim($data['nama']));

        Flash::set('success', 'Pelanggan berhasil diperbarui.');
        redirect('/pelanggan');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->pelanggan->hasActiveRentals($intId)) {
            Flash::set('error', 'Pelanggan tidak bisa dihapus karena sewa masih aktif.');
            redirect('/pelanggan');
        }

        if ($this->pelanggan->hasRentals($intId)) {
            Flash::set('error', 'Pelanggan tidak bisa dihapus karena ada riwayat sewa.');
            redirect('/pelanggan');
        }

        $customer = $this->pelanggan->findById($intId);
        try {
            $this->pelanggan->delete($intId);
            ActivityLogger::log('HAPUS', 'pelanggan', 'Menghapus data pelanggan ' . ($customer['nama'] ?? "ID {$id}"));
            Flash::set('success', 'Pelanggan berhasil dihapus.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal menghapus pelanggan: data masih terhubung.');
        }
        redirect('/pelanggan');
    }
}
