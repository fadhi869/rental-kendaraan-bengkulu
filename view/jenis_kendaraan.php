<?php
/**
 * View Jenis Kendaraan
 */
$viewMode = $viewMode ?? 'index';
?>

<?php if ($viewMode === 'create' || $viewMode === 'edit'): ?>
<div style="max-width:650px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">
                <?= $viewMode === 'create' ? 'Tambah Jenis Kendaraan' : 'Edit Jenis Kendaraan' ?>
            </h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Atur pengelompokan jenis atau tipe armada.</p>
        </div>
        <a href="<?= base_url('jenis_kendaraan.php') ?>" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card" style="padding:28px;">
        <form action="<?= base_url('jenis_kendaraan.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="<?= $viewMode === 'create' ? 'store' : 'update' ?>">
            <?php if ($viewMode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
            <?php endif; ?>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nama Jenis Kendaraan *</label>
                <input type="text" name="nama_jenis" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Contoh: MPV, SUV, Sedan, City Car, Hatchback..." value="<?= e($item['nama_jenis'] ?? '') ?>" required>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Deskripsi / Keterangan</label>
                <textarea name="deskripsi" rows="3" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Keterangan singkat mengenai karakteristik jenis armada..."><?= e($item['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <a href="<?= base_url('jenis_kendaraan.php') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Jenis Kendaraan</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- LIST JENIS KENDARAAN (INDEX) -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Jenis Kendaraan</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Kelola kategori jenis armada rental kendaraan.</p>
    </div>
    <a href="<?= base_url('jenis_kendaraan.php?action=create') ?>" class="btn btn-primary">
        <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Tambah Jenis
    </a>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NO</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NAMA JENIS</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">DESKRIPSI</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">JUMLAH UNIT</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--text-secondary);">
                        Belum ada jenis kendaraan yang terdaftar.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($rows as $r): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);"><?= $no++ ?></td>
                        <td style="padding:14px 18px;font-size:13.5px;font-weight:700;color:var(--text-primary);">
                            <?= e($r['nama_jenis']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);">
                            <?= e($r['deskripsi'] ?: '-') ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <span class="badge badge-info" style="padding:4px 10px;border-radius:12px;font-size:12px;font-weight:600;">
                                <?= (int)($r['jumlah_unit'] ?? 0) ?> Unit
                            </span>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:8px;">
                                <a href="<?= base_url('jenis_kendaraan.php?action=edit&id=' . $r['id']) ?>" class="btn btn-outline btn-sm" title="Edit">
                                    <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                </a>
                                <form action="<?= base_url('jenis_kendaraan.php') ?>" method="POST" onsubmit="return confirm('Hapus jenis <?= e($r['nama_jenis']) ?>?');" style="margin:0;">
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
