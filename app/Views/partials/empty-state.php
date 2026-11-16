<div class="empty-state">
    <div class="empty-icon-wrap">
        <i data-lucide="<?= e($icon ?? 'inbox') ?>" style="width:22px;height:22px;"></i>
    </div>
    <div class="empty-title"><?= e($title ?? 'Data Tidak Ditemukan') ?></div>
    <p class="empty-text"><?= e($message ?? 'Belum ada data yang tersimpan atau tidak ada hasil yang cocok dengan pencarian.') ?></p>
    <?php if (!empty($actionUrl) && !empty($actionLabel)): ?>
        <a href="<?= e($actionUrl) ?>" class="btn btn-outline btn-sm">
            <i data-lucide="plus" style="width:14px;height:14px;"></i>
            <span><?= e($actionLabel) ?></span>
        </a>
    <?php endif; ?>
</div>
