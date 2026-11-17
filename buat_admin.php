<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$pdo = Database::connection();

// Check if default admin exists
$stmt = $pdo->prepare("SELECT id, username, nama, role FROM users WHERE username = 'admin' AND deleted_at IS NULL LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch();

$message = '';
if (!$admin) {
    $stmt = $pdo->prepare("
        INSERT INTO users (username, password, nama, email, role, status)
        VALUES ('admin', :pass, 'Administrator Utama', 'admin@rental.local', 'admin', 'active')
    ");
    $stmt->execute([
        'pass' => password_hash('admin123', PASSWORD_BCRYPT),
    ]);
    $message = "Akun admin default berhasil dibuat! Username: <strong>admin</strong> | Password: <strong>admin123</strong>";
} else {
    $message = "Akun admin (username: <strong>{$admin['username']}</strong>) sudah ada dalam database.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inisialisasi Admin — Rental Kendaraan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body class="auth-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;background:var(--bg-app);">
    <div style="max-width:480px;width:90%;background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:32px;box-shadow:var(--shadow-lg);">
        <h2 style="font-size:20px;font-weight:700;color:var(--text-primary);margin-bottom:12px;">Inisialisasi Akun Admin</h2>
        <div style="padding:16px;background:rgba(22,163,74,0.08);border:1px solid rgba(22,163,74,0.25);border-radius:var(--radius-md);color:var(--text-primary);font-size:14px;line-height:1.6;margin-bottom:20px;">
            <?= $message ?>
        </div>
        <div style="display:flex;gap:12px;">
            <a href="<?= base_url('login.php') ?>" class="btn btn-primary" style="flex:1;text-align:center;padding:10px 16px;border-radius:var(--radius-md);text-decoration:none;">Menuju Halaman Login</a>
            <a href="<?= base_url('index.php') ?>" class="btn btn-outline" style="flex:1;text-align:center;padding:10px 16px;border-radius:var(--radius-md);text-decoration:none;">Dashboard</a>
        </div>
    </div>
</body>
</html>
