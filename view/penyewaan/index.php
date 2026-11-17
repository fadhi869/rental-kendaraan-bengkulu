<div class="page-header">
    <div>
        <h1 class="page-title">Penyewaan</h1>
        <p class="page-desc">Kelola transaksi sewa dan status armada.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Sewa Baru</span>
        </a>
    </div>
</div>

<div class="tabs-nav">
    <?php foreach (['' => 'Semua', 'Berjalan' => 'Berjalan', 'Dipesan' => 'Dipesan', 'Selesai' => 'Selesai', 'Dibatalkan' => 'Dibatalkan'] as $k => $label): ?>
        <a href="<?= base_url('penyewaan' . ($k ? '?status=' . $k : '')) ?>"
           class="tab-btn <?= ($status === $k) ? 'active' : '' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Jadwal Sewa</th>
                    <th>Durasi</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="8">
                            <?php
                            $icon = 'key';
                            $title = 'Belum Ada Transaksi';
                            $message = 'Tidak ada transaksi dengan status ini atau belum ada data yang dibuat.';
                            $actionUrl = base_url('penyewaan/create');
                            $actionLabel = 'Buat Sewa Baru';
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
                        <td style="font-size:12.5px;color:var(--text-secondary);">
                            <?= tgl_indo($r['tgl_sewa']) ?> &rarr; <?= tgl_indo($r['tgl_kembali_rencana']) ?>
                        </td>
                        <td><?= (int)$r['jumlah_hari'] ?> Hari</td>
                        <td><strong><?= rupiah($r['total_biaya']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <span class="badge-dot"></span><?= e($r['status']) ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
