<?php $user = auth_user(); ?>
<header class="app-topbar">
    <div style="display:flex;align-items:center;gap:12px;">
        <button id="sidebarToggle" type="button" class="mobile-toggle" aria-label="Menu">
            <i data-lucide="menu" style="width:18px;height:18px;"></i>
        </button>
        <div>
            <div style="font-size:12px;color:var(--text-muted);"><?= tgl_indo(date('Y-m-d')) ?></div>
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-accent btn-sm">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Sewa Baru</span>
        </a>
        <div style="display:flex;align-items:center;gap:8px;background:var(--bg-warm);border:1px solid var(--border);padding:5px 12px 5px 6px;border-radius:999px;">
            <span style="width:26px;height:26px;border-radius:50%;background:var(--dark);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;">
                <?= strtoupper(substr($user['nama'] ?? 'A', 0, 1)) ?>
            </span>
            <span style="font-size:12.5px;font-weight:600;color:var(--dark);"><?= e($user['nama'] ?? 'Petugas') ?></span>
        </div>
    </div>
</header>
