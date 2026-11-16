<div class="page-header">
    <div>
        <h1 class="page-title">Jenis Kendaraan</h1>
        <p class="page-desc">Kelola kategori tipe kendaraan armada.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('jenis-kendaraan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Tambah Jenis</span>
        </a>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Nama Jenis</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Unit</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="4">
                            <?php
                            $icon = 'tags';
                            $title = 'Belum Ada Jenis Kendaraan';
                            $message = 'Silakan tambahkan jenis kendaraan seperti Mobil MPV, SUV, City Car, atau Motor.';
                            $actionUrl = base_url('jenis-kendaraan/create');
                            $actionLabel = 'Tambah Jenis Baru';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><strong style="font-size:13.5px;"><?= e($r['nama_jenis']) ?></strong></td>
                        <td style="color:var(--text-secondary);max-width:440px;"><?= e($r['deskripsi'] ?: '-') ?></td>
                        <td><span class="badge badge-tersedia"><?= (int)$r['jumlah_unit'] ?> Unit</span></td>
                        <td class="actions">
                            <a href="<?= base_url('jenis-kendaraan/' . $r['id'] . '/edit') ?>" class="btn btn-outline btn-sm">Edit</a>
                            <form action="<?= base_url('jenis-kendaraan/' . $r['id'] . '/delete') ?>" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis kendaraan ini?')">
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
