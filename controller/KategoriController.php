<?php
declare(strict_types=1);

/**
 * Controller Kategori / Jenis Kendaraan
 */
class KategoriController extends Controller
{
    private Kategori $kategori;

    public function __construct()
    {
        $this->kategori = new Kategori();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
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

        $rows = $this->kategori->getAllWithUnitCount();

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/kategori.php';
        $content = ob_get_clean();

        render_layout('Data Kategori & Jenis Kendaraan', 'kategori', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/kategori.php';
        $content = ob_get_clean();

        render_layout('Tambah Kategori Baru', 'kategori', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();

        if (empty(trim((string)$data['nama_jenis']))) {
            Flash::set('error', 'Nama kategori wajib diisi.');
            redirect('kategori.php?action=create');
        }

        $this->kategori->create($data);
        (new Admin())->logActivity('TAMBAH', 'kategori', 'Menambah kategori: ' . trim($data['nama_jenis']));
        Flash::set('success', 'Kategori berhasil disimpan.');
        redirect('kategori.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->kategori->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Kategori tidak ditemukan.');
            redirect('kategori.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/kategori.php';
        $content = ob_get_clean();

        render_layout('Edit Kategori — ' . $item['nama_jenis'], 'kategori', $content);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        if (empty(trim((string)$data['nama_jenis']))) {
            Flash::set('error', 'Nama kategori wajib diisi.');
            redirect("kategori.php?action=edit&id={$id}");
        }

        $this->kategori->update($intId, $data);
        (new Admin())->logActivity('UPDATE', 'kategori', 'Memperbarui kategori ' . trim($data['nama_jenis']));
        Flash::set('success', 'Kategori berhasil diperbarui.');
        redirect('kategori.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->kategori->hasUnits($intId)) {
            Flash::set('error', 'Kategori tidak dapat dihapus karena masih digunakan pada unit kendaraan.');
            redirect('kategori.php');
        }

        $this->kategori->delete($intId);
        (new Admin())->logActivity('HAPUS', 'kategori', "Menghapus kategori ID: {$id}");
        Flash::set('success', 'Kategori berhasil dihapus.');
        redirect('kategori.php');
    }
}

// Backward Compatibility Aliases
class JenisKendaraanController extends KategoriController {}
if (!class_exists('App\Controllers\JenisKendaraanController', false)) class_alias('KategoriController', 'App\Controllers\JenisKendaraanController');
if (!class_exists('App\Controllers\KategoriController', false)) class_alias('KategoriController', 'App\Controllers\KategoriController');
