<?php
namespace App\Controllers;

use App\Services\ActivityService;
use Controller;
use Request;

class ActivityController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAdmin();
        $q = trim((string)$request->input('q', ''));
        $logs = (new ActivityService())->latest(150, $q);

        $this->view('aktivitas/index', [
            'title' => 'Log Aktivitas Sistem — Rental Armada Bengkulu',
            'logs'  => $logs,
            'q'     => $q,
        ]);
    }
}
