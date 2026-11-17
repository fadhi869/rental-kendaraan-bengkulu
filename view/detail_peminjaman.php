<?php
/**
 * View Detail Peminjaman / Pengembalian Unit & Hitung Denda
 */
?>
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Pengembalian Armada & Hitung Denda</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Proses pengembalian unit armada yang sedang disewa dan hitung otomatis denda keterlambatan.</p>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KODE</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ANGGOTA PENYEWA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">UNIT ARMADA</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">JATUH TEMPO KEMBALI</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">STATUS WAKTU</th>
                    <th style="padding:14px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">PROSES PENGEMBALIAN</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($returnsList)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px 20px;color:var(--text-secondary);">
                        <i data-lucide="check-circle" style="width:36px;height:36px;color:var(--success);margin-bottom:8px;display:inline-block;"></i>
                        <div style="font-weight:600;font-size:14px;color:var(--text-primary);">Tidak ada armada yang menunggu pengembalian!</div>
                        <div style="font-size:12.5px;margin-top:4px;">Semua unit yang sedang disewa masih dalam masa rental atau telah dikembalikan.</div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($returnsList as $item): ?>
                    <?php
                    $isLate = strtotime(date('Y-m-d')) > strtotime($item['tgl_kembali_rencana']);
                    $diffDays = (int)floor((strtotime(date('Y-m-d')) - strtotime($item['tgl_kembali_rencana'])) / 86400);
                    $estDenda = $isLate ? ($diffDays * (float)$item['tarif_saat_sewa'] * 1.5) : 0;
                    ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:14px 18px;font-family:monospace;font-weight:700;color:var(--text-primary);">
                            <?= e($item['kode_transaksi']) ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:600;color:var(--text-primary);"><?= e($item['nama_pelanggan']) ?></div>
                            <div style="font-size:12px;color:var(--text-secondary);"><?= e($item['no_hp']) ?></div>
                        </td>
                        <td style="padding:14px 18px;">
                            <div style="font-weight:700;color:var(--text-primary);"><?= e($item['plat_nomor']) ?></div>
                            <div style="font-size:12px;color:var(--text-secondary);"><?= e($item['merek']) ?> <?= e($item['model']) ?></div>
                        </td>
                        <td style="padding:14px 18px;font-size:13px;color:var(--text-secondary);">
                            <?= tgl_indo($item['tgl_kembali_rencana']) ?>
                        </td>
                        <td style="padding:14px 18px;">
                            <?php if ($isLate): ?>
                                <span class="badge badge-danger" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                                    Terlambat <?= $diffDays ?> Hari (Est. Denda: <?= rupiah($estDenda) ?>)
                                </span>
                            <?php else: ?>
                                <span class="badge badge-success" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                                    Tepat Waktu
                                </span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:14px 18px;text-align:right;">
                            <form action="<?= base_url('detail_peminjaman.php') ?>" method="POST" style="display:inline-flex;align-items:center;gap:8px;" onsubmit="return confirm('Konfirmasi terima pengembalian unit <?= e($item['plat_nomor']) ?>?');">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="proses">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <input type="date" name="tgl_kembali_aktual" value="<?= date('Y-m-d') ?>" class="form-input" style="padding:6px 10px;font-size:12px;border:1px solid var(--border);border-radius:var(--radius-sm);" title="Tanggal pengembalian riil">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i data-lucide="check" style="width:14px;height:14px;margin-right:4px;"></i> Terima Unit
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
