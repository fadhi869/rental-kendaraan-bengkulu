<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px;">
        <div>
            <a href="<?= base_url('penyewaan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                <span>Kembali</span>
            </a>
            <h1 class="page-title" style="margin-top:4px;"><?= e($sewa['kode_transaksi']) ?></h1>
        </div>
        <span class="badge badge-<?= strtolower($sewa['status']) ?>" style="font-size:12.5px;padding:5px 12px;">
            <span class="badge-dot"></span><?= e($sewa['status']) ?>
        </span>
    </div>

    <!-- Details Grid -->
    <div class="grid-12" style="margin-bottom:18px;">
        <div class="card card-padded col-6">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;letter-spacing:0.04em;">Data Penyewa</div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);"><?= e($sewa['nama_pelanggan']) ?></div>
            <div style="color:var(--text-secondary);margin-top:4px;font-size:12.5px;">NIK: <?= e($sewa['nik']) ?> &bull; SIM: <?= e($sewa['no_sim']) ?></div>
            <div style="color:var(--text-secondary);margin-top:2px;font-size:12.5px;">Nomor HP: <?= e($sewa['no_hp']) ?></div>
            <div style="color:var(--text-secondary);margin-top:6px;font-size:12.5px;line-height:1.4;"><?= e($sewa['alamat']) ?></div>
        </div>

        <div class="card card-padded col-6">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--text-muted);margin-bottom:8px;letter-spacing:0.04em;">Unit Kendaraan</div>
            <div style="font-size:16px;font-weight:700;color:var(--text-primary);"><?= e($sewa['merek'] . ' ' . $sewa['model']) ?></div>
            <div style="margin-top:6px;">
                <span class="plate-tag"><?= e($sewa['plat_nomor']) ?></span>
                <span style="color:var(--text-secondary);margin-left:8px;font-size:12.5px;"><?= e($sewa['nama_jenis']) ?> (<?= e($sewa['warna']) ?>)</span>
            </div>
            <div style="margin-top:10px;font-size:12.5px;color:var(--text-secondary);">Tarif Sewa: <strong><?= rupiah($sewa['tarif_saat_sewa']) ?> / hari</strong></div>
        </div>
    </div>

    <!-- Financial & Timeline Summary -->
    <div class="card card-padded" style="margin-bottom:18px;">
        <div class="grid-kpi" style="margin-bottom:12px;">
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);">Mulai Sewa</div>
                <div style="font-weight:700;font-size:14px;margin-top:3px;"><?= tgl_indo($sewa['tgl_sewa']) ?></div>
            </div>
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);">Rencana Kembali</div>
                <div style="font-weight:700;font-size:14px;margin-top:3px;"><?= tgl_indo($sewa['tgl_kembali_rencana']) ?> (<?= (int)$sewa['jumlah_hari'] ?> hr)</div>
            </div>
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);">Kembali Aktual</div>
                <div style="font-weight:700;font-size:14px;margin-top:3px;"><?= tgl_indo($sewa['tgl_kembali_aktual']) ?></div>
            </div>
            <div>
                <div style="font-size:11.5px;color:var(--text-muted);">Total Tagihan</div>
                <div style="font-weight:800;font-size:16px;color:var(--accent);margin-top:2px;">
                    <?= rupiah((float)$sewa['total_biaya'] + (float)$sewa['denda']) ?>
                </div>
            </div>
        </div>

        <?php if ((float)$sewa['denda'] > 0): ?>
            <div style="margin-top:12px;padding:10px 14px;background:var(--danger-bg);border:1px solid var(--danger-border);border-radius:var(--radius-md);font-size:12.5px;color:var(--danger);">
                <strong>Keterlambatan:</strong> Terlambat <?= (int)$sewa['hari_terlambat'] ?> hari dengan denda <strong><?= rupiah($sewa['denda']) ?></strong>.
            </div>
        <?php endif; ?>

        <?php if (!empty($sewa['catatan'])): ?>
            <div style="margin-top:12px;padding:10px 14px;background:var(--bg-warm);border:1px solid var(--border);border-radius:var(--radius-md);font-size:12.5px;">
                <strong>Catatan:</strong> <?= e($sewa['catatan']) ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:14px;font-size:12px;color:var(--text-muted);">
            Petugas pembuat transaksi: <strong><?= e($sewa['petugas_nama'] ?? 'Sistem') ?></strong>
        </div>
    </div>

    <!-- Actions -->
    <div style="display:flex;gap:10px;justify-content:flex-end;">
        <?php if ($sewa['status'] === 'Dipesan'): ?>
            <form action="<?= base_url('penyewaan/' . $sewa['id'] . '/serah-kunci') ?>" method="POST">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="key" style="width:14px;height:14px;"></i>
                    <span>Serahkan Kunci</span>
                </button>
            </form>
        <?php endif; ?>

        <?php if (in_array($sewa['status'], ['Dipesan', 'Berjalan'], true)): ?>
            <form action="<?= base_url('penyewaan/' . $sewa['id'] . '/batal') ?>" method="POST" onsubmit="return confirm('Batalkan sewa ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger-soft">Batalkan</button>
            </form>
        <?php endif; ?>
    </div>
</div>
