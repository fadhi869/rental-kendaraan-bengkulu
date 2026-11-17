<?php
// Model Kategori (Alias to JenisKendaraan Model)
require_once __DIR__ . '/JenisKendaraan.php';

use App\Models\JenisKendaraan;

class Kategori extends JenisKendaraan
{
}
if (!class_exists('App\Models\Kategori', false)) {
    class_alias('Kategori', 'App\Models\Kategori');
}
