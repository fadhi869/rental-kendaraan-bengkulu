<?php
namespace App\Models;

use Model;
use PDO;

class Kendaraan extends Model
{
    protected string $table = 'kendaraan';

    public function getFiltered(string $search = '', string $status = ''): array
    {
        $sql = "
            SELECT k.*, jk.nama_jenis
            FROM kendaraan k
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE 1=1
        ";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (k.plat_nomor LIKE :q1 OR k.merek LIKE :q2 OR k.model LIKE :q3)";
            $like = "%{$search}%";
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
        }

        if ($status !== '') {
            $sql .= " AND k.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY k.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAvailable(): array
    {
        $stmt = $this->db()->query("
            SELECT k.*, jk.nama_jenis
            FROM kendaraan k
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE k.status = 'Tersedia'
            ORDER BY k.merek ASC, k.model ASC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare("
            SELECT k.*, jk.nama_jenis
            FROM kendaraan k
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE k.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByIdWithLock(int $id, PDO $pdo): ?array
    {
        $stmt = $pdo->prepare("SELECT * FROM kendaraan WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function isPlateTaken(string $platNomor, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM kendaraan WHERE plat_nomor = :plat";
        $params = ['plat' => strtoupper(trim($platNomor))];

        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function hasRentals(int $id): bool
    {
        $stmt = $this->db()->prepare("SELECT COUNT(*) FROM penyewaan WHERE kendaraan_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function hasActiveRentals(int $id): bool
    {
        $stmt = $this->db()->prepare("
            SELECT COUNT(*) FROM penyewaan 
            WHERE kendaraan_id = :id AND status IN ('Berjalan', 'Dipesan')
        ");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare("
            INSERT INTO kendaraan (plat_nomor, merek, model, tahun, warna, tarif_per_hari, status, jenis_id)
            VALUES (:plat, :merek, :model, :tahun, :warna, :tarif, :status, :jenis)
        ");
        $stmt->execute([
            'plat'   => strtoupper(trim($data['plat_nomor'])),
            'merek'  => trim($data['merek']),
            'model'  => trim($data['model']),
            'tahun'  => (int)$data['tahun'],
            'warna'  => trim($data['warna']),
            'tarif'  => (float)$data['tarif_per_hari'],
            'status' => in_array($data['status'] ?? '', ['Tersedia', 'Disewa', 'Perawatan'], true) ? $data['status'] : 'Tersedia',
            'jenis'  => (int)$data['jenis_id'],
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare("
            UPDATE kendaraan
            SET plat_nomor = :plat,
                merek = :merek,
                model = :model,
                tahun = :tahun,
                warna = :warna,
                tarif_per_hari = :tarif,
                status = :status,
                jenis_id = :jenis
            WHERE id = :id
        ");
        return $stmt->execute([
            'plat'   => strtoupper(trim($data['plat_nomor'])),
            'merek'  => trim($data['merek']),
            'model'  => trim($data['model']),
            'tahun'  => (int)$data['tahun'],
            'warna'  => trim($data['warna']),
            'tarif'  => (float)$data['tarif_per_hari'],
            'status' => in_array($data['status'] ?? '', ['Tersedia', 'Disewa', 'Perawatan'], true) ? $data['status'] : 'Tersedia',
            'jenis'  => (int)$data['jenis_id'],
            'id'     => $id,
        ]);
    }

    public function updateStatus(int $id, string $status, ?PDO $pdo = null): bool
    {
        $conn = $pdo ?? $this->db();
        $stmt = $conn->prepare("UPDATE kendaraan SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function getFleetStats(): array
    {
        $row = $this->db()->query("
            SELECT
                COUNT(*) AS total_kendaraan,
                COALESCE(SUM(status = 'Tersedia'), 0) AS kendaraan_tersedia,
                COALESCE(SUM(status = 'Disewa'), 0) AS kendaraan_disewa,
                COALESCE(SUM(status = 'Perawatan'), 0) AS kendaraan_perawatan
            FROM kendaraan
        ")->fetch();

        return [
            'total_kendaraan'     => (int)($row['total_kendaraan'] ?? 0),
            'kendaraan_tersedia'  => (int)($row['kendaraan_tersedia'] ?? 0),
            'kendaraan_disewa'    => (int)($row['kendaraan_disewa'] ?? 0),
            'kendaraan_perawatan' => (int)($row['kendaraan_perawatan'] ?? 0),
        ];
    }
}
