<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new JenisKendaraanController();
$request = new Request();
$action = (string)$request->input('action', '');
$id = (string)$request->input('id', '');

if ($request->method() === 'POST') {
    if ($action === 'delete') {
        $controller->destroy($request, $id);
    } elseif ($action === 'update' || $id !== '') {
        $controller->update($request, $id);
    } else {
        $controller->store($request);
    }
} else {
    $controller->index($request);
}
