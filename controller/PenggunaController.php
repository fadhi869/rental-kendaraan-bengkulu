<?php
declare(strict_types=1);

/**
 * Controller Pengguna (Manajemen Akun Pengguna & Log Aktivitas Sistem)
 */
class PenggunaController extends Controller
{
    private Pengguna $pengguna;

    public function __construct()
    {
        $this->pengguna = new Pengguna();
    }

    public function index(Request $request): void
    {
        $this->requireAdmin();
        $q = trim((string)$request->input('q', ''));
        $tab = (string)$request->input('tab', 'users');
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

        $users = $this->pengguna->getAll($q);
        $logs = $this->pengguna->getLatestLogs(50, $q);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/pengguna.php';
        $content = ob_get_clean();

        render_layout('Administrasi & Pengguna', 'pengguna', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAdmin();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/pengguna.php';
        $content = ob_get_clean();

        render_layout('Tambah Pengguna Baru', 'pengguna', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $data = $request->all();

        $errors = Validator::validate($data, [
            'username' => 'required',
            'nama'     => 'required',
            'password' => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('pengguna.php?action=create');
        }

        if ($this->pengguna->isUsernameTaken($data['username'])) {
            Flash::set('error', 'Username sudah digunakan.');
            redirect('pengguna.php?action=create');
        }

        $this->pengguna->create($data);
        $this->pengguna->logActivity('TAMBAH_USER', 'pengguna', 'Menambah akun: ' . trim($data['username']));
        Flash::set('success', 'Akun pengguna berhasil dibuat.');
        redirect('pengguna.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAdmin();
        $item = $this->pengguna->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Pengguna tidak ditemukan.');
            redirect('pengguna.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/pengguna.php';
        $content = ob_get_clean();

        render_layout('Edit Pengguna — ' . $item['nama'], 'pengguna', $content);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        $data = $request->all();

        $errors = Validator::validate($data, [
            'username' => 'required',
            'nama'     => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect("pengguna.php?action=edit&id={$id}");
        }

        if ($this->pengguna->isUsernameTaken($data['username'], $intId)) {
            Flash::set('error', 'Username sudah digunakan pengguna lain.');
            redirect("pengguna.php?action=edit&id={$id}");
        }

        $this->pengguna->update($intId, $data);
        $this->pengguna->logActivity('UPDATE_USER', 'pengguna', 'Memperbarui pengguna ID ' . $id);
        Flash::set('success', 'Data akun berhasil diperbarui.');
        redirect('pengguna.php');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        if ($intId === Auth::id()) {
            Flash::set('error', 'Tidak dapat mengubah status akun sendiri.');
            redirect('pengguna.php');
        }
        $st = $this->pengguna->toggleStatus($intId);
        $this->pengguna->logActivity('STATUS_USER', 'pengguna', "Mengubah status user ID {$id} ke {$st}");
        Flash::set('success', "Status akun berhasil diubah menjadi {$st}.");
        redirect('pengguna.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        if ($intId === Auth::id()) {
            Flash::set('error', 'Tidak dapat menghapus akun sendiri yang sedang aktif.');
            redirect('pengguna.php');
        }
        $this->pengguna->softDelete($intId);
        $this->pengguna->logActivity('HAPUS_USER', 'pengguna', "Menghapus akun ID {$id}");
        Flash::set('success', 'Pengguna berhasil dinonaktifkan.');
        redirect('pengguna.php');
    }

    public function dashboard(Request $request): void
    {
        $this->requireAuth();
        $fleet = (new Kendaraan())->getFleetStats();
        $totPelanggan = (new Pelanggan())->countAll();
        $rentalStats = (new Penyewaan())->getSummaryStats();
        $chartData = (new Penyewaan())->getDailyRevenueChart(10);
        $activeRentals = (new Penyewaan())->getActiveAndReserved(8);
        $recentLogs = (new Pengguna())->getLatestLogs(6);

        $stats = [
            'total_kendaraan'     => $fleet['total_kendaraan'],
            'kendaraan_tersedia'  => $fleet['kendaraan_tersedia'],
            'kendaraan_disewa'    => $fleet['kendaraan_disewa'],
            'kendaraan_perawatan' => $fleet['kendaraan_perawatan'],
            'total_pelanggan'     => $totPelanggan,
            'total_pendapatan'    => $rentalStats['total_pendapatan'],
            'sewa_berjalan'       => $rentalStats['sewa_berjalan'],
            'sewa_dipesan'        => $rentalStats['sewa_dipesan'],
            'sewa_selesai'        => $rentalStats['sewa_selesai'],
        ];

        ob_start();
        require BASE_PATH . '/view/pengguna.php';
        $content = ob_get_clean();

        render_layout('Ringkasan Operasional', 'dashboard', $content);
    }
}

// Backward Compatibility Aliases
class AdminController extends PenggunaController {}
class UserController extends PenggunaController {}
class DashboardController extends PenggunaController {}
class ActivityController extends PenggunaController {}
if (!class_exists('App\Controllers\PenggunaController', false)) class_alias('PenggunaController', 'App\Controllers\PenggunaController');
if (!class_exists('App\Controllers\AdminController', false)) class_alias('PenggunaController', 'App\Controllers\AdminController');
if (!class_exists('App\Controllers\UserController', false)) class_alias('PenggunaController', 'App\Controllers\UserController');
if (!class_exists('App\Controllers\DashboardController', false)) class_alias('PenggunaController', 'App\Controllers\DashboardController');
if (!class_exists('App\Controllers\ActivityController', false)) class_alias('PenggunaController', 'App\Controllers\ActivityController');
