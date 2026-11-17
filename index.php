<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

if (!Auth::check()) {
    redirect('login.php');
}

$bukuModel = new Buku();
$peminjamanModel = new Peminjaman();
$anggotaModel = new Anggota();
$adminModel = new Admin();

$stats = [
    'total_kendaraan'     => $bukuModel->getFleetStats()['total_kendaraan'],
    'kendaraan_tersedia'  => $bukuModel->getFleetStats()['kendaraan_tersedia'],
    'kendaraan_disewa'    => $bukuModel->getFleetStats()['kendaraan_disewa'],
    'kendaraan_perawatan' => $bukuModel->getFleetStats()['kendaraan_perawatan'],
    'total_pelanggan'     => $anggotaModel->countAll(),
    'total_pendapatan'    => $peminjamanModel->getSummaryStats()['total_pendapatan'],
    'sewa_berjalan'       => $peminjamanModel->getSummaryStats()['sewa_berjalan'],
    'sewa_dipesan'        => $peminjamanModel->getSummaryStats()['sewa_dipesan'],
    'sewa_selesai'        => $peminjamanModel->getSummaryStats()['sewa_selesai'],
];

$chartData = $peminjamanModel->getDailyRevenueChart(10);
$transaksiBerjalan = $peminjamanModel->getActiveAndReserved(8);
$totalFleet = max(1, $stats['total_kendaraan']);
$pctTersedia = round(((int)$stats['kendaraan_tersedia'] / $totalFleet) * 100);
$pctDisewa = round(((int)$stats['kendaraan_disewa'] / $totalFleet) * 100);
$pctPerawatan = round(((int)$stats['kendaraan_perawatan'] / $totalFleet) * 100);

ob_start();
?>
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Ringkasan Operasional</h1>
        <p style="font-size:13px;color:var(--text-secondary);margin:0;">Pantau ketersediaan armada dan transaksi sewa aktif hari ini.</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?= base_url('peminjaman.php?action=laporan') ?>" class="btn btn-outline">Laporan</a>
        <a href="<?= base_url('peminjaman.php?action=create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;margin-right:6px;"></i> Tambah Penyewaan
        </a>
    </div>
</div>

<div class="grid-kpi" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="kpi-card card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Armada</div>
        <div style="font-size:26px;font-weight:800;color:var(--text-primary);margin:8px 0;"><?= $stats['total_kendaraan'] ?></div>
        <div style="font-size:12px;color:var(--success);font-weight:600;"><?= $stats['kendaraan_tersedia'] ?> unit siap sewa</div>
    </div>
    <div class="kpi-card card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Sewa Berjalan</div>
        <div style="font-size:26px;font-weight:800;color:var(--primary);margin:8px 0;"><?= $stats['sewa_berjalan'] ?></div>
        <div style="font-size:12px;color:var(--text-secondary);"><?= $stats['sewa_dipesan'] ?> booking dipesan</div>
    </div>
    <div class="kpi-card card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Anggota</div>
        <div style="font-size:26px;font-weight:800;color:var(--text-primary);margin:8px 0;"><?= $stats['total_pelanggan'] ?></div>
        <div style="font-size:12px;color:var(--text-secondary);">Penyewa terdaftar</div>
    </div>
    <div class="kpi-card card" style="padding:20px;">
        <div style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Penerimaan</div>
        <div style="font-size:24px;font-weight:800;color:var(--success);margin:8px 0;"><?= rupiah($stats['total_pendapatan']) ?></div>
        <div style="font-size:12px;color:var(--text-secondary);"><?= $stats['sewa_selesai'] ?> transaksi tuntas</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h2 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0;">Grafik Pendapatan Sewa</h2>
            <span style="font-size:12px;font-weight:600;color:var(--text-secondary);background:var(--bg-warm);padding:4px 10px;border-radius:12px;">10 Transaksi Terakhir</span>
        </div>
        <div style="height:220px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="card" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 16px 0;">Ketersediaan Unit</h2>
        <div style="display:flex;flex-direction:column;gap:12px;">
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span style="color:var(--success);font-weight:600;">Tersedia</span>
                    <span style="font-weight:700;"><?= $stats['kendaraan_tersedia'] ?> Unit (<?= $pctTersedia ?>%)</span>
                </div>
                <div style="height:8px;background:var(--bg-warm);border-radius:4px;overflow:hidden;">
                    <div style="height:100%;background:var(--success);width:<?= $pctTersedia ?>%;"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span style="color:var(--primary);font-weight:600;">Sedang Disewa</span>
                    <span style="font-weight:700;"><?= $stats['kendaraan_disewa'] ?> Unit (<?= $pctDisewa ?>%)</span>
                </div>
                <div style="height:8px;background:var(--bg-warm);border-radius:4px;overflow:hidden;">
                    <div style="height:100%;background:var(--primary);width:<?= $pctDisewa ?>%;"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span style="color:var(--danger);font-weight:600;">Perawatan</span>
                    <span style="font-weight:700;"><?= $stats['kendaraan_perawatan'] ?> Unit (<?= $pctPerawatan ?>%)</span>
                </div>
                <div style="height:8px;background:var(--bg-warm);border-radius:4px;overflow:hidden;">
                    <div style="height:100%;background:var(--danger);width:<?= $pctPerawatan ?>%;"></div>
                </div>
            </div>
        </div>
        <div style="padding-top:16px;border-top:1px solid var(--border);text-align:center;">
            <a href="<?= base_url('buku.php') ?>" style="font-size:13px;font-weight:600;color:var(--primary);text-decoration:none;">Lihat Semua Armada &rarr;</a>
        </div>
    </div>
</div>

<div class="card" style="overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <h2 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0;">Peminjaman Sedang Berjalan & Booking</h2>
        <a href="<?= base_url('peminjaman.php') ?>" style="font-size:12.5px;font-weight:600;color:var(--primary);text-decoration:none;">Semua Transaksi &rarr;</a>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KODE</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ANGGOTA</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">ARMADA</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">BATAS KEMBALI</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">STATUS</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);text-align:right;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiBerjalan)): ?>
                <tr><td colspan="6" style="padding:32px;text-align:center;color:var(--text-secondary);">Tidak ada sewa berjalan saat ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($transaksiBerjalan as $tb): ?>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:12px 18px;font-family:monospace;font-weight:700;">
                            <a href="<?= base_url('peminjaman.php?action=show&id=' . $tb['id']) ?>" style="color:var(--primary);text-decoration:none;">
                                <?= e($tb['kode_transaksi']) ?>
                            </a>
                        </td>
                        <td style="padding:12px 18px;font-weight:600;"><?= e($tb['nama_pelanggan']) ?></td>
                        <td style="padding:12px 18px;"><?= e($tb['plat_nomor']) ?> (<?= e($tb['merek']) ?>)</td>
                        <td style="padding:12px 18px;font-size:12.5px;"><?= tgl_indo($tb['tgl_kembali_rencana']) ?></td>
                        <td style="padding:12px 18px;">
                            <span class="badge <?= $tb['status']==='Berjalan'?'badge-primary':'badge-warning' ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;">
                                <?= e($tb['status']) ?>
                            </span>
                        </td>
                        <td style="padding:12px 18px;text-align:right;">
                            <a href="<?= base_url('peminjaman.php?action=show&id=' . $tb['id']) ?>" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx && typeof Chart !== 'undefined') {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartData['labels']) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($chartData['values']) ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) { return 'Rp ' + (v/1000).toLocaleString('id-ID') + 'k'; }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php
$content = ob_get_clean();
render_layout('Ringkasan Operasional', 'dashboard', $content);
