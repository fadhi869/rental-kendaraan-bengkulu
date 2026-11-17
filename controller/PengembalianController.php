<?php
declare(strict_types=1);

/**
 * Controller Pengembalian (Pengembalian Kendaraan & Hitung Denda)
 */
class PengembalianController extends Controller
{
    private Pengembalian $pengembalian;

    public function __construct()
    {
        $this->pengembalian = new Pengembalian();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $returnsList = $this->pengembalian->getReturnsList();

        ob_start();
        require BASE_PATH . '/view/pengembalian.php';
        $content = ob_get_clean();

        render_layout('Pengembalian Kendaraan & Denda', 'pengembalian', $content);
    }

    public function proses(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $tglAktual = (string)$request->input('tgl_kembali_aktual', date('Y-m-d'));
        $catatan = (string)$request->input('catatan', '');

        try {
            $res = $this->pengembalian->prosesPengembalian((int)$id, $tglAktual, $catatan);
            (new Pengguna())->logActivity('KEMBALIKAN', 'pengembalian', "Memproses pengembalian ID: {$id} denda: " . rupiah($res['denda']));
            Flash::set('success', 'Pengembalian unit berhasil dicatat. Denda keterlambatan: ' . rupiah($res['denda']));
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
        redirect('pengembalian.php');
    }
}

// Backward Compatibility Aliases
class DetailPeminjamanController extends PengembalianController {}
if (!class_exists('App\Controllers\PengembalianController', false)) class_alias('PengembalianController', 'App\Controllers\PengembalianController');
if (!class_exists('App\Controllers\DetailPeminjamanController', false)) class_alias('PengembalianController', 'App\Controllers\DetailPeminjamanController');
