<div style="max-width:720px;margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="<?= base_url($module) ?>" style="font-size:13px;font-weight:600;color:#646163;">&larr; Kembali ke Daftar</a>
        <h1 style="margin:6px 0 0;font-size:22px;font-weight:800;"><?= e($heading) ?></h1>
    </div>

    <div class="ui-card" style="padding:28px;">
        <form action="<?= $action ?>" method="POST">
            <?= csrf_field() ?>

            <?php if ($module === 'kendaraan'): ?>
                <div class="grid-12" style="gap:16px;">
                    <div class="form-group col-6">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="ui-input" value="<?= e($item['plat_nomor'] ?? '') ?>" placeholder="BD 1234 XX" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Kategori Jenis</label>
                        <select name="jenis_id" class="ui-select" required>
                            <?php foreach ($jenisList as $j): ?>
                                <option value="<?= $j['id'] ?>" <?= ((int)($item['jenis_id'] ?? 0) === (int)$j['id']) ? 'selected' : '' ?>>
                                    <?= e($j['nama_jenis']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Merek</label>
                        <input type="text" name="merek" class="ui-input" value="<?= e($item['merek'] ?? '') ?>" placeholder="Toyota" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Model / Tipe</label>
                        <input type="text" name="model" class="ui-input" value="<?= e($item['model'] ?? '') ?>" placeholder="Avanza 1.5 G" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Tahun Pembuatan</label>
                        <input type="number" name="tahun" class="ui-input" value="<?= e((string)($item['tahun'] ?? date('Y'))) ?>" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Warna</label>
                        <input type="text" name="warna" class="ui-input" value="<?= e($item['warna'] ?? '') ?>" placeholder="Hitam Metalik" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Tarif Sewa / Hari (Rp)</label>
                        <input type="number" name="tarif_per_hari" class="ui-input" value="<?= e((string)($item['tarif_per_hari'] ?? '')) ?>" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Status Armada</label>
                        <select name="status" class="ui-select">
                            <?php foreach (['Tersedia', 'Disewa', 'Perawatan'] as $st): ?>
                                <option value="<?= $st ?>" <?= (($item['status'] ?? 'Tersedia') === $st) ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            <?php elseif ($module === 'jenis-kendaraan'): ?>
                <div class="form-group">
                    <label class="form-label">Nama Jenis Kendaraan</label>
                    <input type="text" name="nama_jenis" class="ui-input" value="<?= e($item['nama_jenis'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="ui-textarea"><?= e($item['deskripsi'] ?? '') ?></textarea>
                </div>

            <?php else: ?>
                <div class="grid-12" style="gap:16px;">
                    <div class="form-group col-6">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" class="ui-input" value="<?= e($item['nik'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Nomor SIM</label>
                        <input type="text" name="no_sim" class="ui-input" value="<?= e($item['no_sim'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="ui-input" value="<?= e($item['nama'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-6">
                        <label class="form-label">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="ui-input" value="<?= e($item['no_hp'] ?? '') ?>" required>
                    </div>
                    <div class="form-group col-12" style="grid-column:span 12;">
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" name="email" class="ui-input" value="<?= e($item['email'] ?? '') ?>">
                    </div>
                    <div class="form-group col-12" style="grid-column:span 12;">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="ui-textarea" required><?= e($item['alamat'] ?? '') ?></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                <a href="<?= base_url($module) ?>" class="btn btn-warm">Batal</a>
                <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
