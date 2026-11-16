<?php
namespace App\Models;

use Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE username = :u AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['u' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
