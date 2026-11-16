<div style="max-width:680px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('kendaraan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Tambah Kendaraan Baru</h1>
        <p class="page-desc">Isi formulir berikut untuk mendaftarkan unit armada baru.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('kendaraan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="grid-12" style="gap:14px;">
                <div class="form-group col-6">
                    <label class="form-label">Plat Nomor <span class="required">*</span></label>
                    <input type="text" name="plat_nomor" class="ui-input" value="<?= e($item['plat_nomor'] ?? '') ?>" placeholder="BD 1234 XX" required autofocus>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Jenis Kendaraan <span class="required">*</span></label>
                    <select name="jenis_id" class="ui-select" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach ($jenisList as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= ((int)($item['jenis_id'] ?? 0) === (int)$j['id']) ? 'selected' : '' ?>>
                                <?= e($j['nama_jenis']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Merek <span class="required">*</span></label>
                    <input type="text" name="merek" class="ui-input" value="<?= e($item['merek'] ?? '') ?>" placeholder="Contoh: Toyota" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Model / Tipe <span class="required">*</span></label>
                    <input type="text" name="model" class="ui-input" value="<?= e($item['model'] ?? '') ?>" placeholder="Contoh: Avanza 1.5 G" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Tahun Pembuatan <span class="required">*</span></label>
                    <input type="number" name="tahun" class="ui-input" value="<?= e((string)($item['tahun'] ?? date('Y'))) ?>" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Warna <span class="required">*</span></label>
                    <input type="text" name="warna" class="ui-input" value="<?= e($item['warna'] ?? '') ?>" placeholder="Contoh: Hitam Metalik" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Tarif Sewa / Hari (Rp) <span class="required">*</span></label>
                    <input type="number" name="tarif_per_hari" class="ui-input" value="<?= e((string)($item['tarif_per_hari'] ?? '')) ?>" placeholder="Contoh: 350000" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Status Awal</label>
                    <select name="status" class="ui-select">
                        <option value="Tersedia" <?= (($item['status'] ?? 'Tersedia') === 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Disewa" <?= (($item['status'] ?? '') === 'Disewa') ? 'selected' : '' ?>>Disewa</option>
                        <option value="Perawatan" <?= (($item['status'] ?? '') === 'Perawatan') ? 'selected' : '' ?>>Perawatan</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('kendaraan') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
            </div>
        </form>
    </div>
</div>
