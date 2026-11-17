<?php
declare(strict_types=1);

/**
 * Model Kategori / Jenis Kendaraan
 */
class Kategori extends Model
{
    protected string $table = 'jenis_kendaraan';

    public function getAll(): array
    {
        return $this->db()->query("SELECT * FROM jenis_kendaraan ORDER BY nama_jenis ASC")->fetchAll();
    }

    public function getAllWithUnitCount(): array
    {
        return $this->db()->query("
            SELECT jk.*, COUNT(k.id) AS jumlah_unit
            FROM jenis_kendaraan jk
            LEFT JOIN kendaraan k ON k.jenis_id = jk.id
            GROUP BY jk.id
            ORDER BY jk.id ASC
        ")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    public function hasUnits(int $id): bool
    {
        $stmt = $this->db()->prepare("SELECT COUNT(*) FROM kendaraan WHERE jenis_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare("INSERT INTO jenis_kendaraan (nama_jenis, deskripsi) VALUES (:n, :d)");
        $stmt->execute([
            'n' => trim($data['nama_jenis']),
            'd' => trim($data['deskripsi'] ?? ''),
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare("UPDATE jenis_kendaraan SET nama_jenis = :n, deskripsi = :d WHERE id = :id");
        return $stmt->execute([
            'n'  => trim($data['nama_jenis']),
            'd'  => trim($data['deskripsi'] ?? ''),
            'id' => $id,
        ]);
    }
}

// Backward Compatibility Aliases
class JenisKendaraan extends Kategori {}
if (!class_exists('App\Models\JenisKendaraan', false)) class_alias('Kategori', 'App\Models\JenisKendaraan');
if (!class_exists('App\Models\Kategori', false)) class_alias('Kategori', 'App\Models\Kategori');
