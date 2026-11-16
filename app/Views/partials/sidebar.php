<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$user = auth_user();
$isAdmin = ($user['role'] ?? '') === 'admin';

// Accurate active detection based on route segments
$isActive = function(string $route) use ($uri): string {
    $base = base_url($route);
    if ($route === 'dashboard') {
        return (str_ends_with($uri, '/dashboard') || str_ends_with($uri, '/dashboard/')) ? 'active' : '';
    }
    return str_contains($uri, '/' . ltrim($route, '/')) ? 'active' : '';
};
?>
<aside id="appSidebar" class="app-sidebar">
    <div class="sidebar-scroll">
        <a href="<?= base_url('dashboard') ?>" class="brand-header">
            <div class="brand-badge">
                <i data-lucide="car" style="width:18px;height:18px;"></i>
            </div>
            <div>
                <div class="brand-title">Armada Bengkulu</div>
                <div class="brand-subtitle">Rental Kendaraan</div>
            </div>
        </a>

        <div class="nav-group">
            <div class="nav-heading">Menu Utama</div>
            <a href="<?= base_url('dashboard') ?>" class="nav-item <?= $isActive('dashboard') ?>">
                <i data-lucide="layout-dashboard" style="width:16px;height:16px;"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('penyewaan') ?>" class="nav-item <?= $isActive('penyewaan') ?>">
                <i data-lucide="key" style="width:16px;height:16px;"></i>
                <span>Penyewaan</span>
            </a>
            <a href="<?= base_url('pengembalian') ?>" class="nav-item <?= $isActive('pengembalian') ?>">
                <i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i>
                <span>Pengembalian</span>
            </a>
            <a href="<?= base_url('riwayat') ?>" class="nav-item <?= $isActive('riwayat') ?>">
                <i data-lucide="history" style="width:16px;height:16px;"></i>
                <span>Riwayat Sewa</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-heading">Data Master</div>
            <a href="<?= base_url('kendaraan') ?>" class="nav-item <?= $isActive('kendaraan') ?>">
                <i data-lucide="car-front" style="width:16px;height:16px;"></i>
                <span>Data Kendaraan</span>
            </a>
            <a href="<?= base_url('jenis-kendaraan') ?>" class="nav-item <?= $isActive('jenis-kendaraan') ?>">
                <i data-lucide="tag" style="width:16px;height:16px;"></i>
                <span>Jenis Kendaraan</span>
            </a>
            <a href="<?= base_url('pelanggan') ?>" class="nav-item <?= $isActive('pelanggan') ?>">
                <i data-lucide="users" style="width:16px;height:16px;"></i>
                <span>Data Pelanggan</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-heading">Laporan & Pengaturan</div>
            <a href="<?= base_url('laporan') ?>" class="nav-item <?= $isActive('laporan') ?>">
                <i data-lucide="bar-chart-2" style="width:16px;height:16px;"></i>
                <span>Laporan Pendapatan</span>
            </a>
            <?php if ($isAdmin): ?>
            <a href="<?= base_url('admin/users') ?>" class="nav-item <?= $isActive('admin/users') ?>">
                <i data-lucide="shield" style="width:16px;height:16px;"></i>
                <span>Kelola Pengguna</span>
            </a>
            <a href="<?= base_url('aktivitas') ?>" class="nav-item <?= $isActive('aktivitas') ?>">
                <i data-lucide="activity" style="width:16px;height:16px;"></i>
                <span>Catatan Aktivitas</span>
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
