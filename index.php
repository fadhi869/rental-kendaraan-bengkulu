<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$request = new Request();
$path = $request->path();

// If accessed directly at root or index.php
if ($path === '/' || $path === '/index.php') {
    if (Auth::check()) {
        $controller = new DashboardController();
        $controller->index($request);
        exit;
    } else {
        $controller = new AuthController();
        $controller->loginForm($request);
        exit;
    }
}

// Route through central router for clean paths
$router = new Router();
require_once __DIR__ . '/config/routes.php';
$router->dispatch($request);
