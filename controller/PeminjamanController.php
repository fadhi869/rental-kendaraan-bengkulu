<?php
// PeminjamanController (Alias / Extension to PenyewaanController)
require_once __DIR__ . '/PenyewaanController.php';

use App\Controllers\PenyewaanController;

class PeminjamanController extends PenyewaanController
{
}
if (!class_exists('App\Controllers\PeminjamanController', false)) {
    class_alias('PeminjamanController', 'App\Controllers\PeminjamanController');
}
