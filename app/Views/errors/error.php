<div class="auth-wrapper">
    <div class="ui-card" style="max-width:460px;padding:40px;text-align:center;">
        <div style="font-size:48px;font-weight:800;color:#C0410E;"><?= (int)($code ?? 404) ?></div>
        <h1 style="margin:8px 0;font-size:20px;font-weight:800;">Halaman Tidak Ditemukan</h1>
        <p style="color:#646163;margin:0 0 24px;"><?= e($message ?? 'Terjadi kesalahan.') ?></p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-dark">Kembali ke Dashboard</a>
    </div>
</div>
