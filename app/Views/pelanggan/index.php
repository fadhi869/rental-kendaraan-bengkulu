<div class="page-header">
    <div>
        <h1 class="page-title">Pelanggan</h1>
        <p class="page-desc">Daftar pelanggan dan kontak penyewa.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('pelanggan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Tambah Pelanggan</span>
        </a>
    </div>
</div>

<div class="filter-toolbar">
    <form method="GET" action="<?= base_url('pelanggan') ?>" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;width:100%;">
        <input type="text" name="q" value="<?= e($q ?? '') ?>" class="ui-input" placeholder="Cari nama, NIK, no HP, atau SIM...">
        <button type="submit" class="btn btn-dark">Cari</button>
        <?php if (!empty($q)): ?>
            <a href="<?= base_url('pelanggan') ?>" class="btn btn-outline">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>NIK</th>
                    <th>No. SIM</th>
                    <th>Kontak / HP</th>
                    <th>Alamat</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            $icon = 'users';
                            $title = 'Data Pelanggan Tidak Ditemukan';
                            $message = 'Tidak ada pelanggan yang cocok dengan pencarian atau belum ada data tersimpan.';
                            $actionUrl = base_url('pelanggan/create');
                            $actionLabel = 'Tambah Pelanggan Baru';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td>
                            <strong style="font-size:13.5px;"><?= e($r['nama']) ?></strong>
                            <?php if (!empty($r['email'])): ?>
                                <div style="font-size:11.5px;color:var(--text-muted);"><?= e($r['email']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td><code><?= e($r['nik']) ?></code></td>
                        <td><?= e($r['no_sim']) ?></td>
                        <td><strong><?= e($r['no_hp']) ?></strong></td>
                        <td style="color:var(--text-secondary);max-width:240px;"><?= e($r['alamat']) ?></td>
                        <td class="actions">
                            <a href="<?= base_url('pelanggan/' . $r['id'] . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                            <form action="<?= base_url('pelanggan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
