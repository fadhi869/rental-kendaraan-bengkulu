<div class="page-header">
    <div>
        <h1 class="page-title">Kelola Pengguna</h1>
        <p class="page-desc">Atur akun petugas operasional dan administrator sistem.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
            <i data-lucide="user-plus" style="width:15px;height:15px;"></i>
            <span>Tambah Pengguna</span>
        </a>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('admin/users') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;width:100%;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" placeholder="Cari nama, username, atau email...">
        <button type="submit" class="btn btn-dark">Cari</button>
        <?php if (!empty($q)): ?>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Peran (Role)</th>
                    <th>Status</th>
                    <th>Login Terakhir</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $icon = 'user-x';
                            $title = 'Pengguna Tidak Ditemukan';
                            $message = 'Tidak ada pengguna yang cocok dengan pencarian Anda.';
                            $actionUrl = base_url('admin/users/create');
                            $actionLabel = 'Tambah Pengguna Baru';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <strong style="font-size:13.5px;"><?= e($u['nama']) ?></strong>
                            <?php if (!empty($u['email'])): ?>
                                <div style="font-size:11.5px;color:var(--text-muted);"><?= e($u['email']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><code><?= e($u['username']) ?></code></td>
                        <td>
                            <span style="text-transform:capitalize;font-weight:600;">
                                <?= e($u['role']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?= e($u['status']) ?>">
                                <span class="badge-dot"></span><?= ($u['status'] === 'active') ? 'Aktif' : 'Nonaktif' ?>
                            </span>
                        </td>
                        <td style="font-size:12px;color:var(--text-secondary);"><?= e($u['last_login_at'] ?? 'Belum pernah') ?></td>
                        <td class="actions">
                            <a href="<?= base_url('admin/users/' . $u['id'] . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                            <form action="<?= base_url('admin/users/' . $u['id'] . '/toggle-status') ?>" method="POST" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-subtle btn-sm">
                                    <?= ($u['status'] === 'active') ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </button>
                            </form>
                            <form action="<?= base_url('admin/users/' . $u['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
