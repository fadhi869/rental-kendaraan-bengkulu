<?php
// Main View: Kendaraan
if (isset($viewMode) && $viewMode === 'create') {
    require __DIR__ . '/kendaraan/create.php';
} elseif (isset($viewMode) && $viewMode === 'edit') {
    require __DIR__ . '/kendaraan/edit.php';
} else {
    require __DIR__ . '/kendaraan/index.php';
}
