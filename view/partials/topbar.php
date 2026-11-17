<?php $user = auth_user(); ?>
<header class="app-topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" type="button" class="mobile-toggle-btn" aria-label="Buka menu navigasi">
            <i data-lucide="menu" style="width:18px;height:18px;"></i>
        </button>
        <span class="topbar-date"><?= tgl_indo(date('Y-m-d')) ?></span>
    </div>

    <div class="topbar-right">
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-primary btn-premium-cta btn-sm">
            <i data-lucide="plus" style="width:14px;height:14px;"></i>
            <span>Tambah Penyewaan</span>
        </a>
        <div class="topbar-user-pill">
            <span class="topbar-user-avatar">
                <?= strtoupper(substr($user['nama'] ?? 'A', 0, 1)) ?>
            </span>
            <span class="topbar-user-name"><?= e($user['nama'] ?? 'Petugas') ?></span>
        </div>
    </div>
</header>
