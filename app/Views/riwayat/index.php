<div style="margin-bottom:22px;">
    <h1 style="margin:0;font-size:24px;font-weight:800;">Riwayat Seluruh Penyewaan</h1>
    <p style="margin:4px 0 0;color:#646163;">Arsip lengkap transaksi selesai, berjalan, dipesan, maupun dibatalkan.</p>
</div>

<div class="ui-card" style="padding:16px;margin-bottom:20px;">
    <form method="GET" action="<?= base_url('riwayat') ?>" style="display:flex;gap:12px;flex-wrap:wrap;">
        <input type="text" name="q" value="<?= e($q) ?>" class="ui-input" style="max-width:300px;" placeholder="Cari kode, nama penyewa, plat...">
        <select name="status" class="ui-select" style="max-width:190px;">
            <option value="">Semua Status</option>
            <?php foreach (['Selesai', 'Berjalan', 'Dipesan', 'Dibatalkan'] as $st): ?>
                <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= $st ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-dark">Cari</button>
        <a href="<?= base_url('riwayat') ?>" class="btn btn-warm">Reset</a>
    </form>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Tanggal Sewa</th>
                    <th>Kembali Aktual</th>
                    <th>Total + Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><a href="<?= base_url('penyewaan/' . $r['id']) ?>" style="font-weight:700;color:#C0410E;"><?= e($r['kode_transaksi']) ?></a></td>
                    <td><?= e($r['nama_pelanggan']) ?></td>
                    <td><?= e($r['merek'] . ' ' . $r['model']) ?> <small style="color:#646163;">(<?= e($r['plat_nomor']) ?>)</small></td>
                    <td><?= tgl_indo($r['tgl_sewa']) ?></td>
                    <td><?= tgl_indo($r['tgl_kembali_aktual']) ?></td>
                    <td><strong><?= rupiah((float)$r['total_biaya'] + (float)$r['denda']) ?></strong></td>
                    <td><span class="badge badge-<?= strtolower($r['status']) ?>"><span class="badge-dot"></span><?= e($r['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
