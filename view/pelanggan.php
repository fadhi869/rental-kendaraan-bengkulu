<?php
// Main View: Pelanggan
if (isset($viewMode) && $viewMode === 'create') {
    require __DIR__ . '/pelanggan/create.php';
} elseif (isset($viewMode) && $viewMode === 'edit') {
    require __DIR__ . '/pelanggan/edit.php';
} else {
    require __DIR__ . '/pelanggan/index.php';
}
