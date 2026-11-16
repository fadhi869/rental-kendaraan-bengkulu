<div style="margin-bottom:22px;">
    <h1 style="margin:0;font-size:24px;font-weight:800;">Pengembalian Unit Armada</h1>
    <p style="margin:4px 0 0;color:#646163;">Proses serah terima kendaraan kembali. Denda keterlambatan dihitung otomatis sebesar <strong><?= $multiplier ?>x</strong> tarif harian.</p>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Unit Kendaraan</th>
                    <th>Jatuh Tempo</th>
                    <th>Tarif / Hari</th>
                    <th>Proses Pengembalian</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:32px;color:#646163;">Seluruh unit telah dikembalikan. Tidak ada sewa berjalan.</td></tr>
                <?php else: ?>
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
                        <td>
                            <strong><?= tgl_indo($r['tgl_kembali_rencana']) ?></strong>
                            <?php if (strtotime(date('Y-m-d')) > strtotime($r['tgl_kembali_rencana'])): ?>
                                <div style="font-size:11px;color:#C0410E;font-weight:700;">Lewat Jatuh Tempo</div>
                            <?php endif; ?>
                        </td>
                        <td><?= rupiah($r['tarif_saat_sewa']) ?></td>
                        <td>
                            <form action="<?= base_url('pengembalian/' . $r['id'] . '/proses') ?>" method="POST" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                                <?= csrf_field() ?>
                                <input type="date" name="tgl_kembali_aktual" value="<?= date('Y-m-d') ?>" class="ui-input" style="width:150px;padding:7px 10px;">
                                <input type="text" name="catatan" placeholder="Kondisi unit..." class="ui-input" style="width:170px;padding:7px 10px;">
                                <button type="submit" class="btn btn-dark btn-sm">Selesaikan</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
