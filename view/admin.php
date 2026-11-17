<?php
// Main View: Admin / Manajemen Pengguna
if (isset($viewMode) && $viewMode === 'create') {
    require __DIR__ . '/admin/users/create.php';
} elseif (isset($viewMode) && $viewMode === 'edit') {
    require __DIR__ . '/admin/users/edit.php';
} else {
    require __DIR__ . '/admin/users/index.php';
}
