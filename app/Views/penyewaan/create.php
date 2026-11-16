<div style="max-width:780px;margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="<?= base_url('penyewaan') ?>" style="font-size:13px;font-weight:600;color:#646163;">&larr; Kembali ke Daftar Transaksi</a>
        <h1 style="margin:6px 0 0;font-size:22px;font-weight:800;">Formulir Penyewaan Kendaraan</h1>
    </div>

    <div class="ui-card" style="padding:28px;">
        <form action="<?= base_url('penyewaan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="grid-12" style="gap:16px;">
                <div class="form-group col-6">
                    <label class="form-label">Pilih Pelanggan</label>
                    <select name="pelanggan_id" class="ui-select" required>
                        <option value="">-- Pilih Penyewa --</option>
                        <?php foreach ($pelanggan as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= e($p['nama']) ?> (<?= e($p['nik']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Pilih Kendaraan Tersedia</label>
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
                    <label class="form-label">Tanggal Mulai Sewa</label>
                    <input type="date" id="tglSewa" name="tgl_sewa" class="ui-input" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Rencana Tanggal Kembali</label>
                    <input type="date" id="tglKembali" name="tgl_kembali_rencana" class="ui-input" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Tipe Transaksi</label>
                    <select name="status" class="ui-select">
                        <option value="Berjalan">Langsung Jalan (Serah Kunci Sekarang)</option>
                        <option value="Dipesan">Booking / Reservasi Terjadwal</option>
                    </select>
                </div>

                <div class="form-group col-6">
                    <label class="form-label">Estimasi Total Biaya</label>
                    <div id="estimasiBox" style="background:#282627;color:#FFFFFF;padding:10px 14px;border-radius:11px;font-weight:800;font-size:15px;">
                        Rp 0 (1 Hari)
                    </div>
                </div>

                <div class="form-group" style="grid-column:span 12;">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan" rows="2" class="ui-textarea" placeholder="Tujuan perjalanan, jaminan identitas, dll..."></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                <a href="<?= base_url('penyewaan') ?>" class="btn btn-warm">Batal</a>
                <button type="submit" class="btn btn-accent">Simpan & Proses Transaksi</button>
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
});
</script>
