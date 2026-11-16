<?php $flash = Flash::get(); ?>
<?php if ($flash): ?>
    <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>">
        <span><?= e($flash['message']) ?></span>
        <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;font-weight:700;color:inherit;">&times;</button>
    </div>
<?php endif; ?>
