<div class="page-header">
    <div>
        <h1 class="page-title">Laporan Pendapatan</h1>
        <p class="page-desc">Rekap pendapatan sewa dan denda per periode tanggal.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('laporan/export?dari=' . urlencode($dari) . '&sampai=' . urlencode($sampai)) ?>" class="btn btn-primary">
            <i data-lucide="download" style="width:15px;height:15px;"></i>
            <span>Ekspor CSV</span>
        </a>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('laporan') ?>" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;width:100%;">
        <div>
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="dari" value="<?= e($dari) ?>" class="ui-input" style="width:160px;">
        </div>
        <div>
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="sampai" value="<?= e($sampai) ?>" class="ui-input" style="width:160px;">
        </div>
        <button type="submit" class="btn btn-dark">Tampilkan</button>
    </form>
</div>

<div class="grid-kpi" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:20px;">
    <div class="kpi-card">
        <div class="kpi-label">Pendapatan Sewa Pokok</div>
        <div class="kpi-value" style="font-size:22px;margin-top:8px;"><?= rupiah($total_sewa) ?></div>
        <div class="kpi-sub">Biaya durasi sewa kendaraan</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Pendapatan Denda</div>
        <div class="kpi-value" style="font-size:22px;color:var(--accent);margin-top:8px;"><?= rupiah($total_denda) ?></div>
        <div class="kpi-sub">Total denda keterlambatan</div>
    </div>
    <div class="card-dark" style="padding:18px 20px;">
        <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#A8A5A6;">Grand Total Pendapatan</div>
        <div style="font-size:22px;font-weight:800;color:#FFFFFF;margin-top:8px;"><?= rupiah($grand_total) ?></div>
        <div style="font-size:11.5px;color:#A8A5A6;margin-top:4px;">Sewa pokok + denda periode ini</div>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal Sewa</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Sewa Pokok</th>
                    <th>Denda</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="8">
                            <?php
                            $icon = 'file-text';
                            $title = 'Tidak Ada Transaksi di Periode Ini';
                            $message = 'Tidak ada transaksi penyewaan yang dimulai pada rentang tanggal ' . tgl_indo($dari) . ' sampai ' . tgl_indo($sampai) . '.';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
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
                        <td><?= tgl_indo($r['tgl_sewa']) ?></td>
                        <td><strong><?= e($r['nama_pelanggan']) ?></strong></td>
                        <td><?= e($r['merek'] . ' ' . $r['model']) ?> (<?= e($r['plat_nomor']) ?>)</td>
                        <td><?= rupiah($r['total_biaya']) ?></td>
                        <td><?= (float)$r['denda'] > 0 ? rupiah($r['denda']) : '-' ?></td>
                        <td><strong><?= rupiah((float)$r['total_biaya'] + (float)$r['denda']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <?= e($r['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
