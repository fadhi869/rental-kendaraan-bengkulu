<div class="auth-wrapper">
    <div class="card card-padded" style="max-width:440px;width:100%;text-align:center;padding:36px 28px;">
        <div style="font-size:44px;font-weight:800;color:var(--accent);line-height:1;"><?= (int)($code ?? 404) ?></div>
        <h1 style="margin:12px 0 6px;font-size:18px;font-weight:700;color:var(--text-primary);"><?= e($title ?? 'Halaman Tidak Ditemukan') ?></h1>
        <p style="color:var(--text-secondary);font-size:13px;margin:0 0 20px;line-height:1.45;"><?= e($message ?? 'Halaman yang Anda tuju tidak ditemukan atau telah dipindahkan.') ?></p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-dark" style="width:100%;">Kembali ke Dashboard</a>
    </div>
</div>
