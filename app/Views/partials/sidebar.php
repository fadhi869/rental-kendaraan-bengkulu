<?php
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$user = auth_user();
$isAdmin = ($user['role'] ?? '') === 'admin';
$active = fn(string $p): string => str_contains($uri, $p) ? 'active' : '';
?>
<aside id="appSidebar" class="app-sidebar">
    <div style="overflow-y:auto;">
        <a href="<?= base_url('dashboard') ?>" class="brand-box">
            <div class="brand-icon">
                <i data-lucide="car-front" style="width:20px;height:20px;"></i>
            </div>
            <div>
                <div style="font-weight:700;font-size:15px;color:#FFFFFF;line-height:1.2;">Armada Bengkulu</div>
                <div style="font-size:11px;color:#9E9B9C;">Fleet Management</div>
            </div>
        </a>

        <div class="nav-section">
            <div class="nav-label">Menu Utama</div>
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= $active('/dashboard') ?>">
                <i data-lucide="layout-dashboard" style="width:17px;height:17px;"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= base_url('penyewaan') ?>" class="nav-link <?= $active('/penyewaan') ?>">
                <i data-lucide="key-round" style="width:17px;height:17px;"></i>
                <span>Transaksi Sewa</span>
            </a>
            <a href="<?= base_url('pengembalian') ?>" class="nav-link <?= $active('/pengembalian') ?>">
                <i data-lucide="rotate-ccw" style="width:17px;height:17px;"></i>
                <span>Pengembalian</span>
            </a>
            <a href="<?= base_url('riwayat') ?>" class="nav-link <?= $active('/riwayat') ?>">
                <i data-lucide="history" style="width:17px;height:17px;"></i>
                <span>Riwayat Sewa</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-label">Master Data</div>
            <a href="<?= base_url('kendaraan') ?>" class="nav-link <?= $active('/kendaraan') ?>">
                <i data-lucide="car" style="width:17px;height:17px;"></i>
                <span>Data Kendaraan</span>
            </a>
            <a href="<?= base_url('jenis-kendaraan') ?>" class="nav-link <?= $active('/jenis-kendaraan') ?>">
                <i data-lucide="tags" style="width:17px;height:17px;"></i>
                <span>Jenis Kendaraan</span>
            </a>
            <a href="<?= base_url('pelanggan') ?>" class="nav-link <?= $active('/pelanggan') ?>">
                <i data-lucide="users" style="width:17px;height:17px;"></i>
                <span>Data Pelanggan</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-label">Analitik & Sistem</div>
            <a href="<?= base_url('laporan') ?>" class="nav-link <?= $active('/laporan') ?>">
                <i data-lucide="bar-chart-3" style="width:17px;height:17px;"></i>
                <span>Laporan Pendapatan</span>
            </a>
            <?php if ($isAdmin): ?>
            <a href="<?= base_url('admin/users') ?>" class="nav-link <?= $active('/admin/users') ?>">
                <i data-lucide="shield-check" style="width:17px;height:17px;"></i>
                <span>Manajemen User</span>
            </a>
            <a href="<?= base_url('aktivitas') ?>" class="nav-link <?= $active('/aktivitas') ?>">
                <i data-lucide="activity" style="width:17px;height:17px;"></i>
                <span>Log Aktivitas</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div style="padding:14px;border-top:1px solid rgba(255,255,255,0.08);">
        <div style="background:#333132;border-radius:12px;padding:10px 12px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
            <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                <div style="width:32px;height:32px;border-radius:9px;background:#C0410E;color:#fff;font-weight:700;font-size:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:12.5px;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <?= e($user['nama'] ?? 'User') ?>
                    </div>
                    <div style="font-size:11px;color:#A19EA0;text-transform:capitalize;">
                        <?= e($user['role'] ?? 'petugas') ?>
                    </div>
                </div>
            </div>
            <form action="<?= base_url('logout') ?>" method="POST" style="margin:0;">
                <?= csrf_field() ?>
                <button type="submit" title="Keluar" style="background:none;border:none;color:#A19EA0;cursor:pointer;padding:6px;border-radius:8px;">
                    <i data-lucide="log-out" style="width:16px;height:16px;"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
