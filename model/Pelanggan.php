<?php
declare(strict_types=1);

/**
 * Model Pelanggan (Penyewa Kendaraan)
 */
class Pelanggan extends Model
{
    protected string $table = 'pelanggan';

    public function getFiltered(string $search = ''): array
    {
        $sql = "SELECT * FROM pelanggan";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE nama LIKE :q1 OR nik LIKE :q2 OR no_hp LIKE :q3 OR no_sim LIKE :q4";
            $like = "%{$search}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        $sql .= " ORDER BY id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        return $this->db()->query("SELECT * FROM pelanggan ORDER BY nama ASC")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        return $this->find($id);
    }

    public function isNikTaken(string $nik, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM pelanggan WHERE nik = :nik";
        $params = ['nik' => trim($nik)];
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
        $stmt = $this->db()->prepare("SELECT COUNT(*) FROM penyewaan WHERE pelanggan_id = :id");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function hasActiveRentals(int $id): bool
    {
        $stmt = $this->db()->prepare("SELECT COUNT(*) FROM penyewaan WHERE pelanggan_id = :id AND status IN ('Berjalan', 'Dipesan')");
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function countAll(): int
    {
        return (int)$this->db()->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare("
            INSERT INTO pelanggan (nik, nama, alamat, no_hp, no_sim, email)
            VALUES (:nik, :nama, :alamat, :hp, :sim, :email)
        ");
        $stmt->execute([
            'nik'    => trim($data['nik']),
            'nama'   => trim($data['nama']),
            'alamat' => trim($data['alamat']),
            'hp'     => trim($data['no_hp']),
            'sim'    => trim($data['no_sim']),
            'email'  => trim($data['email'] ?? '') ?: null,
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db()->prepare("
            UPDATE pelanggan
            SET nik = :nik, nama = :nama, alamat = :alamat, no_hp = :hp, no_sim = :sim, email = :email
            WHERE id = :id
        ");
        return $stmt->execute([
            'nik'    => trim($data['nik']),
            'nama'   => trim($data['nama']),
            'alamat' => trim($data['alamat']),
            'hp'     => trim($data['no_hp']),
            'sim'    => trim($data['no_sim']),
            'email'  => trim($data['email'] ?? '') ?: null,
            'id'     => $id,
        ]);
    }
}

// Backward Compatibility Aliases
class Anggota extends Pelanggan {}
if (!class_exists('App\Models\Pelanggan', false)) class_alias('Pelanggan', 'App\Models\Pelanggan');
if (!class_exists('App\Models\Anggota', false)) class_alias('Pelanggan', 'App\Models\Anggota');
