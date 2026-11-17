<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new ActivityController();
$request = new Request();
$controller->index($request);
