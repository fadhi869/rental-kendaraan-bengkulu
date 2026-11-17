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
$totalFleet = max(1, (int)$stats['total_kendaraan']);
$pctTersedia = round(((int)$stats['kendaraan_tersedia'] / $totalFleet) * 100);
$pctDisewa = round(((int)$stats['kendaraan_disewa'] / $totalFleet) * 100);
$pctPerawatan = round(((int)$stats['kendaraan_perawatan'] / $totalFleet) * 100);

ob_start();
?>
<div class="page-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <h1 class="page-title" style="font-size:22px;font-weight:700;color:var(--text-primary);margin:0 0 4px 0;">Ringkasan Operasional</h1>
        <p class="page-desc" style="font-size:13px;color:var(--text-secondary);margin:0;">Pantau status armada dan transaksi hari ini.</p>
    </div>
    <div class="page-actions" style="display:flex;gap:10px;">
        <a href="<?= base_url('peminjaman.php?action=laporan') ?>" class="btn btn-outline btn-magnetic">
            <i data-lucide="bar-chart-2" style="width:15px;height:15px;margin-right:6px;"></i>
            <span>Laporan</span>
        </a>
        <a href="<?= base_url('peminjaman.php?action=create') ?>" class="btn btn-primary btn-premium-cta">
            <i data-lucide="plus" style="width:15px;height:15px;margin-right:6px;"></i>
            <span>Sewa Baru</span>
        </a>
    </div>
</div>

<!-- KPI Cards with 3D Tilt, Cursor Spotlight & Odometer -->
<div class="grid-kpi" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <!-- Total Armada -->
    <div class="kpi-card card tilt-card spotlight-card" style="padding:20px;">
        <div class="kpi-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <span class="kpi-label" style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Total Armada</span>
            <span class="kpi-icon" style="color:var(--text-secondary);"><i data-lucide="car-front" style="width:16px;height:16px;"></i></span>
        </div>
        <div>
            <div class="kpi-value" style="font-size:26px;font-weight:800;color:var(--text-primary);margin:4px 0;">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['total_kendaraan'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span>
            </div>
            <div style="margin-top:6px;">
                <span class="badge badge-tersedia" style="background:#ECFDF5;color:#059669;padding:3px 8px;border-radius:12px;font-size:11.5px;font-weight:600;">
                    <?= (int)$stats['kendaraan_tersedia'] ?> Tersedia
                </span>
            </div>
        </div>
    </div>

    <!-- Sedang Disewa -->
    <div class="kpi-card card tilt-card spotlight-card" style="padding:20px;">
        <div class="kpi-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <span class="kpi-label" style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Sedang Disewa</span>
            <span class="kpi-icon" style="color:var(--accent);"><i data-lucide="key" style="width:16px;height:16px;"></i></span>
        </div>
        <div>
            <div class="kpi-value" style="font-size:26px;font-weight:800;color:var(--accent);margin:4px 0;">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['kendaraan_disewa'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span>
            </div>
            <div style="margin-top:6px;">
                <span class="badge badge-dipesan" style="background:#FFFBEB;color:#D97706;padding:3px 8px;border-radius:12px;font-size:11.5px;font-weight:600;">
                    <?= (int)$stats['sewa_dipesan'] ?> Dipesan
                </span>
            </div>
        </div>
    </div>

    <!-- Pelanggan / Anggota -->
    <div class="kpi-card card tilt-card spotlight-card" style="padding:20px;">
        <div class="kpi-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <span class="kpi-label" style="font-size:12px;font-weight:700;color:var(--text-secondary);text-transform:uppercase;">Pelanggan</span>
            <span class="kpi-icon" style="color:var(--text-secondary);"><i data-lucide="users" style="width:16px;height:16px;"></i></span>
        </div>
        <div>
            <div class="kpi-value" style="font-size:26px;font-weight:800;color:var(--text-primary);margin:4px 0;">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['total_pelanggan'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Orang</span>
            </div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:6px;">
                <?= (int)$stats['sewa_selesai'] ?> transaksi selesai
            </div>
        </div>
    </div>

    <!-- Total Penerimaan (Dark Card with Shimmer Sweep) -->
    <div class="card-dark tilt-card spotlight-card shimmer-card" style="padding:20px;border-radius:var(--radius-lg);background:#222021;color:#fff;display:flex;flex-direction:column;justify-content:space-between;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;position:relative;z-index:3;">
            <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#9E9B9C;">Total Penerimaan</span>
            <span style="width:30px;height:30px;border-radius:8px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="wallet" style="width:15px;height:15px;"></i>
            </span>
        </div>
        <div style="position:relative;z-index:3;">
            <div style="font-size:22px;font-weight:800;color:#FFFFFF;letter-spacing:-0.01em;">
                <span class="odometer-digit" data-counter-target="<?= (float)$stats['total_pendapatan'] ?>" data-counter-currency="true">Rp 0</span>
            </div>
            <div style="font-size:11.5px;color:#A8A5A6;margin-top:2px;">Total sewa & denda</div>
        </div>
    </div>
</div>

<!-- Chart & Fleet Status -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;">
    <!-- Revenue Chart with Crosshair & Glassmorphism Tooltip -->
    <div class="card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h2 class="card-title" style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 2px 0;">Grafik Pendapatan Sewa</h2>
                <p class="card-desc" style="font-size:12.5px;color:var(--text-secondary);margin:0;">Pendapatan harian berdasarkan tanggal mulai sewa.</p>
            </div>
            <span style="font-size:11.5px;font-weight:600;color:var(--text-secondary);background:var(--bg-warm);padding:4px 10px;border-radius:12px;border:1px solid var(--border);">10 Transaksi Terakhir</span>
        </div>
        <div id="chartInteractiveBox" class="chart-container-rel" style="position:relative;height:240px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Fleet Status with Animated Liquid Progress & Radar Dots -->
    <div class="card" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
            <h2 class="card-title" style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 2px 0;">Ketersediaan Unit</h2>
            <p class="card-desc" style="font-size:12.5px;color:var(--text-secondary);margin:0 0 16px 0;">Status kendaraan hari ini.</p>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <!-- Tersedia -->
                <div class="progress-status-row">
                    <div class="progress-liquid-fill fill-tersedia" data-fill-percent="<?= $pctTersedia ?>"></div>
                    <div class="progress-row-content">
                        <span style="display:flex;align-items:center;gap:9px;font-weight:600;font-size:13px;">
                            <span class="radar-dot" style="width:8px;height:8px;border-radius:50%;background:#10B981;"></span>
                            <span>Tersedia</span>
                        </span>
                        <strong style="font-size:13px;">
                            <?= (int)$stats['kendaraan_tersedia'] ?> Unit 
                            <small style="font-size:11px;color:var(--text-muted);font-weight:500;">(<?= $pctTersedia ?>%)</small>
                        </strong>
                    </div>
                </div>

                <!-- Disewa -->
                <div class="progress-status-row">
                    <div class="progress-liquid-fill fill-disewa" data-fill-percent="<?= $pctDisewa ?>"></div>
                    <div class="progress-row-content">
                        <span style="display:flex;align-items:center;gap:9px;font-weight:600;font-size:13px;">
                            <span class="radar-dot" style="width:8px;height:8px;border-radius:50%;background:var(--accent);"></span>
                            <span style="color:var(--accent);">Disewa</span>
                        </span>
                        <strong style="font-size:13px;color:var(--accent);">
                            <?= (int)$stats['kendaraan_disewa'] ?> Unit 
                            <small style="font-size:11px;color:var(--accent);font-weight:500;">(<?= $pctDisewa ?>%)</small>
                        </strong>
                    </div>
                </div>

                <!-- Perawatan -->
                <div class="progress-status-row">
                    <div class="progress-liquid-fill fill-perawatan" data-fill-percent="<?= $pctPerawatan ?>"></div>
                    <div class="progress-row-content">
                        <span style="display:flex;align-items:center;gap:9px;font-weight:600;font-size:13px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:#F59E0B;"></span>
                            <span>Perawatan</span>
                        </span>
                        <strong style="font-size:13px;">
                            <?= (int)$stats['kendaraan_perawatan'] ?> Unit 
                            <small style="font-size:11px;color:var(--text-muted);font-weight:500;">(<?= $pctPerawatan ?>%)</small>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?= base_url('buku.php') ?>" class="btn btn-outline btn-magnetic" style="width:100%;margin-top:16px;justify-content:center;">
            <span>Lihat Semua Armada</span>
            <i data-lucide="arrow-right" style="width:14px;height:14px;margin-left:6px;"></i>
        </a>
    </div>
</div>

<!-- Active Rentals Table with Hover Accent & Magnetic Detail -->
<div class="card" style="overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h2 style="font-size:15px;font-weight:700;color:var(--text-primary);margin:0 0 2px 0;">Sewa Aktif & Booking</h2>
            <p style="font-size:12.5px;color:var(--text-secondary);margin:0;">Unit yang sedang dipakai atau menunggu diambil.</p>
        </div>
        <a href="<?= base_url('peminjaman.php') ?>" class="btn btn-outline btn-sm btn-magnetic">
            <span>Semua Transaksi</span>
            <i data-lucide="arrow-right" style="width:13px;height:13px;margin-left:4px;"></i>
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="table-modern" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:var(--bg-warm);text-align:left;border-bottom:1px solid var(--border);">
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">KODE</th>
                    <th style="padding:12px 18px;font-size:12px;font-weight:700;color:var(--text-secondary);">PENYEWA</th>
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
                            <a href="<?= base_url('peminjaman.php?action=show&id=' . $tb['id']) ?>" style="color:var(--accent);text-decoration:none;">
                                <?= e($tb['kode_transaksi']) ?>
                            </a>
                        </td>
                        <td style="padding:12px 18px;font-weight:600;"><?= e($tb['nama_pelanggan']) ?></td>
                        <td style="padding:12px 18px;"><?= e($tb['plat_nomor']) ?> (<?= e($tb['merek']) ?>)</td>
                        <td style="padding:12px 18px;font-size:12.5px;"><?= tgl_indo($tb['tgl_kembali_rencana']) ?></td>
                        <td style="padding:12px 18px;">
                            <span class="badge <?= $tb['status']==='Berjalan'?'badge-primary':'badge-warning' ?>" style="padding:4px 10px;border-radius:12px;font-size:11.5px;font-weight:600;background:<?= $tb['status']==='Berjalan'?'rgba(192,65,14,0.12)':'rgba(217,119,6,0.12)' ?>;color:<?= $tb['status']==='Berjalan'?'var(--accent)':'#D97706' ?>;">
                                <?= e($tb['status']) ?>
                            </span>
                        </td>
                        <td style="padding:12px 18px;text-align:right;">
                            <a href="<?= base_url('peminjaman.php?action=show&id=' . $tb['id']) ?>" class="btn btn-outline btn-sm btn-magnetic">Detail</a>
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
    const container = document.getElementById('chartInteractiveBox');
    if (ctx && typeof Chart !== 'undefined') {
        const chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartData['labels']) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($chartData['values']) ?>,
                    backgroundColor: 'rgba(192, 65, 14, 0.85)',
                    hoverBackgroundColor: 'rgba(192, 65, 14, 1)',
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 900,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(v) { return 'Rp ' + (v/1000).toLocaleString('id-ID') + 'k'; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        if (window.initDashboardChartMotion) {
            window.initDashboardChartMotion(chartInstance, container);
        }
    }
});
</script>
<?php
$content = ob_get_clean();
render_layout('Ringkasan Operasional', 'dashboard', $content);
