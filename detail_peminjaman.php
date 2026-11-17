<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new DetailPeminjamanController();
$request = new Request();
$action = (string)$request->input('action', '');
$id = (string)$request->input('id', '');

if ($request->method() === 'POST') {
    if ($action === 'proses' && $id !== '') {
        $controller->proses($request, $id);
    } else {
        $controller->index($request);
    }
} else {
    $controller->index($request);
}
