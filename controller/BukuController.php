<?php
// BukuController (Alias / Extension to KendaraanController)
require_once __DIR__ . '/KendaraanController.php';

use App\Controllers\KendaraanController;

class BukuController extends KendaraanController
{
}
if (!class_exists('App\Controllers\BukuController', false)) {
    class_alias('BukuController', 'App\Controllers\BukuController');
}
