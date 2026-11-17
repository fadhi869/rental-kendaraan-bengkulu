<?php
declare(strict_types=1);

/**
 * Controller Peminjaman / Penyewaan (Sewa, Laporan & Riwayat)
 */
class PeminjamanController extends Controller
{
    private Peminjaman $peminjaman;
    private Buku $buku;
    private Anggota $anggota;

    public function __construct()
    {
        $this->peminjaman = new Peminjaman();
        $this->buku = new Buku();
        $this->anggota = new Anggota();
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

        $rows = $this->peminjaman->getFiltered($status);

        ob_start();
        $viewMode = 'index';
        require BASE_PATH . '/view/peminjaman.php';
        $content = ob_get_clean();

        render_layout('Transaksi Peminjaman & Sewa', 'peminjaman', $content);
    }

    public function create(Request $request): void
    {
        $this->requireAuth();
        $availableUnits = $this->buku->getAvailable();
        $customers = $this->anggota->getAll();
        $kodeTransaksi = $this->peminjaman->generateKodeTransaksi();

        ob_start();
        $viewMode = 'create';
        require BASE_PATH . '/view/peminjaman.php';
        $content = ob_get_clean();

        render_layout('Buat Transaksi Peminjaman Baru', 'peminjaman', $content);
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
            redirect('peminjaman.php?action=create');
        }

        try {
            $id = $this->peminjaman->createRental($data);
            (new Admin())->logActivity('BUAT_SEWA', 'peminjaman', "Membuat transaksi peminjaman ID: {$id}");
            Flash::set('success', 'Transaksi peminjaman berhasil disimpan.');
            redirect('peminjaman.php');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal memproses transaksi: ' . $e->getMessage());
            redirect('peminjaman.php?action=create');
        }
    }

    public function show(Request $request, string $id): void
    {
        $this->requireAuth();
        $item = $this->peminjaman->findByIdWithDetails((int)$id);
        if (!$item) {
            Flash::set('error', 'Transaksi tidak ditemukan.');
            redirect('peminjaman.php');
        }

        ob_start();
        $viewMode = 'show';
        require BASE_PATH . '/view/peminjaman.php';
        $content = ob_get_clean();

        render_layout('Detail Peminjaman — ' . $item['kode_transaksi'], 'peminjaman', $content);
    }

    public function serahKunci(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $this->peminjaman->serahKunci((int)$id);
            (new Admin())->logActivity('SERAH_KUNCI', 'peminjaman', "Serah kunci transaksi ID: {$id}");
            Flash::set('success', 'Serah kunci berhasil diproses. Status kini Berjalan.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal serah kunci: ' . $e->getMessage());
        }
        redirect('peminjaman.php');
    }

    public function batal(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $this->peminjaman->batalkan((int)$id);
            (new Admin())->logActivity('BATAL', 'peminjaman', "Membatalkan transaksi ID: {$id}");
            Flash::set('success', 'Transaksi peminjaman berhasil dibatalkan.');
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
        redirect('peminjaman.php');
    }

    public function laporan(Request $request): void
    {
        $this->requireAuth();
        $dari = (string)$request->input('dari', date('Y-m-01'));
        $sampai = (string)$request->input('sampai', date('Y-m-t'));
        $report = $this->peminjaman->getRevenueReport($dari, $sampai);

        ob_start();
        $viewMode = 'laporan';
        require BASE_PATH . '/view/peminjaman.php';
        $content = ob_get_clean();

        render_layout('Laporan Peminjaman & Pendapatan', 'peminjaman', $content);
    }
}

// Backward Compatibility Aliases
class PenyewaanController extends PeminjamanController {}
class LaporanController extends PeminjamanController {}
class RiwayatController extends PeminjamanController {}
if (!class_exists('App\Controllers\PenyewaanController', false)) class_alias('PeminjamanController', 'App\Controllers\PenyewaanController');
if (!class_exists('App\Controllers\PeminjamanController', false)) class_alias('PeminjamanController', 'App\Controllers\PeminjamanController');
if (!class_exists('App\Controllers\LaporanController', false)) class_alias('PeminjamanController', 'App\Controllers\LaporanController');
if (!class_exists('App\Controllers\RiwayatController', false)) class_alias('PeminjamanController', 'App\Controllers\RiwayatController');
