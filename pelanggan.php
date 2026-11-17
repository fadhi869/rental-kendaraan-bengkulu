<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new PelangganController();
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
    if ($action === 'create') {
        $controller->create($request);
    } elseif ($action === 'edit' && $id !== '') {
        $controller->edit($request, $id);
    } else {
        $controller->index($request);
    }
}
