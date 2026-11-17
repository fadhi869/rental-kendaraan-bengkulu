<div class="page-header">
    <div>
        <h1 class="page-title">Riwayat</h1>
        <p class="page-desc">Riwayat seluruh transaksi sewa kendaraan.</p>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('riwayat') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;width:100%;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" placeholder="Cari kode, nama penyewa, plat...">
        <select name="status" class="ui-select">
            <option value="">Semua Status</option>
            <?php foreach (['Selesai', 'Berjalan', 'Dipesan', 'Dibatalkan'] as $st): ?>
                <option value="<?= $st ?>" <?= ($status ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-dark">Cari</button>
        <?php if (!empty($q) || !empty($status)): ?>
            <a href="<?= base_url('riwayat') ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Mulai Sewa</th>
                    <th>Kembali Aktual</th>
                    <th>Total & Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="7">
                            <?php
                            $icon = 'history';
                            $title = 'Riwayat Tidak Ditemukan';
                            $message = 'Tidak ada transaksi yang cocok dengan kata kunci atau filter status yang dipilih.';
                            require BASE_PATH . '/view/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td>
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" style="font-weight:700;color:var(--accent);">
                                <?= e($r['kode_transaksi']) ?>
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['nama_pelanggan']) ?></div>
                            <div style="font-size:11.5px;color:var(--text-muted);"><?= e($r['no_hp']) ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['merek'] . ' ' . $r['model']) ?></div>
                            <span class="plate-tag"><?= e($r['plat_nomor']) ?></span>
                        </td>
                        <td><?= tgl_indo($r['tgl_sewa']) ?></td>
                        <td><?= tgl_indo($r['tgl_kembali_aktual']) ?></td>
                        <td><strong><?= rupiah((float)$r['total_biaya'] + (float)$r['denda']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <span class="badge-dot"></span><?= e($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
