<?php
namespace App\Services;

use Database;

class ActivityService
{
    public function latest(int $limit = 100, string $search = ''): array
    {
        $pdo = Database::connection();
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
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
