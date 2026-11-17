<?php
namespace App\Controllers;

use App\Models\Laporan;
use App\Services\ActivityLogger;
use Controller;
use Request;

class LaporanController extends Controller
{
    private Laporan $laporan;

    public function __construct()
    {
        $this->laporan = new Laporan();
    }

    public function index(Request $request): void
    {
        $this->requireAuth();
        $dari = $request->input('dari') ?: date('Y-m-01');
        $sampai = $request->input('sampai') ?: date('Y-m-t');

        $report = $this->laporan->getRevenueReport($dari, $sampai);
        $report['title'] = 'Laporan Pendapatan — Rental Armada Bengkulu';

        $this->view('laporan/index', $report);
    }

    public function export(Request $request): void
    {
        $this->requireAuth();
        $dari = $request->input('dari') ?: date('Y-m-01');
        $sampai = $request->input('sampai') ?: date('Y-m-t');

        $report = $this->laporan->getRevenueReport($dari, $sampai);
        ActivityLogger::log('EXPORT_LAPORAN', 'laporan', "Ekspor laporan pendapatan periode {$dari} s/d {$sampai} ke CSV.");

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan-pendapatan-' . $dari . '-sd-' . $sampai . '.csv"');

        $out = fopen('php://output', 'w');
        fputcsv($out, [
            'Kode Transaksi',
            'Tanggal Mulai Sewa',
            'Tanggal Pengembalian',
            'Nama Pelanggan',
            'Kendaraan',
            'Plat Nomor',
            'Jumlah Hari',
            'Biaya Sewa Pokok (Rp)',
            'Denda Keterlambatan (Rp)',
            'Total Pembayaran (Rp)',
            'Status Transaksi',
        ]);

        foreach ($report['rows'] as $r) {
            $tglKembali = $r['tgl_kembali_aktual'] ?: $r['tgl_kembali_rencana'];
            fputcsv($out, [
                $r['kode_transaksi'],
                $r['tgl_sewa'],
                $tglKembali,
                $r['nama_pelanggan'],
                $r['merek'] . ' ' . $r['model'],
                $r['plat_nomor'],
                $r['jumlah_hari'],
                (float)$r['total_biaya'],
                (float)$r['denda'],
                (float)$r['total_biaya'] + (float)$r['denda'],
                $r['status'],
            ]);
        }

        fclose($out);
        exit;
    }
}
