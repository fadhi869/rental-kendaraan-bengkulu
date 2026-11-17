<?php
// Model DetailPeminjaman (Alias to Penyewaan Model)
require_once __DIR__ . '/Penyewaan.php';

use App\Models\Penyewaan;

class DetailPeminjaman extends Penyewaan
{
}
if (!class_exists('App\Models\DetailPeminjaman', false)) {
    class_alias('DetailPeminjaman', 'App\Models\DetailPeminjaman');
}
