<?php
namespace App\Controllers;

use App\Models\Penyewaan;
use App\Services\PenyewaanService;
use Controller;
use Flash;
use Request;

class PengembalianController extends Controller
{
    private Penyewaan $penyewaan;
    private PenyewaanService $service;

    public function __construct()
    {
        $this->penyewaan = new Penyewaan();
        $this->service   = new PenyewaanService();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $rows = $this->penyewaan->getReturnsList();
        $multiplier = $this->service->getDendaMultiplier();

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
            $res = $this->service->prosesPengembalian((int)$id, $tgl, $catatan);

            $msg = 'Pengembalian kendaraan selesai diproses.';
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
