<?php
namespace App\Controllers;

use App\Models\ActivityLog;
use Controller;
use Request;

class ActivityController extends Controller
{
    private ActivityLog $activity;

    public function __construct()
    {
        $this->activity = new ActivityLog();
    }

    public function index(Request $request): void
    {
        $this->requireAdmin();
        $q = trim((string)$request->input('q', ''));
        $logs = $this->activity->getLatest(150, $q);

        $this->view('aktivitas/index', [
            'title' => 'Catatan Aktivitas — Rental Armada Bengkulu',
            'logs'  => $logs,
            'q'     => $q,
        ]);
    }
}
