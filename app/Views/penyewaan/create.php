<div style="max-width:740px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('penyewaan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar Transaksi</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Buat Sewa Baru</h1>
        <p class="page-desc">Pilih penyewa, unit kendaraan yang tersedia, dan tentukan durasi sewa.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('penyewaan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="grid-12" style="gap:14px;">
                <div class="form-group col-6">
                    <label class="form-label">Pilih Pelanggan <span class="required">*</span></label>
                    <select name="pelanggan_id" class="ui-select" required autofocus>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?> (<?= e($p['nik']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Pilih Kendaraan Tersedia <span class="required">*</span></label>
                    <select id="kendaraanSelect" name="kendaraan_id" class="ui-select" required>
                        <option value="" data-tarif="0">-- Pilih Unit Armada --</option>
                        <?php foreach ($kendaraan as $k): ?>
                            <option value="<?= $k['id'] ?>" data-tarif="<?= (float)$k['tarif_per_hari'] ?>">
                                <?= e($k['plat_nomor']) ?> — <?= e($k['merek'] . ' ' . $k['model']) ?> (<?= rupiah($k['tarif_per_hari']) ?>/hr)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Tanggal Mulai Sewa <span class="required">*</span></label>
                    <input type="date" id="tglSewa" name="tgl_sewa" class="ui-input" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Rencana Tanggal Kembali <span class="required">*</span></label>
                    <input type="date" id="tglKembali" name="tgl_kembali_rencana" class="ui-input" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Tipe Transaksi</label>
                    <select name="status" class="ui-select">
                        <option value="Berjalan">Langsung Jalan (Kunci diserahkan sekarang)</option>
                        <option value="Dipesan">Booking / Reservasi (Jadwal mendatang)</option>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Perkiraan Biaya Sewa</label>
                    <div id="estimasiBox" style="background:var(--dark);color:#FFFFFF;padding:8px 12px;border-radius:var(--radius-md);font-weight:700;font-size:14px;min-height:38px;display:flex;align-items:center;">
                        Rp 0 (1 Hari)
                    </div>
                </div>

                <div class="form-group col-12">
                    <label class="form-label">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" class="ui-textarea" placeholder="Tujuan perjalanan, kelengkapan unit, atau jaminan yang dititipkan..."></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('penyewaan') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sel = document.getElementById('kendaraanSelect');
    const d1 = document.getElementById('tglSewa');
    const d2 = document.getElementById('tglKembali');
    const box = document.getElementById('estimasiBox');

    function calc() {
        const opt = sel.options[sel.selectedIndex];
        const tarif = parseFloat(opt?.dataset?.tarif || 0);
        const t1 = new Date(d1.value);
        const t2 = new Date(d2.value);
        let days = Math.round((t2 - t1) / 86400000);
        if (isNaN(days) || days < 1) days = 1;
        const total = tarif * days;
        box.textContent = 'Rp ' + total.toLocaleString('id-ID') + ' (' + days + ' Hari)';
    }

    sel.addEventListener('change', calc);
    d1.addEventListener('change', calc);
    d2.addEventListener('change', calc);
    calc();
});
</script>
