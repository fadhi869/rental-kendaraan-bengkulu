<?php
declare(strict_types=1);

/**
 * Model Admin / User Management & Log Aktivitas
 */
class Admin extends Model
{
    protected string $table = 'users';

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE username = :u AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['u' => $username]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
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
        $fields = ['nama = :nama', 'username = :username', 'email = :email', 'role = :role', 'status = :status'];
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
        return $this->db()->prepare($sql)->execute($params);
    }

    public function toggleStatus(int $id): string
    {
        $user = $this->findById($id);
        if (!$user) return '';
        $newStatus = ($user['status'] === 'active') ? 'inactive' : 'active';
        $this->db()->prepare("UPDATE users SET status = :s WHERE id = :id")->execute(['s' => $newStatus, 'id' => $id]);
        return $newStatus;
    }

    public function softDelete(int $id): bool
    {
        return $this->db()->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id")->execute(['id' => $id]);
    }

    public function updateLastLogin(int $id): bool
    {
        return $this->db()->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id")->execute(['id' => $id]);
    }

    public function logActivity(string $action, string $module, string $description, ?int $userId = null): void
    {
        try {
            $stmt = $this->db()->prepare("
                INSERT INTO activity_logs (user_id, action, module, description, ip_address)
                VALUES (:uid, :action, :module, :desc, :ip)
            ");
            $stmt->execute([
                'uid'    => $userId ?? Auth::id(),
                'action' => $action,
                'module' => $module,
                'desc'   => mb_substr($description, 0, 255),
                'ip'     => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ]);
        } catch (\Throwable $e) {}
    }

    public function getLatestLogs(int $limit = 100, string $search = ''): array
    {
        $sql = "SELECT a.*, u.nama AS user_nama, u.username, u.role FROM activity_logs a LEFT JOIN users u ON u.id = a.user_id";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE a.description LIKE :q1 OR a.module LIKE :q2 OR a.action LIKE :q3 OR u.nama LIKE :q4";
            $like = "%{$search}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        $sql .= " ORDER BY a.created_at DESC, a.id DESC LIMIT " . (int)$limit;
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}

// Backward Compatibility Aliases
class User extends Admin {}
class ActivityLog extends Admin {}
if (!class_exists('App\Models\User', false)) class_alias('Admin', 'App\Models\User');
if (!class_exists('App\Models\Admin', false)) class_alias('Admin', 'App\Models\Admin');
if (!class_exists('App\Models\ActivityLog', false)) class_alias('Admin', 'App\Models\ActivityLog');
