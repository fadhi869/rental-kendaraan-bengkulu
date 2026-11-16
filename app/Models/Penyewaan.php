<?php
namespace App\Models;

use Model;
use PDO;

class Penyewaan extends Model
{
    protected string $table = 'penyewaan';

    public function getFiltered(string $status = ''): array
    {
        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE 1=1
        ";
        $params = [];

        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getReturnsList(): array
    {
        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status = 'Berjalan'
            ORDER BY p.tgl_kembali_rencana ASC
        ";
        return $this->db()->query($sql)->fetchAll();
    }

    public function getHistory(string $status = '', string $search = ''): array
    {
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
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findByIdWithDetails(int $id): ?array
    {
        $stmt = $this->db()->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.nik, pl.no_hp, pl.no_sim, pl.alamat,
                   k.plat_nomor, k.merek, k.model, k.warna, k.tahun, jk.nama_jenis,
                   u.nama AS petugas_nama
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            LEFT JOIN users u ON u.id = p.created_by
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByIdWithLock(int $id, PDO $pdo): ?array
    {
        $stmt = $pdo->prepare("
            SELECT p.*, k.plat_nomor, k.status AS k_status
            FROM penyewaan p
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.id = :id
            FOR UPDATE
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getActiveAndReserved(int $limit = 8): array
    {
        $stmt = $this->db()->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp,
                   k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status IN ('Berjalan', 'Dipesan')
            ORDER BY FIELD(p.status, 'Berjalan', 'Dipesan'), p.tgl_kembali_rencana ASC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function generateKodeTransaksi(): string
    {
        $prefix = 'SEWA-' . date('Ym') . '-';
        $stmt = $this->db()->prepare("
            SELECT kode_transaksi FROM penyewaan 
            WHERE kode_transaksi LIKE :p 
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute(['p' => $prefix . '%']);
        $last = $stmt->fetchColumn();
        $next = 1;
        if ($last) {
            $num = (int)substr($last, strlen($prefix));
            $next = $num + 1;
        }
        return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    public function createRecord(array $data, PDO $pdo): int
    {
        $stmt = $pdo->prepare("
            INSERT INTO penyewaan
            (kode_transaksi, pelanggan_id, kendaraan_id, created_by, tgl_sewa, tgl_kembali_rencana, tarif_saat_sewa, jumlah_hari, total_biaya, status, catatan)
            VALUES
            (:kode, :pid, :kid, :uid, :tgl_sewa, :tgl_kembali, :tarif, :hari, :total, :status, :catatan)
        ");
        $stmt->execute([
            'kode'        => $data['kode_transaksi'],
            'pid'         => (int)$data['pelanggan_id'],
            'kid'         => (int)$data['kendaraan_id'],
            'uid'         => $data['created_by'],
            'tgl_sewa'    => $data['tgl_sewa'],
            'tgl_kembali' => $data['tgl_kembali_rencana'],
            'tarif'       => (float)$data['tarif_saat_sewa'],
            'hari'        => (int)$data['jumlah_hari'],
            'total'       => (float)$data['total_biaya'],
            'status'      => $data['status'],
            'catatan'     => trim((string)($data['catatan'] ?? '')),
        ]);
        return (int)$pdo->lastInsertId();
    }

    public function updateStatus(int $id, string $status, PDO $pdo): bool
    {
        $stmt = $pdo->prepare("UPDATE penyewaan SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function completeRental(int $id, string $tglAktual, int $hariTerlambat, float $denda, string $catatan, PDO $pdo): bool
    {
        $stmt = $pdo->prepare("
            UPDATE penyewaan
            SET tgl_kembali_aktual = :tgl,
                hari_terlambat = :ht,
                denda = :denda,
                status = 'Selesai',
                catatan = :catatan
            WHERE id = :id
        ");
        return $stmt->execute([
            'tgl'     => $tglAktual,
            'ht'      => $hariTerlambat,
            'denda'   => $denda,
            'catatan' => mb_substr($catatan, 0, 255),
            'id'      => $id,
        ]);
    }

    public function getSummaryStats(): array
    {
        $row = $this->db()->query("
            SELECT
                COALESCE(SUM(total_biaya + denda), 0) AS total_pendapatan,
                COALESCE(SUM(status = 'Berjalan'), 0) AS sewa_berjalan,
                COALESCE(SUM(status = 'Dipesan'), 0) AS sewa_dipesan,
                COALESCE(SUM(status = 'Selesai'), 0) AS sewa_selesai
            FROM penyewaan
            WHERE status != 'Dibatalkan'
        ")->fetch();

        return [
            'total_pendapatan' => (float)($row['total_pendapatan'] ?? 0),
            'sewa_berjalan'    => (int)($row['sewa_berjalan'] ?? 0),
            'sewa_dipesan'     => (int)($row['sewa_dipesan'] ?? 0),
            'sewa_selesai'     => (int)($row['sewa_selesai'] ?? 0),
        ];
    }

    public function getDailyRevenueChart(int $limit = 10): array
    {
        $rows = $this->db()->query("
            SELECT DATE_FORMAT(tgl_sewa, '%d %b') AS label, SUM(total_biaya + denda) AS total
            FROM penyewaan
            WHERE status != 'Dibatalkan'
            GROUP BY tgl_sewa
            ORDER BY tgl_sewa ASC
            LIMIT {$limit}
        ")->fetchAll();

        $labels = [];
        $values = [];
        foreach ($rows as $r) {
            $labels[] = $r['label'];
            $values[] = (float)$r['total'];
        }

        return [
            'labels' => $labels ?: ['Hari 1', 'Hari 2'],
            'values' => $values ?: [0, 0],
        ];
    }
}
