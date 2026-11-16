<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Dashboard — Rental Armada Bengkulu') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <div class="app-shell">
        <?php require BASE_PATH . '/app/Views/partials/sidebar.php'; ?>
        <div class="app-main">
            <?php require BASE_PATH . '/app/Views/partials/topbar.php'; ?>
            <main class="app-content">
                <?php require BASE_PATH . '/app/Views/partials/flash.php'; ?>
                <?= $content ?>
            </main>
        </div>
    </div>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
