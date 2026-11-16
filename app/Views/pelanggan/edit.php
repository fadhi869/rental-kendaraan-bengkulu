<div style="max-width:680px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('pelanggan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Edit Data Pelanggan</h1>
        <p class="page-desc">Perbarui data identitas dan kontak <?= e($item['nama']) ?>.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('pelanggan/' . $item['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="grid-12" style="gap:14px;">
                <div class="form-group col-6">
                    <label class="form-label">NIK (16 Digit) <span class="required">*</span></label>
                    <input type="text" name="nik" maxlength="16" class="ui-input" value="<?= e($item['nik'] ?? '') ?>" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Nomor SIM <span class="required">*</span></label>
                    <input type="text" name="no_sim" class="ui-input" value="<?= e($item['no_sim'] ?? '') ?>" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama" class="ui-input" value="<?= e($item['nama'] ?? '') ?>" required>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Nomor HP / WhatsApp <span class="required">*</span></label>
                    <input type="text" name="no_hp" class="ui-input" value="<?= e($item['no_hp'] ?? '') ?>" required>
                </div>
                <div class="form-group col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="ui-input" value="<?= e($item['email'] ?? '') ?>">
                </div>
                <div class="form-group col-12">
                    <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="alamat" rows="3" class="ui-textarea" required><?= e($item['alamat'] ?? '') ?></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('pelanggan') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
