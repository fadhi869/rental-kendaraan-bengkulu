<div style="max-width:600px;margin:0 auto;">
    <div style="margin-bottom:18px;">
        <a href="<?= base_url('admin/users') ?>" class="btn btn-outline btn-sm" style="margin-bottom:8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
            <span>Kembali ke Daftar</span>
        </a>
        <h1 class="page-title" style="margin-top:6px;">Edit Akun Pengguna</h1>
        <p class="page-desc">Perbarui data profil dan hak akses <?= e($user['nama']) ?>.</p>
    </div>

    <div class="card card-padded">
        <form action="<?= base_url('admin/users/' . $user['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" class="ui-input" value="<?= e($user['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <input type="text" name="username" class="ui-input" value="<?= e($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="ui-input" value="<?= e($user['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="ui-input" placeholder="Kosongkan jika tidak ingin mengubah password">
                <div class="form-hint">Biarkan kosong jika tetap menggunakan password saat ini.</div>
            </div>
            <div class="grid-12" style="gap:14px;">
                <div class="form-group col-6">
                    <label class="form-label">Peran (Role)</label>
                    <select name="role" class="ui-select">
                        <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Status Akun</label>
                    <select name="status" class="ui-select">
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
