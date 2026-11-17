<?php
// Model Peminjaman (Alias to Penyewaan Model)
require_once __DIR__ . '/Penyewaan.php';

use App\Models\Penyewaan;

class Peminjaman extends Penyewaan
{
}
if (!class_exists('App\Models\Peminjaman', false)) {
    class_alias('Peminjaman', 'App\Models\Peminjaman');
}
