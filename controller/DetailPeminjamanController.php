<?php
declare(strict_types=1);

/**
 * Controller DetailPeminjaman / Pengembalian & Denda
 */
class DetailPeminjamanController extends Controller
{
    private DetailPeminjaman $detailPeminjaman;

    public function __construct()
    {
        $this->detailPeminjaman = new DetailPeminjaman();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $returnsList = $this->detailPeminjaman->getReturnsList();

        ob_start();
        require BASE_PATH . '/view/detail_peminjaman.php';
        $content = ob_get_clean();

        render_layout('Pengembalian Unit & Hitung Denda', 'detail_peminjaman', $content);
    }

    public function proses(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        $tglAktual = (string)$request->input('tgl_kembali_aktual', date('Y-m-d'));
        $catatan = (string)$request->input('catatan', '');

        try {
            $res = $this->detailPeminjaman->prosesPengembalian((int)$id, $tglAktual, $catatan);
            (new Admin())->logActivity('KEMBALIKAN', 'detail_peminjaman', "Memproses pengembalian ID: {$id} denda: " . rupiah($res['denda']));
            Flash::set('success', 'Pengembalian unit berhasil dicatat. Denda keterlambatan: ' . rupiah($res['denda']));
        } catch (\Throwable $e) {
            Flash::set('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
        redirect('detail_peminjaman.php');
    }
}

// Backward Compatibility Aliases
class PengembalianController extends DetailPeminjamanController {}
if (!class_exists('App\Controllers\PengembalianController', false)) class_alias('DetailPeminjamanController', 'App\Controllers\PengembalianController');
if (!class_exists('App\Controllers\DetailPeminjamanController', false)) class_alias('DetailPeminjamanController', 'App\Controllers\DetailPeminjamanController');
