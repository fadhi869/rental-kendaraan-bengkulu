<?php
namespace App\Controllers;

use App\Services\DashboardService;
use Controller;
use Request;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $service = new DashboardService();
        $data = $service->getSummary();
        $data['title'] = 'Dashboard — Rental Armada Bengkulu';
        $this->view('dashboard/index', $data);
    }
}
