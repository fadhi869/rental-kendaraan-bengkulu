<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new LaporanController();
$request = new Request();
$action = (string)$request->input('action', '');

if ($action === 'export') {
    $controller->export($request);
} else {
    $controller->index($request);
}
