<?php
// Model Anggota (Alias to Pelanggan Model)
require_once __DIR__ . '/Pelanggan.php';

use App\Models\Pelanggan;

class Anggota extends Pelanggan
{
}
if (!class_exists('App\Models\Anggota', false)) {
    class_alias('Anggota', 'App\Models\Anggota');
}
