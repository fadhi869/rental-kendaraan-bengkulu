<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$user = auth_user();
$isAdmin = ($user['role'] ?? '') === 'admin';

// Accurate active detection based on route segments
$isActive = function(string $route) use ($uri): string {
    if ($route === 'dashboard') {
        return (str_ends_with($uri, '/dashboard') || str_ends_with($uri, '/dashboard/') || str_ends_with($uri, '/index.php') || str_ends_with($uri, '/')) ? 'active' : '';
    }
    $clean = str_replace('-', '_', ltrim($route, '/'));
    return (str_contains($uri, '/' . ltrim($route, '/')) || str_contains($uri, '/' . $clean)) ? 'active' : '';
};
?>
<aside id="appSidebar" class="app-sidebar">
    <div class="sidebar-scroll">
        <a href="<?= base_url('dashboard') ?>" class="brand-header">
            <div class="brand-badge">
                <i data-lucide="car" style="width:18px;height:18px;"></i>
            </div>
            <div>
                <div class="brand-title">Manajemen Rental Kendaraan</div>
            </div>
        </a>

        <div class="nav-group">
            <div class="nav-heading">Menu Utama</div>
            <a href="<?= base_url('dashboard') ?>" class="nav-item <?= $isActive('dashboard') ?>">
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('penyewaan') ?>" class="nav-item <?= $isActive('penyewaan') ?>">
                <span>Penyewaan</span>
            </a>
            <a href="<?= base_url('pengembalian') ?>" class="nav-item <?= $isActive('pengembalian') ?>">
                <span>Pengembalian</span>
            </a>
            <a href="<?= base_url('riwayat') ?>" class="nav-item <?= $isActive('riwayat') ?>">
                <span>Riwayat</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-heading">Data Master</div>
            <a href="<?= base_url('kendaraan') ?>" class="nav-item <?= $isActive('kendaraan') ?>">
                <span>Kendaraan</span>
            </a>
            <a href="<?= base_url('jenis-kendaraan') ?>" class="nav-item <?= $isActive('jenis-kendaraan') ?>">
                <span>Jenis Kendaraan</span>
            </a>
            <a href="<?= base_url('pelanggan') ?>" class="nav-item <?= $isActive('pelanggan') ?>">
                <span>Pelanggan</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-heading">Laporan & Pengaturan</div>
            <a href="<?= base_url('laporan') ?>" class="nav-item <?= $isActive('laporan') ?>">
                <span>Laporan</span>
            </a>
            <?php if ($isAdmin): ?>
            <a href="<?= base_url('admin/users') ?>" class="nav-item <?= $isActive('admin/users') ?>">
                <span>Pengguna</span>
            </a>
            <a href="<?= base_url('aktivitas') ?>" class="nav-item <?= $isActive('aktivitas') ?>">
                <span>Aktivitas</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div style="display:flex;align-items:center;gap:9px;min-width:0;flex:1;">
                <div class="user-avatar-sm">
                    <?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="user-info-text">
                    <div class="user-name" title="<?= e($user['nama'] ?? 'Pengguna') ?>">
                        <?= e($user['nama'] ?? 'Pengguna') ?>
                    </div>
                    <div class="user-role-badge">
                        <?= e($user['role'] ?? 'petugas') ?>
                    </div>
                </div>
            </div>
            <form action="<?= base_url('logout') ?>" method="POST" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" class="btn-logout-icon" title="Keluar">
                    <i data-lucide="log-out" style="width:15px;height:15px;"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
