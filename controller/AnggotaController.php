<?php
// AnggotaController (Alias / Extension to PelangganController)
require_once __DIR__ . '/PelangganController.php';

use App\Controllers\PelangganController;

class AnggotaController extends PelangganController
{
}
if (!class_exists('App\Controllers\AnggotaController', false)) {
    class_alias('AnggotaController', 'App\Controllers\AnggotaController');
}
