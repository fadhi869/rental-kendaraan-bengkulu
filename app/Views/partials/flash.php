<?php $flash = Flash::get(); ?>
<?php if ($flash): ?>
    <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>">
        <span><?= e($flash['message']) ?></span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
    </div>
<?php endif; ?>
