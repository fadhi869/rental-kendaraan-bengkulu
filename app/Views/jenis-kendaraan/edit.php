<div style="max-width:600px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('jenis-kendaraan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Edit Jenis Kendaraan</h1>
        <p class="page-desc">Perbarui nama dan deskripsi kategori kendaraan.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('jenis-kendaraan/' . $item['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Jenis <span class="required">*</span></label>
                <input type="text" name="nama_jenis" class="ui-input" value="<?= e($item['nama_jenis']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="ui-textarea"><?= e($item['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('jenis-kendaraan') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
