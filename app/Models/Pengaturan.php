<?php
namespace App\Models;

use Model;

class Pengaturan extends Model
{
    protected string $table = 'pengaturan';

    public function get(string $key, mixed $default = null): mixed
    {
        $stmt = $this->db()->prepare("SELECT nilai FROM pengaturan WHERE kunci = :k LIMIT 1");
        $stmt->execute(['k' => $key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public function getDendaMultiplier(): float
    {
        $stmt = $this->db()->prepare("
            SELECT nilai FROM pengaturan 
            WHERE kunci IN ('denda_multiplier', 'pengali_denda') 
            LIMIT 1
        ");
        $stmt->execute();
        $val = $stmt->fetchColumn();
        return $val ? (float)$val : 1.5;
    }

    public function set(string $key, string $value, string $keterangan = ''): bool
    {
        $stmt = $this->db()->prepare("
            INSERT INTO pengaturan (kunci, nilai, keterangan)
            VALUES (:k, :v, :desc)
            ON DUPLICATE KEY UPDATE nilai = :v2
        ");
        return $stmt->execute([
            'k'    => $key,
            'v'    => $value,
            'desc' => $keterangan,
            'v2'   => $value,
        ]);
    }
}
