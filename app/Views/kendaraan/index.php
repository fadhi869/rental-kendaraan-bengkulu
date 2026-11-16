<div class="page-header">
    <div>
        <h1 class="page-title">Data Kendaraan</h1>
        <p class="page-desc">Kelola unit armada mobil dan motor yang siap disewakan.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('kendaraan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Tambah Kendaraan</span>
        </a>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('kendaraan') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;width:100%;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" placeholder="Cari plat, merek, atau model...">
        <select name="status" class="ui-select">
            <option value="">Semua Status</option>
            <option value="Tersedia" <?= ($status ?? '') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
            <option value="Disewa" <?= ($status ?? '') === 'Disewa' ? 'selected' : '' ?>>Disewa</option>
            <option value="Perawatan" <?= ($status ?? '') === 'Perawatan' ? 'selected' : '' ?>>Perawatan</option>
        </select>
        <button type="submit" class="btn btn-dark">Cari</button>
        <?php if (!empty($q) || !empty($status)): ?>
            <a href="<?= base_url('kendaraan') ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Plat Nomor</th>
                    <th>Kendaraan</th>
                    <th>Jenis</th>
                    <th>Tahun & Warna</th>
                    <th>Tarif / Hari</th>
                    <th>Status</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="7">
                            <?php
                            $icon = 'car';
                            $title = 'Kendaraan Tidak Ditemukan';
                            $message = 'Tidak ada kendaraan yang cocok dengan filter atau belum ada kendaraan yang didaftarkan.';
                            $actionUrl = base_url('kendaraan/create');
                            $actionLabel = 'Tambah Kendaraan Baru';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><span class="plate-tag"><?= e($r['plat_nomor']) ?></span></td>
                        <td>
                            <strong style="font-size:13.5px;"><?= e($r['merek'] . ' ' . $r['model']) ?></strong>
                        </td>
                        <td><?= e($r['nama_jenis']) ?></td>
                        <td style="color:var(--text-secondary);"><?= e((string)$r['tahun']) ?> &bull; <?= e($r['warna']) ?></td>
                        <td><strong><?= rupiah($r['tarif_per_hari']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <span class="badge-dot"></span><?= e($r['status']) ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="<?= base_url('kendaraan/' . $r['id'] . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                            <form action="<?= base_url('kendaraan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')">
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
