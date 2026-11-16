<?php
namespace App\Services;

use Database;

class DashboardService
{
    public function getSummary(): array
    {
        $pdo = Database::connection();

        $fleet = $pdo->query("
            SELECT
                COUNT(*) AS total_kendaraan,
                SUM(status = 'Tersedia') AS kendaraan_tersedia,
                SUM(status = 'Disewa') AS kendaraan_disewa,
                SUM(status = 'Perawatan') AS kendaraan_perawatan
            FROM kendaraan
        ")->fetch();

        $pelanggan = (int)$pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();

        $rev = $pdo->query("
            SELECT
                COALESCE(SUM(total_biaya + denda), 0) AS total_pendapatan,
                SUM(status = 'Berjalan') AS sewa_berjalan,
                SUM(status = 'Dipesan') AS sewa_dipesan,
                SUM(status = 'Selesai') AS sewa_selesai
            FROM penyewaan
            WHERE status != 'Dibatalkan'
        ")->fetch();

        // Pendapatan per hari (7 transaksi/tanggal terakhir)
        $chartRows = $pdo->query("
            SELECT DATE_FORMAT(tgl_sewa, '%d %b') AS label, SUM(total_biaya + denda) AS total
            FROM penyewaan
            WHERE status != 'Dibatalkan'
            GROUP BY tgl_sewa
            ORDER BY tgl_sewa ASC
            LIMIT 10
        ")->fetchAll();

        $labels = [];
        $values = [];
        foreach ($chartRows as $r) {
            $labels[] = $r['label'];
            $values[] = (float)$r['total'];
        }

        $aktif = $pdo->query("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status IN ('Berjalan', 'Dipesan')
            ORDER BY FIELD(p.status, 'Berjalan', 'Dipesan'), p.tgl_kembali_rencana ASC
            LIMIT 8
        ")->fetchAll();

        $logs = $pdo->query("
            SELECT a.*, u.nama AS user_nama
            FROM activity_logs a
            LEFT JOIN users u ON u.id = a.user_id
            ORDER BY a.created_at DESC, a.id DESC
            LIMIT 6
        ")->fetchAll();

        return [
            'stats' => [
                'total_kendaraan'     => (int)($fleet['total_kendaraan'] ?? 0),
                'kendaraan_tersedia'  => (int)($fleet['kendaraan_tersedia'] ?? 0),
                'kendaraan_disewa'    => (int)($fleet['kendaraan_disewa'] ?? 0),
                'kendaraan_perawatan' => (int)($fleet['kendaraan_perawatan'] ?? 0),
                'total_pelanggan'     => $pelanggan,
                'total_pendapatan'    => (float)($rev['total_pendapatan'] ?? 0),
                'sewa_berjalan'       => (int)($rev['sewa_berjalan'] ?? 0),
                'sewa_dipesan'        => (int)($rev['sewa_dipesan'] ?? 0),
                'sewa_selesai'        => (int)($rev['sewa_selesai'] ?? 0),
            ],
            'chartLabels'        => $labels ?: ['Hari 1', 'Hari 2'],
            'chartValues'        => $values ?: [0, 0],
            'transaksiBerjalan'  => $aktif,
            'aktivitasTerbaru'   => $logs,
        ];
    }
}
