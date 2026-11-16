<?php
namespace App\Controllers;

use App\Models\Penyewaan;
use Controller;
use Request;

class RiwayatController extends Controller
{
    private Penyewaan $penyewaan;

    public function __construct()
    {
        $this->penyewaan = new Penyewaan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $status = trim((string)$request->input('status', ''));
        $q = trim((string)$request->input('q', ''));
        $rows = $this->penyewaan->getHistory($status, $q);

        $this->view('riwayat/index', [
            'title'  => 'Riwayat Penyewaan — Rental Armada Bengkulu',
            'rows'   => $rows,
            'status' => $status,
            'q'      => $q,
        ]);
    }
}
