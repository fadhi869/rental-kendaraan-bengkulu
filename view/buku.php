<?php
/**
 * View Buku / Kendaraan
 */
$viewMode = $viewMode ?? 'index';
?>

<?php if ($viewMode === 'create' || $viewMode === 'edit'): ?>
<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">
                <?= $viewMode === 'create' ? 'Tambah Kendaraan Baru' : 'Edit Data Kendaraan' ?>
            </h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Lengkapi formulir spesifikasi armada berikut.</p>
        </div>
        <a href="<?= base_url('buku.php') ?>" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card" style="padding:28px;">
        <form action="<?= base_url('buku.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="<?= $viewMode === 'create' ? 'store' : 'update' ?>">
            <?php if ($viewMode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= e($item['id'] ?? '') ?>">
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Nomor Plat / Polisi *</label>
                    <input type="text" name="plat_nomor" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Contoh: BD 1234 AY" value="<?= e($item['plat_nomor'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Kategori / Jenis *</label>
                    <select name="jenis_id" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" required>
                        <option value="">-- Pilih Jenis --</option>
                        <?php foreach ($jenisList as $j): ?>
                            <option value="<?= $j['id'] ?>" <?= ((int)($item['jenis_id'] ?? 0) === (int)$j['id']) ? 'selected' : '' ?>>
                                <?= e($j['nama_jenis']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Merek *</label>
                    <input type="text" name="merek" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Toyota, Honda, dll." value="<?= e($item['merek'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Model / Tipe *</label>
                    <input type="text" name="model" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Avanza, Brio, Innova..." value="<?= e($item['model'] ?? '') ?>" required>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Tahun Pembuatan *</label>
                    <input type="number" name="tahun" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" value="<?= e($item['tahun'] ?? date('Y')) ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Warna *</label>
                    <input type="text" name="warna" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Hitam, Putih, dll." value="<?= e($item['warna'] ?? '') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Tarif per Hari (Rp) *</label>
                    <input type="number" name="tarif_per_hari" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="350000" value="<?= e($item['tarif_per_hari'] ?? '') ?>" required>
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Status Awal</label>
                <select name="status" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
                    <option value="Tersedia" <?= (($item['status'] ?? 'Tersedia') === 'Tersedia') ? 'selected' : '' ?>>Tersedia (Ready to Rent)</option>
                    <option value="Disewa" <?= (($item['status'] ?? '') === 'Disewa') ? 'selected' : '' ?>>Disewa (Currently Rented)</option>
                    <option value="Perawatan" <?= (($item['status'] ?? '') === 'Perawatan') ? 'selected' : '' ?>>Perawatan (In Maintenance)</option>
                </select>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <a href="<?= base_url('buku.php') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kendaraan</button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- LIST KENDARAAN (INDEX) -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Manajemen Armada Kendaraan</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Kelola data armada, status ketersediaan, dan tarif harian.</p>
    </div>
    <a href="<?= base_url('buku.php?action=create') ?>" class="btn btn-primary">
        <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Tambah Kendaraan
    </a>
</div>

<div class="card" style="padding:20px;margin-bottom:20px;">
    <form action="<?= base_url('buku.php') ?>" method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
        <input type="text" name="q" placeholder="Cari plat, merek, atau model..." value="<?= e($_GET['q'] ?? '') ?>" class="form-input" style="flex:1;min-width:240px;padding:8px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
        <select name="status" class="form-input" style="padding:8px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
            <option value="">Semua Status</option>
            <option value="Tersedia" <?= (($_GET['status'] ?? '') === 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
            <option value="Disewa" <?= (($_GET['status'] ?? '') === 'Disewa') ? 'selected' : '' ?>>Disewa</option>
            <option value="Perawatan" <?= (($_GET['status'] ?? '') === 'Perawatan') ? 'selected' : '' ?>>Perawatan</option>
        </select>
        <button type="submit" class="btn btn-outline">Filter</button>
        <?php if (!empty($_GET['q']) || !empty($_GET['status'])): ?>
            <a href="<?= base_url('buku.php') ?>" class="btn btn-ghost" style="color:var(--text-secondary);">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">NO</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">PLAT NOMOR</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ARMADA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KATEGORI</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">TARIF / HARI</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">STATUS</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-secondary);">
                        Tidak ada data armada kendaraan yang sesuai filter.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($rows as $r): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);"><?= $no++ ?></td>
                        <td style="padding:14px 18px;font-size:13.5px;font-weight:700;color:var(--text-primary);letter-spacing:0.5px;">
                            <?= e($r['plat_nomor']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:13.5px;">
                            <div style="font-weight:600;color:var(--text-primary);"><?= e($r['merek']) ?> <?= e($r['model']) ?></div>
                            <div style="font-size:12px;color:var(--text-secondary);"><?= e($r['warna']) ?> &bull; <?= e($r['tahun']) ?></div>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);">
                            <?= e($r['nama_jenis']) ?>
                        </td>
                        <td style="padding:14px 18px;font-size:13.5px;font-weight:600;color:var(--text-primary);">
                            <?= rupiah($r['tarif_per_hari']) ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <?php
                            $stClass = match($r['status']) {
                                'Tersedia' => 'badge-success',
                                'Disewa' => 'badge-warning',
                                default => 'badge-danger',
                            };
                            ?>
                            <span class="badge <?= $stClass ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                                <?= e($r['status']) ?>
                            </span>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:8px;">
                                <a href="<?= base_url('buku.php?action=edit&id=' . $r['id']) ?>" class="btn btn-outline btn-sm" title="Edit">
                                    <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                </a>
                                <form action="<?= base_url('buku.php') ?>" method="POST" onsubmit="return confirm('Hapus unit <?= e($r['plat_nomor']) ?>?');" style="margin:0;">
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
