<?php
// DetailPeminjamanController (Alias / Extension to PengembalianController)
require_once __DIR__ . '/PengembalianController.php';

use App\Controllers\PengembalianController;

class DetailPeminjamanController extends PengembalianController
{
}
if (!class_exists('App\Controllers\DetailPeminjamanController', false)) {
    class_alias('DetailPeminjamanController', 'App\Controllers\DetailPeminjamanController');
}
