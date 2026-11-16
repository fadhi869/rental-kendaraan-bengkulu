<div style="max-width:640px;margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="<?= base_url('admin/users') ?>" style="font-size:13px;font-weight:600;color:#646163;">&larr; Kembali</a>
        <h1 style="margin:6px 0 0;font-size:22px;font-weight:800;">Tambah Pengguna Baru</h1>
    </div>
    <div class="ui-card" style="padding:28px;">
        <form action="<?= base_url('admin/users') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="ui-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="ui-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="ui-input">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="ui-input" required>
            </div>
            <div class="grid-12" style="gap:16px;">
                <div class="form-group col-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="ui-select">
                        <option value="petugas">Petugas</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div class="form-group col-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="ui-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-warm">Batal</a>
                <button type="submit" class="btn btn-dark">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>
