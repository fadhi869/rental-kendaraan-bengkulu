<?php
namespace App\Models;

use Auth;
use Model;
use PDO;

class ActivityLog extends Model
{
    protected string $table = 'activity_logs';

    public function record(string $action, string $module, string $description, ?int $userId = null, ?string $ip = null): void
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
                'ip'     => $ip ?? ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'),
            ]);
        } catch (\Throwable $e) {
            // Fail silently to avoid breaking main workflow
        }
    }

    public function getLatest(int $limit = 100, string $search = ''): array
    {
        $sql = "
            SELECT a.*, u.nama AS user_nama, u.username, u.role
            FROM activity_logs a
            LEFT JOIN users u ON u.id = a.user_id
        ";
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
