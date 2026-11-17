<?php
declare(strict_types=1);

/**
 * Controller Pelanggan (Penyewa Kendaraan)
 */
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

        $rows = $this->pelanggan->getFiltered($q);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/pelanggan.php';
        $content = ob_get_clean();

        render_layout('Data Pelanggan', 'pelanggan', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/pelanggan.php';
        $content = ob_get_clean();

        render_layout('Tambah Pelanggan Baru', 'pelanggan', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();

        $errors = Validator::validate($data, [
            'nik'    => 'required',
            'nama'   => 'required',
            'alamat' => 'required',
            'no_hp'  => 'required',
            'no_sim' => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('pelanggan.php?action=create');
        }

        if ($this->pelanggan->isNikTaken($data['nik'])) {
            Flash::set('error', 'NIK sudah terdaftar dalam sistem.');
            redirect('pelanggan.php?action=create');
        }

        $this->pelanggan->create($data);
        (new Pengguna())->logActivity('TAMBAH', 'pelanggan', 'Menambah pelanggan: ' . trim($data['nama']));
        Flash::set('success', 'Data pelanggan berhasil disimpan.');
        redirect('pelanggan.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->pelanggan->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Pelanggan tidak ditemukan.');
            redirect('pelanggan.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/pelanggan.php';
        $content = ob_get_clean();

        render_layout('Edit Pelanggan — ' . $item['nama'], 'pelanggan', $content);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        $errors = Validator::validate($data, [
            'nik'    => 'required',
            'nama'   => 'required',
            'alamat' => 'required',
            'no_hp'  => 'required',
            'no_sim' => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect("pelanggan.php?action=edit&id={$id}");
        }

        if ($this->pelanggan->isNikTaken($data['nik'], $intId)) {
            Flash::set('error', 'NIK sudah terdaftar pada pelanggan lain.');
            redirect("pelanggan.php?action=edit&id={$id}");
        }

        $this->pelanggan->update($intId, $data);
        (new Pengguna())->logActivity('UPDATE', 'pelanggan', 'Memperbarui data pelanggan ' . trim($data['nama']));
        Flash::set('success', 'Data pelanggan berhasil diperbarui.');
        redirect('pelanggan.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->pelanggan->hasActiveRentals($intId)) {
            Flash::set('error', 'Pelanggan masih memiliki transaksi sewa aktif.');
            redirect('pelanggan.php');
        }

        if ($this->pelanggan->hasRentals($intId)) {
            Flash::set('error', 'Pelanggan memiliki riwayat penyewaan, tidak dapat dihapus.');
            redirect('pelanggan.php');
        }

        $this->pelanggan->delete($intId);
        (new Pengguna())->logActivity('HAPUS', 'pelanggan', "Menghapus pelanggan ID: {$id}");
        Flash::set('success', 'Pelanggan berhasil dihapus.');
        redirect('pelanggan.php');
    }
}

// Backward Compatibility Aliases
class AnggotaController extends PelangganController {}
if (!class_exists('App\Controllers\PelangganController', false)) class_alias('PelangganController', 'App\Controllers\PelangganController');
if (!class_exists('App\Controllers\AnggotaController', false)) class_alias('PelangganController', 'App\Controllers\AnggotaController');
