<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;">
    <div>
        <h1 style="margin:0;font-size:24px;font-weight:800;">Transaksi Penyewaan</h1>
        <p style="margin:4px 0 0;color:#646163;">Kelola pemesanan baru, serah kunci unit, dan pemantauan sewa berjalan.</p>
    </div>
    <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-accent">
        <i data-lucide="plus" style="width:16px;height:16px;"></i>
        <span>Buat Sewa Baru</span>
    </a>
</div>

<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:18px;">
    <?php foreach (['' => 'Semua', 'Berjalan' => 'Berjalan', 'Dipesan' => 'Dipesan', 'Selesai' => 'Selesai', 'Dibatalkan' => 'Dibatalkan'] as $k => $label): ?>
        <a href="<?= base_url('penyewaan' . ($k ? '?status=' . $k : '')) ?>"
           class="btn btn-sm <?= ($status === $k) ? 'btn-dark' : 'btn-warm' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Unit Kendaraan</th>
                    <th>Tanggal Sewa</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><strong><?= e($r['kode_transaksi']) ?></strong></td>
                    <td>
                        <div style="font-weight:600;"><?= e($r['nama_pelanggan']) ?></div>
                        <div style="font-size:12px;color:#646163;"><?= e($r['no_hp']) ?></div>
                    </td>
                    <td>
                        <div style="font-weight:600;"><?= e($r['merek'] . ' ' . $r['model']) ?></div>
                        <div style="font-size:12px;color:#646163;"><?= e($r['plat_nomor']) ?></div>
                    </td>
                    <td style="font-size:13px;"><?= tgl_indo($r['tgl_sewa']) ?> &rarr; <?= tgl_indo($r['tgl_kembali_rencana']) ?></td>
                    <td><?= (int)$r['jumlah_hari'] ?> Hari</td>
                    <td><strong><?= rupiah($r['total_biaya']) ?></strong></td>
                    <td><span class="badge badge-<?= strtolower($r['status']) ?>"><span class="badge-dot"></span><?= e($r['status']) ?></span></td>
                    <td style="text-align:right;">
                        <a href="<?= base_url('penyewaan/' . $r['id']) ?>" class="btn btn-warm btn-sm">Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
