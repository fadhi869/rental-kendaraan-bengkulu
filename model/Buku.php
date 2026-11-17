<?php
// Model Buku (Alias to Kendaraan Model)
require_once __DIR__ . '/Kendaraan.php';

use App\Models\Kendaraan;

class Buku extends Kendaraan
{
}
if (!class_exists('App\Models\Buku', false)) {
    class_alias('Buku', 'App\Models\Buku');
}
