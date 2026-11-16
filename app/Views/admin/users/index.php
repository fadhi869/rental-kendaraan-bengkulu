<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;">
    <div>
        <h1 style="margin:0;font-size:24px;font-weight:800;">Manajemen Akun Pengguna</h1>
        <p style="margin:4px 0 0;color:#646163;">Kelola hak akses administrator dan petugas operasional.</p>
    </div>
    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-accent">
        <i data-lucide="user-plus" style="width:16px;height:16px;"></i>
        <span>Tambah User</span>
    </a>
</div>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td>
                        <strong><?= e($u['nama']) ?></strong>
                        <div style="font-size:12px;color:#646163;"><?= e($u['email'] ?? '-') ?></div>
                    </td>
                    <td><code><?= e($u['username']) ?></code></td>
                    <td style="text-transform:capitalize;font-weight:600;"><?= e($u['role']) ?></td>
                    <td><span class="badge badge-<?= e($u['status']) ?>"><span class="badge-dot"></span><?= e($u['status']) ?></span></td>
                    <td style="font-size:12.5px;color:#646163;"><?= e($u['last_login_at'] ?? 'Belum pernah') ?></td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?= base_url('admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-warm btn-sm">Edit</a>
                        <form action="<?= base_url('admin/users/' . $u['id'] . '/toggle-status') ?>" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-warm btn-sm">Toggle</button>
                        </form>
                        <form action="<?= base_url('admin/users/' . $u['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus user ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
