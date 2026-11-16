<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;">
    <div>
        <h1 style="margin:0;font-size:24px;font-weight:800;">Laporan Pendapatan Armada</h1>
        <p style="margin:4px 0 0;color:#646163;">Rekapitulasi pendapatan sewa pokok dan denda keterlambatan per periode.</p>
    </div>
    <a href="<?= base_url('laporan/export?dari=' . urlencode($dari) . '&sampai=' . urlencode($sampai)) ?>" class="btn btn-accent">
        <i data-lucide="download" style="width:16px;height:16px;"></i>
        <span>Export CSV</span>
    </a>
</div>

<div class="ui-card" style="padding:16px;margin-bottom:20px;">
    <form method="GET" action="<?= base_url('laporan') ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:end;">
        <div>
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="dari" value="<?= e($dari) ?>" class="ui-input">
        </div>
        <div>
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="sampai" value="<?= e($sampai) ?>" class="ui-input">
        </div>
        <button type="submit" class="btn btn-dark">Tampilkan Laporan</button>
    </form>
</div>

<div class="grid-kpi" style="grid-template-columns:repeat(3,minmax(0,1fr));margin-bottom:22px;">
    <div class="ui-card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;">Pendapatan Sewa Pokok</div>
        <div style="font-size:24px;font-weight:800;margin-top:8px;"><?= rupiah($total_sewa) ?></div>
    </div>
    <div class="ui-card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;">Pendapatan Denda</div>
        <div style="font-size:24px;font-weight:800;color:#C0410E;margin-top:8px;"><?= rupiah($total_denda) ?></div>
    </div>
    <div class="ui-card-dark" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#B8B5B6;">Grand Total Pendapatan</div>
        <div style="font-size:24px;font-weight:800;color:#FFFFFF;margin-top:8px;"><?= rupiah($grand_total) ?></div>
    </div>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal Sewa</th>
                    <th>Pelanggan</th>
                    <th>Armada</th>
                    <th>Sewa Pokok</th>
                    <th>Denda</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><strong><?= e($r['kode_transaksi']) ?></strong></td>
                    <td><?= tgl_indo($r['tgl_sewa']) ?></td>
                    <td><?= e($r['nama_pelanggan']) ?></td>
                    <td><?= e($r['merek'] . ' ' . $r['model']) ?> (<?= e($r['plat_nomor']) ?>)</td>
                    <td><?= rupiah($r['total_biaya']) ?></td>
                    <td><?= rupiah($r['denda']) ?></td>
                    <td><strong><?= rupiah((float)$r['total_biaya'] + (float)$r['denda']) ?></strong></td>
                    <td><span class="badge badge-<?= strtolower($r['status']) ?>"><?= e($r['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
