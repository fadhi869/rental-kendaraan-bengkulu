<?php
declare(strict_types=1);

/**
 * Controller Admin / Manajemen Pengguna & Dashboard Sistem
 */
class AdminController extends Controller
{
    private Admin $admin;

    public function __construct()
    {
        $this->admin = new Admin();
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

        $users = $this->admin->getAll($q);
        $logs = $this->admin->getLatestLogs(50, $q);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/admin.php';
        $content = ob_get_clean();

        render_layout('Administrasi & Pengguna', 'admin', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAdmin();
        $item = [];

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/admin.php';
        $content = ob_get_clean();

        render_layout('Tambah Pengguna Baru', 'admin', $content);
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
            redirect('admin.php?action=create');
        }

        if ($this->admin->isUsernameTaken($data['username'])) {
            Flash::set('error', 'Username sudah digunakan.');
            redirect('admin.php?action=create');
        }

        $this->admin->create($data);
        $this->admin->logActivity('TAMBAH_USER', 'admin', 'Menambah akun: ' . trim($data['username']));
        Flash::set('success', 'Akun pengguna berhasil dibuat.');
        redirect('admin.php');
    }

    public function edit(Request $request, string $id): void
    {
        $this->requireAdmin();
        $item = $this->admin->findById((int)$id);
        if (!$item) {
            Flash::set('error', 'Pengguna tidak ditemukan.');
            redirect('admin.php');
        }

        ob_start();
        $viewMode = 'edit';
        require BASE_PATH . '/view/admin.php';
        $content = ob_get_clean();

        render_layout('Edit Pengguna — ' . $item['nama'], 'admin', $content);
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
            redirect("admin.php?action=edit&id={$id}");
        }

        if ($this->admin->isUsernameTaken($data['username'], $intId)) {
            Flash::set('error', 'Username sudah digunakan pengguna lain.');
            redirect("admin.php?action=edit&id={$id}");
        }

        $this->admin->update($intId, $data);
        $this->admin->logActivity('UPDATE_USER', 'admin', 'Memperbarui pengguna ID ' . $id);
        Flash::set('success', 'Data akun berhasil diperbarui.');
        redirect('admin.php');
    }

    public function toggleStatus(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        if ($intId === Auth::id()) {
            Flash::set('error', 'Tidak dapat mengubah status akun sendiri.');
            redirect('admin.php');
        }
        $st = $this->admin->toggleStatus($intId);
        $this->admin->logActivity('STATUS_USER', 'admin', "Mengubah status user ID {$id} ke {$st}");
        Flash::set('success', "Status akun berhasil diubah menjadi {$st}.");
        redirect('admin.php');
    }

    public function destroy(Request $request, string $id): void
    {
        $this->requireAdmin();
        $this->verifyCsrf($request);
        $intId = (int)$id;
        if ($intId === Auth::id()) {
            Flash::set('error', 'Tidak dapat menghapus akun sendiri yang sedang aktif.');
            redirect('admin.php');
        }
        $this->admin->softDelete($intId);
        $this->admin->logActivity('HAPUS_USER', 'admin', "Menghapus akun ID {$id}");
        Flash::set('success', 'Pengguna berhasil dinonaktifkan.');
        redirect('admin.php');
    }

    public function dashboard(Request $request): void
    {
        $this->requireAuth();
        $fleet = (new Buku())->getFleetStats();
        $totPelanggan = (new Anggota())->countAll();
        $rentalStats = (new Peminjaman())->getSummaryStats();
        $chartData = (new Peminjaman())->getDailyRevenueChart(10);
        $activeRentals = (new Peminjaman())->getActiveAndReserved(8);
        $recentLogs = (new Admin())->getLatestLogs(6);

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
        require BASE_PATH . '/view/admin.php'; // or dashboard rendered via index.php
        $content = ob_get_clean();

        render_layout('Ringkasan Operasional', 'dashboard', $content);
    }
}

// Backward Compatibility Aliases
class UserController extends AdminController {}
class DashboardController extends AdminController {}
class ActivityController extends AdminController {}
if (!class_exists('App\Controllers\UserController', false)) class_alias('AdminController', 'App\Controllers\UserController');
if (!class_exists('App\Controllers\DashboardController', false)) class_alias('AdminController', 'App\Controllers\DashboardController');
if (!class_exists('App\Controllers\ActivityController', false)) class_alias('AdminController', 'App\Controllers\ActivityController');
