<?php
namespace App\Controllers;

use App\Services\RiwayatService;
use Controller;
use Request;

class RiwayatController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $status = trim((string)$request->input('status', ''));
        $q = trim((string)$request->input('q', ''));
        $rows = (new RiwayatService())->getHistory($status, $q);

        $this->view('riwayat/index', [
            'title'  => 'Riwayat Penyewaan — Rental Armada Bengkulu',
            'rows'   => $rows,
            'status' => $status,
            'q'      => $q,
        ]);
    }
}
