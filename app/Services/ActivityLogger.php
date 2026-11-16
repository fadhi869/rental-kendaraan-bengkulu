<?php
namespace App\Services;

use Auth;
use Database;

class ActivityLogger
{
    public static function log(string $action, string $module, string $description, ?int $userId = null): void
    {
        try {
            $pdo = Database::connection();
            $stmt = $pdo->prepare("
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
        } catch (\Throwable $e) {
            // Silent fail for logging
        }
    }
}
