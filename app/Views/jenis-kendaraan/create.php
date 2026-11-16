<div style="max-width:600px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('jenis-kendaraan') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Tambah Jenis Kendaraan</h1>
        <p class="page-desc">Masukkan nama kategori dan penjelasan armada.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('jenis-kendaraan') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Jenis <span class="required">*</span></label>
                <input type="text" name="nama_jenis" class="ui-input" placeholder="Contoh: MPV Keluarga / City Car" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="ui-textarea" placeholder="Keterangan singkat mengenai kapasitas atau peruntukan..."></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('jenis-kendaraan') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
