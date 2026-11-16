<div class="page-header">
    <div>
        <h1 class="page-title">Aktivitas</h1>
        <p class="page-desc">Riwayat aktivitas pengguna dalam sistem.</p>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('aktivitas') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;width:100%;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" placeholder="Cari nama pengguna, modul, atau keterangan...">
        <button type="submit" class="btn btn-dark">Cari</button>
        <?php if (!empty($q)): ?>
            <a href="<?= base_url('aktivitas') ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Modul</th>
                    <th>Keterangan Aktivitas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $icon = 'activity';
                            $title = 'Catatan Aktivitas Kosong';
                            $message = 'Tidak ada catatan aktivitas yang sesuai dengan kriteria pencarian.';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                    <tr>
                        <td style="font-size:12px;color:var(--text-secondary);white-space:nowrap;"><?= e($l['created_at']) ?></td>
                        <td><strong><?= e($l['user_nama'] ?? 'Sistem') ?></strong></td>
                        <td><span class="badge badge-berjalan"><?= e($l['action']) ?></span></td>
                        <td><code><?= e($l['module']) ?></code></td>
                        <td><?= e($l['description']) ?></td>
                        <td style="font-size:11.5px;color:var(--text-muted);"><?= e($l['ip_address'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
