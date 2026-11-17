<?php
/**
 * View Anggota / Pelanggan
 */
$viewMode = $viewMode ?? 'index';
?>

<?php if ($viewMode === 'create' || $viewMode === 'edit'): ?>
<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">
                <?= $viewMode === 'create' ? 'Tambah Anggota Baru' : 'Edit Data Anggota' ?>
            </h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Lengkapi identitas pelanggan/anggota penyewa.</p>
        </div>
        <a href="<?= base_url('anggota.php') ?>" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card" style="padding:28px;">
        <form action="<?= base_url('anggota.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="<?= $viewMode === 'create' ? 'store' : 'update' ?>">
            <?php if ($viewMode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nomor KTP / NIK *</label>
                    <input type="text" name="nik" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="16 digit NIK" value="<?= e($item['nik'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Nama sesuai identitas" value="<?= e($item['nama'] ?? '') ?>" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nomor HP / WhatsApp *</label>
                    <input type="text" name="no_hp" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="08xxxxxxxxxx" value="<?= e($item['no_hp'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nomor SIM *</label>
                    <input type="text" name="no_sim" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="SIM A / Umum" value="<?= e($item['no_sim'] ?? '') ?>" required>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Alamat Email</label>
                <input type="email" name="email" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="email@contoh.com" value="<?= e($item['email'] ?? '') ?>">
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Alamat Domisili Lengkap *</label>
                <textarea name="alamat" rows="3" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" required><?= e($item['alamat'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <a href="<?= base_url('anggota.php') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- LIST ANGGOTA (INDEX) -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Data Anggota & Pelanggan</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Daftar penyewa terdaftar yang memiliki izin peminjaman armada.</p>
    </div>
    <a href="<?= base_url('anggota.php?action=create') ?>" class="btn btn-primary">
        <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Tambah Anggota
    </a>
</div>

<div class="card" style="padding:20px;margin-bottom:20px;">
    <form action="<?= base_url('anggota.php') ?>" method="GET" style="display:flex;gap:12px;align-items:center;">
        <input type="text" name="q" placeholder="Cari nama, NIK, SIM, atau nomor HP..." value="<?= e($_GET['q'] ?? '') ?>" class="form-input" style="flex:1;padding:8px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
        <button type="submit" class="btn btn-outline">Cari</button>
        <?php if (!empty($_GET['q'])): ?>
            <a href="<?= base_url('anggota.php') ?>" class="btn btn-ghost" style="color:var(--text-secondary);">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NO</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NAMA LENGKAP</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NIK / KTP</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KONTAK</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NO SIM</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ALAMAT</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-secondary);">
                        Belum ada data anggota yang terdaftar.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($rows as $r): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);"><?= $no++ ?></td>
                        <td style="padding:14px 18px;font-size:13.5px;font-weight:600;color:var(--text-primary);">
                            <?= e($r['nama']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);letter-spacing:0.3px;">
                            <?= e($r['nik']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);">
                            <div><?= e($r['no_hp']) ?></div>
                            <div style="font-size:11.5px;color:var(--text-muted);"><?= e($r['email'] ?? '-') ?></div>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);">
                            <?= e($r['no_sim']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:12.5px;color:var(--text-secondary);max-width:200px;">
                            <?= e($r['alamat']) ?>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:8px;">
                                <a href="<?= base_url('anggota.php?action=edit&id=' . $r['id']) ?>" class="btn btn-outline btn-sm" title="Edit">
                                    <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                </a>
                                <form action="<?= base_url('anggota.php') ?>" method="POST" onsubmit="return confirm('Hapus data anggota <?= e($r['nama']) ?>?');" style="margin:0;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
