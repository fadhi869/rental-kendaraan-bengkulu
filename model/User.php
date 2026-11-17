<?php
namespace App\Models;

use Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db()->prepare("
            SELECT * FROM users 
            WHERE username = :u AND deleted_at IS NULL 
            LIMIT 1
        ");
        $stmt->execute(['u' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare("
            SELECT * FROM users 
            WHERE id = :id AND deleted_at IS NULL 
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getAll(string $search = ''): array
    {
        $sql = "SELECT * FROM users WHERE deleted_at IS NULL";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (nama LIKE :q1 OR username LIKE :q2 OR email LIKE :q3)";
            $like = "%{$search}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like];
        }

        $sql .= " ORDER BY id ASC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function isUsernameTaken(string $username, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :u AND deleted_at IS NULL";
        $params = ['u' => $username];

        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function create(array $data): int
    {
        $stmt = $this->db()->prepare("
            INSERT INTO users (username, password, nama, email, role, status)
            VALUES (:u, :p, :n, :e, :r, :s)
        ");
        $stmt->execute([
            'u' => trim($data['username']),
            'p' => password_hash($data['password'], PASSWORD_BCRYPT),
            'n' => trim($data['nama']),
            'e' => trim($data['email'] ?? '') ?: null,
            'r' => ($data['role'] === 'admin') ? 'admin' : 'petugas',
            's' => ($data['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [
            'nama = :nama',
            'username = :username',
            'email = :email',
            'role = :role',
            'status = :status',
        ];
        $params = [
            'nama'     => trim($data['nama']),
            'username' => trim($data['username']),
            'email'    => trim($data['email'] ?? '') ?: null,
            'role'     => ($data['role'] === 'admin') ? 'admin' : 'petugas',
            'status'   => ($data['status'] ?? 'active') === 'inactive' ? 'inactive' : 'active',
            'id'       => $id,
        ];

        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->db()->prepare($sql);
        return $stmt->execute($params);
    }

    public function toggleStatus(int $id): string
    {
        $user = $this->findById($id);
        if (!$user) {
            return '';
        }

        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        $stmt = $this->db()->prepare("UPDATE users SET status = :s WHERE id = :id");
        $stmt->execute(['s' => $newStatus, 'id' => $id]);
        return $newStatus;
    }

    public function softDelete(int $id): bool
    {
        $stmt = $this->db()->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function updateLastLogin(int $id): bool
    {
        $stmt = $this->db()->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
