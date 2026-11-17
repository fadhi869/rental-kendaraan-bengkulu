<?php
/**
 * View Peminjaman / Penyewaan & Laporan
 */
$viewMode = $viewMode ?? 'index';
?>

<?php if ($viewMode === 'create'): ?>
<!-- FORM BUAT PEMINJAMAN BARU -->
<div style="max-width:850px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Buat Transaksi Peminjaman Baru</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Catat peminjaman/sewa armada untuk anggota penyewa.</p>
        </div>
        <a href="<?= base_url('peminjaman.php') ?>" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card" style="padding:28px;">
        <form action="<?= base_url('peminjaman.php') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="store">

            <div style="padding:14px;background:var(--bg-warm);border:1px solid var(--border);border-radius:var(--radius-md);margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:12.5px;font-weight:600;color:var(--text-secondary);">Kode Transaksi Otomatis:</span>
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);font-family:monospace;"><?= e($kodeTransaksi) ?></span>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Anggota / Pelanggan *</label>
                    <select name="pelanggan_id" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" required>
                        <option value="">-- Pilih Anggota Penyewa --</option>
                        <?php foreach ($customers as $c): ?>
                            <option value="<?= $c['id'] ?>">
                                <?= e($c['nama']) ?> &bull; NIK: <?= e($c['nik']) ?> (<?= e($c['no_hp']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Armada Kendaraan Tersedia *</label>
                    <select name="kendaraan_id" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" required>
                        <option value="">-- Pilih Armada Siap Pakai --</option>
                        <?php foreach ($availableUnits as $u): ?>
                            <option value="<?= $u['id'] ?>">
                                <?= e($u['plat_nomor']) ?> - <?= e($u['merek']) ?> <?= e($u['model']) ?> (<?= rupiah($u['tarif_per_hari']) ?>/hari)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Tanggal Mulai Sewa *</label>
                    <input type="date" name="tgl_sewa" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div>
                    <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Rencana Tanggal Kembali *</label>
                    <input type="date" name="tgl_kembali_rencana" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Status Awal Transaksi</label>
                <select name="status" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);">
                    <option value="Berjalan">Berjalan (Kunci diserahkan hari ini)</option>
                    <option value="Dipesan">Dipesan (Booking terlebih dahulu)</option>
                </select>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;font-size:12.5px;font-weight:600;margin-bottom:6px;">Catatan / Keperluan Sewa</label>
                <textarea name="catatan" rows="3" class="form-input" style="width:100%;padding:10px 14px;border:1px solid var(--border);border-radius:var(--radius-md);" placeholder="Tujuan perjalanan, kondisi awal kendaraan, dll."></textarea>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;">
                <a href="<?= base_url('peminjaman.php') ?>" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Konfirmasi Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<?php elseif ($viewMode === 'show'): ?>
<!-- DETAIL TRANSAKSI / INVOICE -->
<div style="max-width:850px;margin:0 auto;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Detail Transaksi Peminjaman</h1>
            <p style="font-size:13px;color:var(--text-secondary);margin:0;">Informasi lengkap peminjaman dan bukti sewa.</p>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="javascript:window.print()" class="btn btn-outline"><i data-lucide="printer" style="width:15px;height:15px;margin-right:6px;"></i> Cetak Bukti</a>
            <a href="<?= base_url('peminjaman.php') ?>" class="btn btn-outline">Kembali</a>
        </div>
    </div>

    <div class="card" style="padding:32px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px solid var(--border);padding-bottom:20px;margin-bottom:24px;">
            <div>
                <div style="font-size:20px;font-weight:800;color:var(--text-primary);letter-spacing:0.5px;"><?= e($item['kode_transaksi']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;">Dibuat oleh: <?= e($item['petugas_nama'] ?? 'Petugas') ?></div>
            </div>
            <div>
                <span class="badge badge-primary" style="padding:6px 14px;border-radius:14px;font-size:13px;font-weight:700;">
                    <?= e($item['status']) ?>
                </span>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-bottom:28px;">
            <div>
                <h3 style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">Informasi Anggota / Penyewa</h3>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);"><?= e($item['nama_pelanggan']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;">NIK: <?= e($item['nik']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);">No. HP: <?= e($item['no_hp']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);">No. SIM: <?= e($item['no_sim']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);margin-top:4px;"><?= e($item['alamat']) ?></div>
            </div>

            <div>
                <h3 style="font-size:13px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:0.5px;margin-bottom:12px;">Unit Kendaraan</h3>
                <div style="font-size:15px;font-weight:700;color:var(--text-primary);"><?= e($item['merek']) ?> <?= e($item['model']) ?> (<?= e($item['nama_jenis']) ?>)</div>
                <div style="font-size:14px;font-weight:800;color:var(--primary);margin-top:4px;"><?= e($item['plat_nomor']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);">Warna: <?= e($item['warna']) ?> &bull; Tahun: <?= e($item['tahun']) ?></div>
                <div style="font-size:13px;color:var(--text-secondary);">Tarif Sewa: <?= rupiah($item['tarif_saat_sewa']) ?> / hari</div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:20px;margin-bottom:24px;">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
                <div style="padding:16px;background:var(--bg-warm);border-radius:var(--radius-md);">
                    <div style="font-size:11.5px;font-weight:600;color:var(--text-secondary);">TGL MULAI SEWA</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:4px;"><?= tgl_indo($item['tgl_sewa']) ?></div>
                </div>
                <div style="padding:16px;background:var(--bg-warm);border-radius:var(--radius-md);">
                    <div style="font-size:11.5px;font-weight:600;color:var(--text-secondary);">RENCANA KEMBALI</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:4px;"><?= tgl_indo($item['tgl_kembali_rencana']) ?></div>
                </div>
                <div style="padding:16px;background:var(--bg-warm);border-radius:var(--radius-md);">
                    <div style="font-size:11.5px;font-weight:600;color:var(--text-secondary);">DURASI SEWA</div>
                    <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-top:4px;"><?= (int)$item['jumlah_hari'] ?> Hari</div>
                </div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:20px;display:flex;justify-content:space-between;align-items:center;">
            <div>
                <span style="font-size:13px;color:var(--text-secondary);">Catatan: </span>
                <span style="font-size:13px;font-weight:600;"><?= e($item['catatan'] ?: 'Tidak ada catatan.') ?></span>
            </div>
            <div style="text-align:right;">
                <div style="font-size:12px;color:var(--text-secondary);">TOTAL BIAYA SEWA</div>
                <div style="font-size:22px;font-weight:800;color:var(--primary);"><?= rupiah($item['total_biaya']) ?></div>
                <?php if ((float)($item['denda'] ?? 0) > 0): ?>
                    <div style="font-size:12px;color:var(--danger);font-weight:600;">+ Denda: <?= rupiah($item['denda']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php elseif ($viewMode === 'laporan'): ?>
<!-- LAPORAN REKAPITULASI KEUANGAN -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Laporan Pendapatan Rental</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Rekapitulasi keuangan dari seluruh transaksi sewa armada.</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="javascript:window.print()" class="btn btn-outline"><i data-lucide="printer" style="width:15px;height:15px;margin-right:6px;"></i> Cetak Laporan</a>
        <a href="<?= base_url('peminjaman.php') ?>" class="btn btn-outline">Kembali ke Transaksi</a>
    </div>
</div>

<div class="card" style="padding:20px;margin-bottom:24px;">
    <form action="<?= base_url('peminjaman.php') ?>" method="GET" style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;">
        <input type="hidden" name="action" value="laporan">
        <div style="display:flex;align-items:center;gap:8px;">
            <label style="font-size:13px;font-weight:600;">Periode:</label>
            <input type="date" name="dari" value="<?= e($report['dari']) ?>" class="form-input" style="padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-md);">
            <span>s/d</span>
            <input type="date" name="sampai" value="<?= e($report['sampai']) ?>" class="form-input" style="padding:8px 12px;border:1px solid var(--border);border-radius:var(--radius-md);">
        </div>
        <button type="submit" class="btn btn-primary">Filter Periode</button>
    </form>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Pendapatan Sewa</div>
        <div style="font-size:22px;font-weight:800;color:var(--primary);margin-top:6px;"><?= rupiah($report['total_sewa']) ?></div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Denda Keterlambatan</div>
        <div style="font-size:22px;font-weight:800;color:var(--danger);margin-top:6px;"><?= rupiah($report['total_denda']) ?></div>
    </div>
    <div class="card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Grand Total Penerimaan</div>
        <div style="font-size:22px;font-weight:800;color:var(--success);margin-top:6px;"><?= rupiah($report['grand_total']) ?></div>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">KODE</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">ANGGOTA</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">ARMADA</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">TGL SEWA</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">SEWA (RP)</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">DENDA (RP)</th>
                    <th style="padding:12px 16px;font-size:12px;font-weight:700;">TOTAL (RP)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($report['rows'])): ?>
                <tr><td colspan="7" style="padding:32px;text-align:center;color:var(--text-secondary);">Tidak ada transaksi dalam periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($report['rows'] as $row): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:12px 16px;font-family:monospace;font-weight:700;"><?= e($row['kode_transaksi']) ?></td>
                        <td style="padding:12px 16px;font-weight:600;"><?= e($row['nama_pelanggan']) ?></td>
                        <td style="padding:12px 16px;"><?= e($row['plat_nomor']) ?> (<?= e($row['merek']) ?>)</td>
                        <td style="padding:12px 16px;"><?= tgl_indo($row['tgl_sewa']) ?></td>
                        <td style="padding:12px 16px;"><?= rupiah($row['total_biaya']) ?></td>
                        <td style="padding:12px 16px;color:var(--danger);"><?= rupiah($row['denda']) ?></td>
                        <td style="padding:12px 16px;font-weight:700;color:var(--primary);"><?= rupiah($row['total_biaya'] + $row['denda']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<!-- LIST TRANSAKSI PEMINJAMAN (INDEX) -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Daftar Transaksi Peminjaman</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Kelola alur penyewaan, serah terima kunci, hingga pembatalan sewa.</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?= base_url('peminjaman.php?action=laporan') ?>" class="btn btn-outline">
            <i data-lucide="bar-chart-2" style="width:16px;height:16px;margin-right:6px;"></i> Laporan Keuangan
        </a>
        <a href="<?= base_url('peminjaman.php?action=create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:16px;height:16px;margin-right:6px;"></i> Tambah Peminjaman
        </a>
    </div>
</div>

<div class="card" style="padding:16px 20px;margin-bottom:20px;display:flex;gap:12px;align-items:center;">
    <span style="font-size:13px;font-weight:600;color:var(--text-secondary);">Filter Status:</span>
    <a href="<?= base_url('peminjaman.php') ?>" class="badge <?= empty($_GET['status']) ? 'badge-primary' : 'badge-ghost' ?>" style="padding:6px 12px;border-radius:12px;text-decoration:none;">Semua</a>
    <a href="<?= base_url('peminjaman.php?status=Berjalan') ?>" class="badge <?= (($_GET['status'] ?? '') === 'Berjalan') ? 'badge-primary' : 'badge-ghost' ?>" style="padding:6px 12px;border-radius:12px;text-decoration:none;">Berjalan</a>
    <a href="<?= base_url('peminjaman.php?status=Dipesan') ?>" class="badge <?= (($_GET['status'] ?? '') === 'Dipesan') ? 'badge-primary' : 'badge-ghost' ?>" style="padding:6px 12px;border-radius:12px;text-decoration:none;">Dipesan</a>
    <a href="<?= base_url('peminjaman.php?status=Selesai') ?>" class="badge <?= (($_GET['status'] ?? '') === 'Selesai') ? 'badge-primary' : 'badge-ghost' ?>" style="padding:6px 12px;border-radius:12px;text-decoration:none;">Selesai</a>
    <a href="<?= base_url('peminjaman.php?status=Dibatalkan') ?>" class="badge <?= (($_GET['status'] ?? '') === 'Dibatalkan') ? 'badge-primary' : 'badge-ghost' ?>" style="padding:6px 12px;border-radius:12px;text-decoration:none;">Dibatalkan</a>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KODE</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ANGGOTA PENYEWA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ARMADA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">PERIODE</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">TOTAL BIAYA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">STATUS</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-secondary);">
                        Tidak ada transaksi peminjaman pada filter ini.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 18px;font-family:monospace;font-weight:700;color:var(--text-primary);">
                            <a href="<?= base_url('peminjaman.php?action=show&id=' . $r['id']) ?>" style="color:var(--primary);text-decoration:none;">
                                <?= e($r['kode_transaksi']) ?>
                            </a>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:var(--text-primary);"><?= e($r['nama_pelanggan']) ?></div>
                            <div style="font-size:12px;color:var(--text-secondary);"><?= e($r['no_hp']) ?></div>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:700;color:var(--text-primary);"><?= e($r['plat_nomor']) ?></div>
                            <div style="font-size:12px;color:var(--text-secondary);"><?= e($r['merek']) ?> <?= e($r['model']) ?></div>
                        </td>
                        <td style="padding:14px 18px;font-size:12.5px;color:var(--text-secondary);">
                            <div><?= tgl_indo($r['tgl_sewa']) ?></div>
                            <div>s/d <?= tgl_indo($r['tgl_kembali_rencana']) ?></div>
                        </td>
                        <td style="padding:14px 18px;font-size:13.5px;font-weight:700;color:var(--text-primary);">
                            <?= rupiah($r['total_biaya']) ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <?php
                            $badgeClass = match($r['status']) {
                                'Berjalan' => 'badge-primary',
                                'Dipesan' => 'badge-warning',
                                'Selesai' => 'badge-success',
                                default => 'badge-ghost',
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                                <?= e($r['status']) ?>
                            </span>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <div style="display:inline-flex;gap:6px;">
                                <a href="<?= base_url('peminjaman.php?action=show&id=' . $r['id']) ?>" class="btn btn-outline btn-sm" title="Lihat Detail">
                                    <i data-lucide="eye" style="width:14px;height:14px;"></i>
                                </a>

                                <?php if ($r['status'] === 'Dipesan'): ?>
                                    <form action="<?= base_url('peminjaman.php') ?>" method="POST" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="serah-kunci">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-primary btn-sm" title="Serah Kunci">
                                            <i data-lucide="key" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if (in_array($r['status'], ['Dipesan', 'Berjalan'], true)): ?>
                                    <form action="<?= base_url('peminjaman.php') ?>" method="POST" onsubmit="return confirm('Batalkan transaksi sewa ini?');" style="margin:0;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="batal">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Batalkan">
                                            <i data-lucide="x" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
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
