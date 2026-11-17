<?php
// KategoriController (Alias / Extension to JenisKendaraanController)
require_once __DIR__ . '/JenisKendaraanController.php';

use App\Controllers\JenisKendaraanController;

class KategoriController extends JenisKendaraanController
{
}
if (!class_exists('App\Controllers\KategoriController', false)) {
    class_alias('KategoriController', 'App\Controllers\KategoriController');
}
