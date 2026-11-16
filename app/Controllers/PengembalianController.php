<?php
namespace App\Controllers;

use App\Services\PenyewaanService;
use Controller;
use Database;
use Flash;
use Request;

class PengembalianController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $pdo = Database::connection();
        $rows = $pdo->query("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status = 'Berjalan'
            ORDER BY p.tgl_kembali_rencana ASC
        ")->fetchAll();

        $multiplier = (new PenyewaanService())->getDendaMultiplier();

        $this->view('pengembalian/index', [
            'title'      => 'Pengembalian Kendaraan — Rental Armada Bengkulu',
            'rows'       => $rows,
            'multiplier' => $multiplier,
        ]);
    }

    public function proses(Request $request, string $id): void
    {
        $this->requireAuth();
        $this->verifyCsrf($request);
        try {
            $tgl = (string)$request->input('tgl_kembali_aktual', date('Y-m-d'));
            $catatan = (string)$request->input('catatan', '');
            $res = (new PenyewaanService())->prosesPengembalian((int)$id, $tgl, $catatan);
            $msg = 'Pengembalian unit selesai diproses.';
            if ($res['hari_terlambat'] > 0) {
                $msg .= " Terlambat {$res['hari_terlambat']} hari (Denda: " . rupiah($res['denda']) . ').';
            }
            Flash::set('success', $msg);
        } catch (\Throwable $e) {
            Flash::set('error', $e->getMessage());
        }
        redirect('/pengembalian');
    }
}
