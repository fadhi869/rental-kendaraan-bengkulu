<?php
namespace App\Services;

use App\Models\ActivityLog;

class ActivityService
{
    public function latest(int $limit = 100, string $search = ''): array
    {
        return (new ActivityLog())->getLatest($limit, $search);
    }
}
