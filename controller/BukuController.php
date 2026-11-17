<?php
declare(strict_types=1);

/**
 * Controller Buku / Kendaraan
 */
class BukuController extends Controller
{
    private Buku $buku;
    private Kategori $kategori;

    public function __construct()
    {
        $this->buku = new Buku();
        $this->kategori = new Kategori();
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

        $rows = $this->buku->getFiltered($q, $status);
        $jenisList = $this->kategori->getAll();
        $stats = $this->buku->getFleetStats();

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/buku.php';
        $content = ob_get_clean();

        render_layout('Data Kendaraan & Armada', 'buku', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $jenisList = $this->kategori->getAll();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/buku.php';
        $content = ob_get_clean();

        render_layout('Tambah Kendaraan Baru', 'buku', $content);
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
            redirect('buku.php?action=create');
        }

        if ($this->buku->isPlateTaken($data['plat_nomor'])) {
            Flash::set('error', 'Nomor plat sudah terdaftar.');
            redirect('buku.php?action=create');
        }

        $this->buku->create($data);
        (new Admin())->logActivity('TAMBAH', 'buku', 'Menambah armada: ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Kendaraan berhasil disimpan.');
        redirect('buku.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->buku->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Kendaraan tidak ditemukan.');
            redirect('buku.php');
        }
        $jenisList = $this->kategori->getAll();

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/buku.php';
        $content = ob_get_clean();

        render_layout('Edit Kendaraan — ' . $item['plat_nomor'], 'buku', $content);
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
            redirect("buku.php?action=edit&id={$id}");
        }

        if ($this->buku->isPlateTaken($data['plat_nomor'], $intId)) {
            Flash::set('error', 'Nomor plat sudah terdaftar.');
            redirect("buku.php?action=edit&id={$id}");
        }

        $this->buku->update($intId, $data);
        (new Admin())->logActivity('UPDATE', 'buku', 'Memperbarui unit ' . strtoupper(trim($data['plat_nomor'])));
        Flash::set('success', 'Data armada berhasil diperbarui.');
        redirect('buku.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->buku->hasActiveRentals($intId)) {
            Flash::set('error', 'Kendaraan sedang disewa, tidak dapat dihapus.');
            redirect('buku.php');
        }

        if ($this->buku->hasRentals($intId)) {
            Flash::set('error', 'Kendaraan memiliki riwayat transaksi, tidak dapat dihapus.');
            redirect('buku.php');
        }

        $this->buku->delete($intId);
        (new Admin())->logActivity('HAPUS', 'buku', "Menghapus unit ID: {$id}");
        Flash::set('success', 'Kendaraan berhasil dihapus.');
        redirect('buku.php');
    }
}

// Backward Compatibility Aliases
class KendaraanController extends BukuController {}
if (!class_exists('App\Controllers\KendaraanController', false)) class_alias('BukuController', 'App\Controllers\KendaraanController');
if (!class_exists('App\Controllers\BukuController', false)) class_alias('BukuController', 'App\Controllers\BukuController');
