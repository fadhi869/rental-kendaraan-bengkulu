<?php
declare(strict_types=1);

/**
 * Controller Penyewaan (Sewa Armada, Laporan & Riwayat)
 */
class PenyewaanController extends Controller
{
    private Penyewaan $penyewaan;
    private Kendaraan $kendaraan;
    private Pelanggan $pelanggan;

    public function __construct()
    {
        $this->penyewaan = new Penyewaan();
        $this->kendaraan = new Kendaraan();
        $this->pelanggan = new Pelanggan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $action = (string)$request->input('action', '');
        $status = trim((string)$request->input('status', ''));
        $id = (int)$request->input('id', 0);

        if ($action === 'create') {
            $this->create($request);
            return;
        }

        if ($action === 'show' && $id > 0) {
            $this->show($request, (string)$id);
            return;
        }

        if ($action === 'laporan') {
            $this->laporan($request);
            return;
        }

        $rows = $this->penyewaan->getFiltered($status);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/penyewaan.php';
        $content = ob_get_clean();

        render_layout('Transaksi Penyewaan', 'penyewaan', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $availableUnits = $this->kendaraan->getAvailable();
        $customers = $this->pelanggan->getAll();
        $kodeTransaksi = $this->penyewaan->generateKodeTransaksi();

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/penyewaan.php';
        $content = ob_get_clean();

        render_layout('Buat Transaksi Penyewaan', 'penyewaan', $content);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $data = $request->all();

        $errors = Validator::validate($data, [
            'pelanggan_id'        => 'required|numeric',
            'kendaraan_id'        => 'required|numeric',
            'tgl_sewa'            => 'required',
            'tgl_kembali_rencana' => 'required',
        ]);

        if ($errors) {
            Flash::set('error', reset($errors));
            redirect('penyewaan.php?action=create');
        }

        try {
            $id = $this->penyewaan->createRental($data);
            (new Pengguna())->logActivity('BUAT_SEWA', 'penyewaan', "Membuat transaksi penyewaan ID: {$id}");
            Flash::set('success', 'Transaksi penyewaan berhasil disimpan.');
            redirect('penyewaan.php');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal memproses transaksi: ' . $e->getMessage());
            redirect('penyewaan.php?action=create');
        }
    }

    public function show(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->penyewaan->findByIdWithDetails((int)$id);
        if (!$item) {
            Flash::set('error', 'Transaksi tidak ditemukan.');
            redirect('penyewaan.php');
        }

        ob_start();
        $viewMode = 'show';
        require BASE_PATH . '/view/penyewaan.php';
        $content = ob_get_clean();

        render_layout('Detail Penyewaan — ' . $item['kode_transaksi'], 'penyewaan', $content);
    }

    public function serahKunci(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $this->penyewaan->serahKunci((int)$id);
            (new Pengguna())->logActivity('SERAH_KUNCI', 'penyewaan', "Serah kunci transaksi ID: {$id}");
            Flash::set('success', 'Serah kunci berhasil diproses. Status kini Berjalan.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal serah kunci: ' . $e->getMessage());
        }
        redirect('penyewaan.php');
    }

    public function batal(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $this->penyewaan->batalkan((int)$id);
            (new Pengguna())->logActivity('BATAL', 'penyewaan', "Membatalkan transaksi ID: {$id}");
            Flash::set('success', 'Transaksi penyewaan berhasil dibatalkan.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
        redirect('penyewaan.php');
    }

    public function laporan(Request $request): void
    {
        $this->requireAuth();
        $dari = (string)$request->input('dari', date('Y-m-01'));
        $sampai = (string)$request->input('sampai', date('Y-m-t'));
        $report = $this->penyewaan->getRevenueReport($dari, $sampai);

        ob_start();
        $viewMode = 'laporan';
        require BASE_PATH . '/view/penyewaan.php';
        $content = ob_get_clean();

        render_layout('Laporan Penyewaan & Pendapatan', 'penyewaan', $content);
    }
}

// Backward Compatibility Aliases
class PeminjamanController extends PenyewaanController {}
class LaporanController extends PenyewaanController {}
class RiwayatController extends PenyewaanController {}
if (!class_exists('App\Controllers\PenyewaanController', false)) class_alias('PenyewaanController', 'App\Controllers\PenyewaanController');
if (!class_exists('App\Controllers\PeminjamanController', false)) class_alias('PenyewaanController', 'App\Controllers\PeminjamanController');
if (!class_exists('App\Controllers\LaporanController', false)) class_alias('PenyewaanController', 'App\Controllers\LaporanController');
if (!class_exists('App\Controllers\RiwayatController', false)) class_alias('PenyewaanController', 'App\Controllers\RiwayatController');
