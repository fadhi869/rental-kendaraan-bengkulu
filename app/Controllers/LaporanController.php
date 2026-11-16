<?php
namespace App\Controllers;

use App\Services\ActivityLogger;
use App\Services\LaporanService;
use Controller;
use Request;

class LaporanController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $dari = $request->input('dari') ?: date('Y-m-01');
        $sampai = $request->input('sampai') ?: date('Y-m-t');
        $report = (new LaporanService())->getReport($dari, $sampai);
        $report['title'] = 'Laporan Pendapatan — Rental Armada Bengkulu';
        $this->view('laporan/index', $report);
    }

    public function export(Request $request): void
    {
        $this->requireAuth();
        $dari = $request->input('dari') ?: date('Y-m-01');
        $sampai = $request->input('sampai') ?: date('Y-m-t');
        $report = (new LaporanService())->getReport($dari, $sampai);

        ActivityLogger::log('EXPORT_LAPORAN', 'laporan', "Mengekspor laporan pendapatan periode {$dari} s/d {$sampai} ke CSV.");

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename="laporan-pendapatan-{$dari}-sd-{$sampai}.csv"");

        $out = fopen('php://output', 'w');
        fputcsv($out, ['Kode Transaksi', 'Tanggal Sewa', 'Tanggal Kembali', 'Pelanggan', 'Kendaraan', 'Plat Nomor', 'Hari', 'Biaya Sewa', 'Denda', 'Total', 'Status']);
        foreach ($report['rows'] as $r) {
            fputcsv($out, [
                $r['kode_transaksi'],
                $r['tgl_sewa'],
                $r['tgl_kembali_aktual'] ?: $r['tgl_kembali_rencana'],
                $r['nama_pelanggan'],
                $r['merek'] . ' ' . $r['model'],
                $r['plat_nomor'],
                $r['jumlah_hari'],
                $r['total_biaya'],
                $r['denda'],
                (float)$r['total_biaya'] + (float)$r['denda'],
                $r['status'],
            ]);
        }
        fclose($out);
        exit;
    }
}
