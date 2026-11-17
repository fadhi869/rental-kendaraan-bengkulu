<?php
namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogger
{
    public static function log(string $action, string $module, string $description, ?int $userId = null): void
    {
        (new ActivityLog())->record($action, $module, $description, $userId);
    }
}
