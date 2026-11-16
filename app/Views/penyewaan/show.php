<div style="max-width:860px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
        <div>
            <a href="<?= base_url('penyewaan') ?>" style="font-size:13px;font-weight:600;color:#646163;">&larr; Kembali</a>
            <h1 style="margin:4px 0 0;font-size:24px;font-weight:800;"><?= e($sewa['kode_transaksi']) ?></h1>
        </div>
        <span class="badge badge-<?= strtolower($sewa['status']) ?>" style="font-size:13px;padding:6px 14px;">
            <span class="badge-dot"></span><?= e($sewa['status']) ?>
        </span>
    </div>

    <div class="grid-12" style="margin-bottom:20px;">
        <div class="ui-card col-6" style="padding:22px;">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;margin-bottom:10px;">Data Penyewa</div>
            <div style="font-size:17px;font-weight:800;"><?= e($sewa['nama_pelanggan']) ?></div>
            <div style="color:#646163;margin-top:4px;">NIK: <?= e($sewa['nik']) ?> &bull; SIM: <?= e($sewa['no_sim']) ?></div>
            <div style="color:#646163;margin-top:2px;">HP: <?= e($sewa['no_hp']) ?></div>
            <div style="color:#646163;margin-top:6px;font-size:13px;"><?= e($sewa['alamat']) ?></div>
        </div>

        <div class="ui-card col-6" style="padding:22px;">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;margin-bottom:10px;">Unit Armada</div>
            <div style="font-size:17px;font-weight:800;"><?= e($sewa['merek'] . ' ' . $sewa['model']) ?></div>
            <div style="margin-top:6px;">
                <span style="background:#F8F7F3;border:1px solid #E5E9EC;padding:3px 10px;border-radius:8px;font-weight:700;"><?= e($sewa['plat_nomor']) ?></span>
                <span style="color:#646163;margin-left:8px;"><?= e($sewa['nama_jenis']) ?> (<?= e($sewa['warna']) ?>)</span>
            </div>
            <div style="margin-top:10px;font-size:13px;color:#646163;">Tarif Kontrak: <strong><?= rupiah($sewa['tarif_saat_sewa']) ?> / hari</strong></div>
        </div>
    </div>

    <div class="ui-card" style="padding:24px;margin-bottom:20px;">
        <div class="grid-kpi">
            <div>
                <div style="font-size:12px;color:#646163;">Mulai Sewa</div>
                <div style="font-weight:700;font-size:15px;margin-top:2px;"><?= tgl_indo($sewa['tgl_sewa']) ?></div>
            </div>
            <div>
                <div style="font-size:12px;color:#646163;">Rencana Kembali</div>
                <div style="font-weight:700;font-size:15px;margin-top:2px;"><?= tgl_indo($sewa['tgl_kembali_rencana']) ?> (<?= (int)$sewa['jumlah_hari'] ?> hr)</div>
            </div>
            <div>
                <div style="font-size:12px;color:#646163;">Biaya Sewa + Denda</div>
                <div style="font-weight:800;font-size:16px;color:#C0410E;margin-top:2px;">
                    <?= rupiah((float)$sewa['total_biaya'] + (float)$sewa['denda']) ?>
                </div>
            </div>
            <div>
                <div style="font-size:12px;color:#646163;">Petugas Input</div>
                <div style="font-weight:600;font-size:14px;margin-top:2px;"><?= e($sewa['petugas_nama'] ?? '-') ?></div>
            </div>
        </div>

        <?php if (!empty($sewa['catatan'])): ?>
            <div style="margin-top:18px;padding:12px 16px;background:#F8F7F3;border-radius:11px;font-size:13px;">
                <strong>Catatan:</strong> <?= e($sewa['catatan']) ?>
            </div>
        <?php endif; ?>
    </div>

    <div style="display:flex;gap:12px;justify-content:flex-end;">
        <?php if ($sewa['status'] === 'Dipesan'): ?>
            <form action="<?= base_url('penyewaan/' . $sewa['id'] . '/serah-kunci') ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-accent">Serah Kunci & Mulai Sewa</button>
            </form>
        <?php endif; ?>

        <?php if (in_array($sewa['status'], ['Dipesan', 'Berjalan'], true)): ?>
            <form action="<?= base_url('penyewaan/' . $sewa['id'] . '/batal') ?>" method="POST" onsubmit="return confirm('Batalkan transaksi ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger-soft">Batalkan Transaksi</button>
            </form>
        <?php endif; ?>
    </div>
</div>
