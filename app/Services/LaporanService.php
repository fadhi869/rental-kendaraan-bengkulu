<?php
namespace App\Services;

use Database;

class LaporanService
{
    public function getReport(?string $dari = null, ?string $sampai = null): array
    {
        $pdo = Database::connection();
        $dari = $dari ?: date('Y-m-01');
        $sampai = $sampai ?: date('Y-m-t');

        $stmt = $pdo->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, k.plat_nomor, k.merek, k.model, jk.nama_jenis
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE p.status != 'Dibatalkan'
              AND p.tgl_sewa BETWEEN :dari AND :sampai
            ORDER BY p.tgl_sewa DESC, p.id DESC
        ");
        $stmt->execute(['dari' => $dari, 'sampai' => $sampai]);
        $rows = $stmt->fetchAll();

        $totalSewa = 0.0;
        $totalDenda = 0.0;
        foreach ($rows as $r) {
            $totalSewa += (float)$r['total_biaya'];
            $totalDenda += (float)$r['denda'];
        }

        return [
            'dari'        => $dari,
            'sampai'      => $sampai,
            'rows'        => $rows,
            'total_sewa'  => $totalSewa,
            'total_denda' => $totalDenda,
            'grand_total' => $totalSewa + $totalDenda,
        ];
    }
}
