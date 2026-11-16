<div class="page-header">
    <div>
        <h1 class="page-title">Pengembalian</h1>
        <p class="page-desc">Proses pengembalian unit sewa. Denda dihitung otomatis (pengali: <strong><?= $multiplier ?>x</strong>).</p>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Jatuh Tempo</th>
                    <th>Tarif / Hari</th>
                    <th style="min-width:320px;">Proses Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $icon = 'check-circle';
                            $title = 'Semua Unit Telah Kembali';
                            $message = 'Saat ini tidak ada kendaraan yang berstatus sedang disewa.';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <?php $isOverdue = strtotime(date('Y-m-d')) > strtotime($r['tgl_kembali_rencana']); ?>
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
                        <td>
                            <div style="font-weight:600;"><?= tgl_indo($r['tgl_kembali_rencana']) ?></div>
                            <?php if ($isOverdue): ?>
                                <span class="badge badge-berjalan" style="margin-top:3px;font-size:10.5px;">Lewat Jatuh Tempo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= rupiah($r['tarif_saat_sewa']) ?></td>
                        <td>
                            <form action="<?= base_url('pengembalian/' . $r['id'] . '/proses') ?>" method="POST" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                <?= csrf_field() ?>
                                <input type="date" name="tgl_kembali_aktual" value="<?= date('Y-m-d') ?>" class="ui-input" style="width:136px;padding:6px 10px;font-size:12.5px;">
                                <input type="text" name="catatan" placeholder="Kondisi kendaraan..." class="ui-input" style="width:160px;padding:6px 10px;font-size:12.5px;">
                                <button type="submit" class="btn btn-dark btn-sm" onclick="return confirm('Proses pengembalian unit ini?')">
                                    <span>Selesaikan</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
