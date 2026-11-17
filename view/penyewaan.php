<?php
// Main View: Penyewaan
if (isset($viewMode) && $viewMode === 'create') {
    require __DIR__ . '/penyewaan/create.php';
} elseif (isset($viewMode) && $viewMode === 'show') {
    require __DIR__ . '/penyewaan/show.php';
} else {
    require __DIR__ . '/penyewaan/index.php';
}
