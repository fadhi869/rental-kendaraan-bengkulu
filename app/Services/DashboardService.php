<?php
namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Penyewaan;

class DashboardService
{
    public function getSummary(): array
    {
        $kendaraanModel = new Kendaraan();
        $pelangganModel = new Pelanggan();
        $penyewaanModel = new Penyewaan();
        $activityModel  = new ActivityLog();

        $fleetStats = $kendaraanModel->getFleetStats();
        $totalPelanggan = $pelangganModel->countAll();
        $rentalStats = $penyewaanModel->getSummaryStats();
        $chartData = $penyewaanModel->getDailyRevenueChart(10);
        $activeRentals = $penyewaanModel->getActiveAndReserved(8);
        $recentLogs = $activityModel->getLatest(6);

        return [
            'stats' => [
                'total_kendaraan'     => $fleetStats['total_kendaraan'],
                'kendaraan_tersedia'  => $fleetStats['kendaraan_tersedia'],
                'kendaraan_disewa'    => $fleetStats['kendaraan_disewa'],
                'kendaraan_perawatan' => $fleetStats['kendaraan_perawatan'],
                'total_pelanggan'     => $totalPelanggan,
                'total_pendapatan'    => $rentalStats['total_pendapatan'],
                'sewa_berjalan'       => $rentalStats['sewa_berjalan'],
                'sewa_dipesan'        => $rentalStats['sewa_dipesan'],
                'sewa_selesai'        => $rentalStats['sewa_selesai'],
            ],
            'chartLabels'        => $chartData['labels'],
            'chartValues'        => $chartData['values'],
            'transaksiBerjalan'  => $activeRentals,
            'aktivitasTerbaru'   => $recentLogs,
        ];
    }
}
