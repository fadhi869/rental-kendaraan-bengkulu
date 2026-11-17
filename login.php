<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new AuthController();
$request = new Request();

if ($request->method() === 'POST') {
    $controller->login($request);
} else {
    $controller->loginForm($request);
}
