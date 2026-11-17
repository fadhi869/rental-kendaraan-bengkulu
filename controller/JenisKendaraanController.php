<?php
declare(strict_types=1);

/**
 * Controller Jenis Kendaraan
 */
class JenisKendaraanController extends Controller
{
    private JenisKendaraan $jenisKendaraan;

    public function __construct()
    {
        $this->jenisKendaraan = new JenisKendaraan();
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

        $rows = $this->jenisKendaraan->getAllWithUnitCount();

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/jenis_kendaraan.php';
        $content = ob_get_clean();

        render_layout('Data Jenis Kendaraan', 'jenis_kendaraan', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/jenis_kendaraan.php';
        $content = ob_get_clean();

        render_layout('Tambah Jenis Kendaraan', 'jenis_kendaraan', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();

        if (empty(trim((string)$data['nama_jenis']))) {
            Flash::set('error', 'Nama jenis kendaraan wajib diisi.');
            redirect('jenis_kendaraan.php?action=create');
        }

        $this->jenisKendaraan->create($data);
        (new Pengguna())->logActivity('TAMBAH', 'jenis_kendaraan', 'Menambah jenis kendaraan: ' . trim($data['nama_jenis']));
        Flash::set('success', 'Jenis kendaraan berhasil disimpan.');
        redirect('jenis_kendaraan.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->jenisKendaraan->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Jenis kendaraan tidak ditemukan.');
            redirect('jenis_kendaraan.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/jenis_kendaraan.php';
        $content = ob_get_clean();

        render_layout('Edit Jenis Kendaraan — ' . $item['nama_jenis'], 'jenis_kendaraan', $content);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        if (empty(trim((string)$data['nama_jenis']))) {
            Flash::set('error', 'Nama jenis kendaraan wajib diisi.');
            redirect("jenis_kendaraan.php?action=edit&id={$id}");
        }

        $this->jenisKendaraan->update($intId, $data);
        (new Pengguna())->logActivity('UPDATE', 'jenis_kendaraan', 'Memperbarui jenis kendaraan ' . trim($data['nama_jenis']));
        Flash::set('success', 'Jenis kendaraan berhasil diperbarui.');
        redirect('jenis_kendaraan.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->jenisKendaraan->hasUnits($intId)) {
            Flash::set('error', 'Jenis kendaraan tidak dapat dihapus karena masih digunakan pada unit armada.');
            redirect('jenis_kendaraan.php');
        }

        $this->jenisKendaraan->delete($intId);
        (new Pengguna())->logActivity('HAPUS', 'jenis_kendaraan', "Menghapus jenis kendaraan ID: {$id}");
        Flash::set('success', 'Jenis kendaraan berhasil dihapus.');
        redirect('jenis_kendaraan.php');
    }
}

// Backward Compatibility Aliases
class KategoriController extends JenisKendaraanController {}
if (!class_exists('App\Controllers\JenisKendaraanController', false)) class_alias('JenisKendaraanController', 'App\Controllers\JenisKendaraanController');
if (!class_exists('App\Controllers\KategoriController', false)) class_alias('JenisKendaraanController', 'App\Controllers\KategoriController');
