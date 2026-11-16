<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:22px;">
    <div>
        <h1 style="margin:0;font-size:24px;font-weight:800;"><?= e($heading) ?></h1>
        <p style="margin:4px 0 0;color:#646163;"><?= e($sub) ?></p>
    </div>
    <a href="<?= base_url($module . '/create') ?>" class="btn btn-accent">
        <i data-lucide="plus" style="width:16px;height:16px;"></i>
        <span>Tambah Data</span>
    </a>
</div>

<?php if ($module !== 'jenis-kendaraan'): ?>
<div class="ui-card" style="padding:16px;margin-bottom:20px;">
    <form method="GET" action="<?= base_url($module) ?>" style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" style="max-width:320px;" placeholder="Cari kata kunci...">
        <?php if ($module === 'kendaraan'): ?>
        <select name="status" class="ui-select" style="max-width:190px;">
            <option value="">Semua Status</option>
            <option value="Tersedia" <?= ($status ?? '') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
            <option value="Disewa" <?= ($status ?? '') === 'Disewa' ? 'selected' : '' ?>>Disewa</option>
            <option value="Perawatan" <?= ($status ?? '') === 'Perawatan' ? 'selected' : '' ?>>Perawatan</option>
        </select>
        <?php endif; ?>
        <button type="submit" class="btn btn-dark">Filter</button>
        <a href="<?= base_url($module) ?>" class="btn btn-warm">Reset</a>
    </form>
</div>
<?php endif; ?>

<div class="ui-card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <?php if ($module === 'kendaraan'): ?>
            <thead>
                <tr>
                    <th>Plat Nomor</th>
                    <th>Kendaraan</th>
                    <th>Kategori</th>
                    <th>Tahun / Warna</th>
                    <th>Tarif / Hari</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><span style="background:#F8F7F3;border:1px solid #E5E9EC;padding:4px 10px;border-radius:8px;font-weight:700;"><?= e($r['plat_nomor']) ?></span></td>
                    <td><strong><?= e($r['merek'] . ' ' . $r['model']) ?></strong></td>
                    <td><?= e($r['nama_jenis']) ?></td>
                    <td style="color:#646163;"><?= e((string)$r['tahun']) ?> &bull; <?= e($r['warna']) ?></td>
                    <td><strong><?= rupiah($r['tarif_per_hari']) ?></strong></td>
                    <td><span class="badge badge-<?= strtolower($r['status']) ?>"><span class="badge-dot"></span><?= e($r['status']) ?></span></td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?= base_url('kendaraan/' . $r['id'] . '/edit') ?>" class="btn btn-warm btn-sm">Edit</a>
                        <form action="<?= base_url('kendaraan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus unit ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <?php elseif ($module === 'jenis-kendaraan'): ?>
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Unit</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><strong><?= e($r['nama_jenis']) ?></strong></td>
                    <td style="color:#646163;max-width:460px;"><?= e($r['deskripsi']) ?></td>
                    <td><span class="badge badge-tersedia"><?= (int)$r['jumlah_unit'] ?> Unit</span></td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?= base_url('jenis-kendaraan/' . $r['id'] . '/edit') ?>" class="btn btn-warm btn-sm">Edit</a>
                        <form action="<?= base_url('jenis-kendaraan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

            <?php else: ?>
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>No. SIM</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td>
                        <strong><?= e($r['nama']) ?></strong>
                        <div style="font-size:12px;color:#646163;"><?= e($r['email'] ?? '-') ?></div>
                    </td>
                    <td><code><?= e($r['nik']) ?></code></td>
                    <td><?= e($r['no_sim']) ?></td>
                    <td><?= e($r['no_hp']) ?></td>
                    <td style="max-width:260px;color:#646163;"><?= e($r['alamat']) ?></td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="<?= base_url('pelanggan/' . $r['id'] . '/edit') ?>" class="btn btn-warm btn-sm">Edit</a>
                        <form action="<?= base_url('pelanggan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Hapus pelanggan ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger-soft btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>
