<?php
/**
 * View Admin / Manajemen Pengguna & Log Aktivitas
 */
$viewMode = $viewMode ?? 'index';
?>

<?php if ($viewMode === 'create' || $viewMode === 'edit'): ?>
<div style="max-width:700px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">
                <?= $viewMode === 'create' ? 'Tambah Akun Pengguna' : 'Edit Akun Pengguna' ?>
            </h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Atur hak akses login petugas dan administrator.</p>
        </div>
        <a href="<?= base_url('admin.php') ?>" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card" style="padding:28px;">
        <form action="<?= base_url('admin.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="<?= $viewMode === 'create' ? 'store' : 'update' ?>">
            <?php if ($viewMode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Nama petugas/admin" value="<?= e($item['nama'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Username Login *</label>
                    <input type="text" name="username" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Username unik" value="<?= e($item['username'] ?? '') ?>" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">
                        Password <?= $viewMode === 'edit' ? '(Kosongkan jika tidak diubah)' : '*' ?>
                    </label>
                    <input type="password" name="password" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Minimal 6 karakter" <?= $viewMode === 'create' ? 'required' : '' ?>>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Alamat Email</label>
                    <input type="email" name="email" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="user@armadabengkulu.com" value="<?= e($item['email'] ?? '') ?>">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Role / Hak Akses</label>
                    <select name="role" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
                        <option value="petugas" <?= (($item['role'] ?? 'petugas') === 'petugas') ? 'selected' : '' ?>>Petugas Operasional</option>
                        <option value="admin" <?= (($item['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Administrator Utama</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Status Akun</label>
                    <select name="status" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
                        <option value="active" <?= (($item['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= (($item['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <a href="<?= base_url('admin.php') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- LIST USER & AKTIVITAS (INDEX) -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Manajemen Pengguna & Log Sistem</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Kelola hak akses petugas loket dan pantau jejak audit aktivitas sistem.</p>
    </div>
    <a href="<?= base_url('admin.php?action=create') ?>" class="btn btn-primary">
        <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Tambah Pengguna
    </a>
</div>

<div class="card" style="margin-bottom:24px;overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:var(--bg-warm);font-weight:700;font-size:14px;">
        Daftar Akun Pengguna Terdaftar
    </div>
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NO</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NAMA & USERNAME</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">EMAIL</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ROLE</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">STATUS</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($users as $u): ?>
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:12px 18px;font-size:13px;color:var(--text-secondary);"><?= $no++ ?></td>
                    <td style="padding:12px 18px;">
                        <div style="font-weight:700;color:var(--text-primary);"><?= e($u['nama']) ?></div>
                        <div style="font-size:12px;color:var(--text-secondary);">@<?= e($u['username']) ?></div>
                    </td>
                    <td style="padding:12px 18px;font-size:13px;color:var(--text-secondary);"><?= e($u['email'] ?: '-') ?></td>
                    <td style="padding:12px 18px;">
                        <span class="badge <?= $u['role']==='admin'?'badge-primary':'badge-ghost' ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                            <?= strtoupper($u['role']) ?>
                        </span>
                    </td>
                    <td style="padding:12px 18px;">
                        <span class="badge <?= $u['status']==='active'?'badge-success':'badge-danger' ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                            <?= $u['status']==='active'?'Aktif':'Nonaktif' ?>
                        </span>
                    </td>
                    <td style="padding:12px 18px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <form action="<?= base_url('admin.php') ?>" method="POST" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="toggle-status">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-outline btn-sm" title="Ubah Status">
                                    <i data-lucide="power" style="width:14px;height:14px;"></i>
                                </button>
                            </form>
                            <a href="<?= base_url('admin.php?action=edit&id=' . $u['id']) ?>" class="btn btn-outline btn-sm" title="Edit">
                                <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                            </a>
                            <form action="<?= base_url('admin.php') ?>" method="POST" onsubmit="return confirm('Nonaktifkan akun <?= e($u['username']) ?>?');" style="margin:0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);background:var(--bg-warm);font-weight:700;font-size:14px;">
        Jejak Audit Aktivitas Terakhir (50 Entri)
    </div>
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:10px 16px;font-size:11.5px;font-weight:700;color:var(--text-secondary);">WAKTU</th>
                    <th style="padding:10px 16px;font-size:11.5px;font-weight:700;color:var(--text-secondary);">USER</th>
                    <th style="padding:10px 16px;font-size:11.5px;font-weight:700;color:var(--text-secondary);">MODUL</th>
                    <th style="padding:10px 16px;font-size:11.5px;font-weight:700;color:var(--text-secondary);">AKSI</th>
                    <th style="padding:10px 16px;font-size:11.5px;font-weight:700;color:var(--text-secondary);">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $l): ?>
                <tr style="border-bottom:1px solid var(--border);font-size:12px;">
                    <td style="padding:10px 16px;color:var(--text-secondary);white-space:nowrap;"><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?></td>
                    <td style="padding:10px 16px;font-weight:600;"><?= e($l['user_nama'] ?? 'Sistem') ?></td>
                    <td style="padding:10px 16px;font-family:monospace;"><?= e($l['module']) ?></td>
                    <td style="padding:10px 16px;"><span class="badge badge-ghost"><?= e($l['action']) ?></span></td>
                    <td style="padding:10px 16px;color:var(--text-primary);"><?= e($l['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
