<div style="margin-bottom:22px;">
    <h1 style="margin:0;font-size:24px;font-weight:800;">Log Aktivitas Sistem</h1>
    <p style="margin:4px 0 0;color:#646163;">Jejak audit aktivitas login, transaksi penyewaan, dan perubahan data master.</p>
</div>

<div class="ui-card" style="padding:16px;margin-bottom:20px;">
    <form method="GET" action="<?= base_url('aktivitas') ?>" style="display:flex;gap:12px;">
        <input type="text" name="q" value="<?= e($q) ?>" class="ui-input" style="max-width:340px;" placeholder="Cari aksi, modul, atau deskripsi...">
        <button type="submit" class="btn btn-dark">Filter</button>
        <a href="<?= base_url('aktivitas') ?>" class="btn btn-warm">Reset</a>
    </form>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Pengguna</th>
                    <th>Aksi</th>
                    <th>Modul</th>
                    <th>Deskripsi</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                <tr>
                    <td style="font-size:12.5px;color:#646163;white-space:nowrap;"><?= e($l['created_at']) ?></td>
                    <td><strong><?= e($l['user_nama'] ?? 'Sistem') ?></strong></td>
                    <td><span class="badge badge-disewa"><?= e($l['action']) ?></span></td>
                    <td><code><?= e($l['module']) ?></code></td>
                    <td><?= e($l['description']) ?></td>
                    <td style="font-size:12px;color:#646163;"><?= e($l['ip_address'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
