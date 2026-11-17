<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new PeminjamanController();
$request = new Request();
$action = (string)$request->input('action', '');
$id = (string)$request->input('id', '');

if ($request->method() === 'POST') {
    if ($action === 'serah-kunci' && $id !== '') {
        $controller->serahKunci($request, $id);
    } elseif ($action === 'batal' && $id !== '') {
        $controller->batal($request, $id);
    } else {
        $controller->store($request);
    }
} else {
    $controller->index($request);
}
