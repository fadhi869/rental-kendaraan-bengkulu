<div style="max-width:640px;margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="<?= base_url('admin/users') ?>" style="font-size:13px;font-weight:600;color:#646163;">&larr; Kembali</a>
        <h1 style="margin:6px 0 0;font-size:22px;font-weight:800;">Edit Akun Pengguna</h1>
    </div>
    <div class="ui-card" style="padding:28px;">
        <form action="<?= base_url('admin/users/' . $user['id']) ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="ui-input" value="<?= e($user['nama']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="ui-input" value="<?= e($user['username']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="ui-input" value="<?= e($user['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="ui-input">
            </div>
            <div class="grid-12" style="gap:16px;">
                <div class="form-group col-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="ui-select">
                        <option value="petugas" <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>Petugas</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="ui-select">
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-warm">Batal</a>
                <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
