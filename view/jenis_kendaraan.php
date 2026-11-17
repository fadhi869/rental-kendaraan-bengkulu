<?php
// Main View: Jenis Kendaraan
if (isset($viewMode) && $viewMode === 'create') {
    require __DIR__ . '/jenis-kendaraan/create.php';
} elseif (isset($viewMode) && $viewMode === 'edit') {
    require __DIR__ . '/jenis-kendaraan/edit.php';
} else {
    require __DIR__ . '/jenis-kendaraan/index.php';
}
