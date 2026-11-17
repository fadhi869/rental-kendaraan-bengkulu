<?php
declare(strict_types=1);

/**
 * Controller Anggota / Pelanggan
 */
class AnggotaController extends Controller
{
    private Anggota $anggota;

    public function __construct()
    {
        $this->anggota = new Anggota();
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

        $rows = $this->anggota->getFiltered($q);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/anggota.php';
        $content = ob_get_clean();

        render_layout('Data Anggota & Pelanggan', 'anggota', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/anggota.php';
        $content = ob_get_clean();

        render_layout('Tambah Anggota Baru', 'anggota', $content);
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
            redirect('anggota.php?action=create');
        }

        if ($this->anggota->isNikTaken($data['nik'])) {
            Flash::set('error', 'NIK sudah terdaftar dalam sistem.');
            redirect('anggota.php?action=create');
        }

        $this->anggota->create($data);
        (new Admin())->logActivity('TAMBAH', 'anggota', 'Menambah anggota: ' . trim($data['nama']));
        Flash::set('success', 'Data anggota berhasil disimpan.');
        redirect('anggota.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->anggota->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Anggota tidak ditemukan.');
            redirect('anggota.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/anggota.php';
        $content = ob_get_clean();

        render_layout('Edit Anggota — ' . $item['nama'], 'anggota', $content);
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
            redirect("anggota.php?action=edit&id={$id}");
        }

        if ($this->anggota->isNikTaken($data['nik'], $intId)) {
            Flash::set('error', 'NIK sudah terdaftar pada pengguna lain.');
            redirect("anggota.php?action=edit&id={$id}");
        }

        $this->anggota->update($intId, $data);
        (new Admin())->logActivity('UPDATE', 'anggota', 'Memperbarui data anggota ' . trim($data['nama']));
        Flash::set('success', 'Data anggota berhasil diperbarui.');
        redirect('anggota.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $intId = (int)$id;

        if ($this->anggota->hasActiveRentals($intId)) {
            Flash::set('error', 'Anggota masih memiliki peminjaman aktif.');
            redirect('anggota.php');
        }

        if ($this->anggota->hasRentals($intId)) {
            Flash::set('error', 'Anggota memiliki riwayat peminjaman, tidak dapat dihapus.');
            redirect('anggota.php');
        }

        $this->anggota->delete($intId);
        (new Admin())->logActivity('HAPUS', 'anggota', "Menghapus anggota ID: {$id}");
        Flash::set('success', 'Anggota berhasil dihapus.');
        redirect('anggota.php');
    }
}

// Backward Compatibility Aliases
class PelangganController extends AnggotaController {}
if (!class_exists('App\Controllers\PelangganController', false)) class_alias('AnggotaController', 'App\Controllers\PelangganController');
if (!class_exists('App\Controllers\AnggotaController', false)) class_alias('AnggotaController', 'App\Controllers\AnggotaController');
