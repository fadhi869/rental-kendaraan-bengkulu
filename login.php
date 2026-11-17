<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$controller = new AuthController();
$request = new Request();

if ($request->method() === 'POST') {
    $controller->login($request);
    exit;
}

if (Auth::check()) {
    redirect('index.php');
}

$flash = Flash::get();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login — Rental Kendaraan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="auth-body" style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg-app);padding:20px;">
    <div class="auth-card" style="width:100%;max-width:440px;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:36px;box-shadow:var(--shadow-lg);">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:52px;height:52px;border-radius:14px;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;">
                <i data-lucide="car" style="width:26px;height:26px;"></i>
            </div>
            <div style="font-size:12px;font-weight:700;color:var(--text-secondary);letter-spacing:1px;text-transform:uppercase;">MANAJEMEN RENTAL KENDARAAN</div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text-primary);margin:6px 0 0 0;">Halaman Login</h1>
        </div>

        <?php if ($flash): ?>
        <div style="padding:12px 14px;border-radius:var(--radius-md);margin-bottom:20px;font-size:13px;background:<?= $flash['type']==='success'?'rgba(22,163,74,0.1)':'rgba(220,38,38,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(22,163,74,0.3)':'rgba(220,38,38,0.3)' ?>;color:<?= $flash['type']==='success'?'var(--success)':'var(--danger)' ?>;">
            <?= e($flash['message']) ?>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('login.php') ?>" method="POST">
            <?= csrf_field() ?>

            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Username</label>
                <input type="text" name="username" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Masukkan username" required autofocus>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">Password</label>
                <input type="password" name="password" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;padding:12px;font-size:14px;font-weight:700;border-radius:var(--radius-md);">
                Masuk ke Sistem
            </button>
        </form>

        <div style="margin-top:24px;padding-top:18px;border-top:1px solid var(--border);text-align:center;font-size:12px;color:var(--text-secondary);">
            <span>Kelompok 1 &bull; Tugas Besar Praktikum PBO</span>
        </div>
    </div>
    <script>
    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    </script>
</body>
</html>
