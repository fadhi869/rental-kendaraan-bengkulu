<?php
namespace App\Services;

use Database;

class RiwayatService
{
    public function getHistory(string $status = '', string $search = ''): array
    {
        $pdo = Database::connection();
        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model, u.nama AS petugas_nama
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            LEFT JOIN users u ON u.id = p.created_by
            WHERE 1=1
        ";
        $params = [];
        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        if ($search !== '') {
            $sql .= " AND (p.kode_transaksi LIKE :q1 OR pl.nama LIKE :q2 OR k.plat_nomor LIKE :q3 OR k.model LIKE :q4)";
            $like = "%{$search}%";
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
            $params['q4'] = $like;
        }
        $sql .= " ORDER BY p.created_at DESC, p.id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
