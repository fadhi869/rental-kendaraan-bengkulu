<?php
declare(strict_types=1);

/**
 * Controller Kendaraan (Armada Rental)
 */
class KendaraanController extends Controller
{
    private Kendaraan $kendaraan;
    private JenisKendaraan $jenisKendaraan;

    public function __construct()
    {
        $this->kendaraan = new Kendaraan();
        $this->jenisKendaraan = new JenisKendaraan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $q = trim((string)$request->input('q', ''));
        $status = trim((string)$request->input('status', ''));
        $action = (string)$request->input('action', '');
        $id = (int)$request->input('id', 0);

        if ($action === 'create') {
            $this->create($request);
            return;
        }

        if ($action === 'edit' && $id > 0) {
            $this->edit($request, (string)$id);
            return;
        }

        $rows = $this->kendaraan->getFiltered($q, $status);
        $jenisList = $this->jenisKendaraan->getAll();
        $stats = $this->kendaraan->getFleetStats();

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/kendaraan.php';
        $content = ob_get_clean();

        render_layout('Data Kendaraan & Armada', 'kendaraan', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $jenisList = $this->jenisKendaraan->getAll();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/kendaraan.php';
        $content = ob_get_clean();

        render_layout('Tambah Kendaraan Baru', 'kendaraan', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();

        $errors = Validator::validate($data, [
            'plat_nomor'     => 'required',
            'merek'          => 'required',
            'model'          => 'required',
            'tahun'          => 'required|numeric',
            'warna'          => 'required',
            'tarif_per_hari' => 'required|numeric',
            'jenis_id'       => 'required|numeric',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('kendaraan.php?action=create');
        }

        if ($this->kendaraan->isPlateTaken($data['plat_nomor'])) {
            Flash::set('error', 'Nomor plat sudah terdaftar.');
            redirect('kendaraan.php?action=create');
        }

        $this->kendaraan->create($data);
        (new Pengguna())->logActivity('TAMBAH', 'kendaraan', 'Menambah armada: ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Kendaraan berhasil disimpan.');
        redirect('kendaraan.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->kendaraan->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Kendaraan tidak ditemukan.');
            redirect('kendaraan.php');
        }
        $jenisList = $this->jenisKendaraan->getAll();

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/kendaraan.php';
        $content = ob_get_clean();

        render_layout('Edit Kendaraan — ' . $item['plat_nomor'], 'kendaraan', $content);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        $errors = Validator::validate($data, [
            'plat_nomor'     => 'required',
            'merek'          => 'required',
            'model'          => 'required',
            'tahun'          => 'required|numeric',
            'warna'          => 'required',
            'tarif_per_hari' => 'required|numeric',
            'jenis_id'       => 'required|numeric',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect("kendaraan.php?action=edit&id={$id}");
        }

        if ($this->kendaraan->isPlateTaken($data['plat_nomor'], $intId)) {
            Flash::set('error', 'Nomor plat sudah terdaftar.');
            redirect("kendaraan.php?action=edit&id={$id}");
        }

        $this->kendaraan->update($intId, $data);
        (new Pengguna())->logActivity('UPDATE', 'kendaraan', 'Memperbarui unit ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Data armada berhasil diperbarui.');
        redirect('kendaraan.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->kendaraan->hasActiveRentals($intId)) {
            Flash::set('error', 'Kendaraan sedang disewa, tidak dapat dihapus.');
            redirect('kendaraan.php');
        }

        if ($this->kendaraan->hasRentals($intId)) {
            Flash::set('error', 'Kendaraan memiliki riwayat transaksi, tidak dapat dihapus.');
            redirect('kendaraan.php');
        }

        $this->kendaraan->delete($intId);
        (new Pengguna())->logActivity('HAPUS', 'kendaraan', "Menghapus unit ID: {$id}");
        Flash::set('success', 'Kendaraan berhasil dihapus.');
        redirect('kendaraan.php');
    }
}

// Backward Compatibility Aliases
class BukuController extends KendaraanController {}
if (!class_exists('App\Controllers\KendaraanController', false)) class_alias('KendaraanController', 'App\Controllers\KendaraanController');
if (!class_exists('App\Controllers\BukuController', false)) class_alias('KendaraanController', 'App\Controllers\BukuController');
