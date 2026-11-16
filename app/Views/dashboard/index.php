<?php
$totalFleet = max(1, (int)$stats['total_kendaraan']);
$pctTersedia = round(((int)$stats['kendaraan_tersedia'] / $totalFleet) * 100);
$pctDisewa = round(((int)$stats['kendaraan_disewa'] / $totalFleet) * 100);
$pctPerawatan = round(((int)$stats['kendaraan_perawatan'] / $totalFleet) * 100);
?>
<div class="page-header">
    <div>
        <h1 class="page-title">Ringkasan Operasional</h1>
        <p class="page-desc">Pantau status armada dan transaksi hari ini.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('laporan') ?>" class="btn btn-outline btn-magnetic">
            <i data-lucide="bar-chart-2" style="width:15px;height:15px;"></i>
            <span>Laporan</span>
        </a>
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-primary btn-premium-cta">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Sewa Baru</span>
        </a>
    </div>
</div>

<!-- KPI Cards with 3D Tilt & Cursor Spotlight -->
<div class="grid-kpi" style="margin-bottom:20px;">
    <div class="kpi-card tilt-card spotlight-card">
        <div class="kpi-header">
            <span class="kpi-label">Total Armada</span>
            <span class="kpi-icon">
                <i data-lucide="car-front" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['total_kendaraan'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span>
            </div>
            <div style="margin-top:6px;">
                <span class="badge badge-tersedia"><?= (int)$stats['kendaraan_tersedia'] ?> Tersedia</span>
            </div>
        </div>
    </div>

    <div class="kpi-card tilt-card spotlight-card">
        <div class="kpi-header">
            <span class="kpi-label">Sedang Disewa</span>
            <span class="kpi-icon" style="color:var(--accent);background:var(--accent-subtle);border-color:var(--accent-border);">
                <i data-lucide="key" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['kendaraan_disewa'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span>
            </div>
            <div style="margin-top:6px;">
                <span class="badge badge-dipesan"><?= (int)$stats['sewa_dipesan'] ?> Dipesan</span>
            </div>
        </div>
    </div>

    <div class="kpi-card tilt-card spotlight-card">
        <div class="kpi-header">
            <span class="kpi-label">Pelanggan</span>
            <span class="kpi-icon">
                <i data-lucide="users" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value">
                <span class="odometer-digit" data-counter-target="<?= (int)$stats['total_pelanggan'] ?>">0</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Orang</span>
            </div>
            <div class="kpi-sub"><?= (int)$stats['sewa_selesai'] ?> transaksi selesai</div>
        </div>
    </div>

    <div class="card-dark tilt-card spotlight-card shimmer-card" style="padding:18px 20px;display:flex;flex-direction:column;justify-content:space-between;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;position:relative;z-index:3;">
            <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#9E9B9C;">Total Penerimaan</span>
            <span style="width:32px;height:32px;border-radius:var(--radius-md);background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(192,65,14,0.4);">
                <i data-lucide="wallet" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div style="position:relative;z-index:3;">
            <div style="font-size:21px;font-weight:800;color:#FFFFFF;letter-spacing:-0.01em;">
                <span class="odometer-digit" data-counter-target="<?= (float)$stats['total_pendapatan'] ?>" data-counter-currency="true">Rp 0</span>
            </div>
            <div style="font-size:11.5px;color:#A8A5A6;margin-top:2px;">Total sewa & denda</div>
        </div>
    </div>
</div>

<!-- Chart & Fleet Status -->
<div class="grid-12" style="margin-bottom:20px;">
    <div class="card col-8" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h2 class="card-title">Grafik Pendapatan Sewa</h2>
                <p class="card-desc">Pendapatan harian berdasarkan tanggal mulai sewa.</p>
            </div>
            <span style="font-size:11.5px;font-weight:600;color:var(--text-secondary);background:var(--bg-warm);padding:4px 10px;border-radius:var(--radius-sm);border:1px solid var(--border);">
                10 Transaksi Terakhir
            </span>
        </div>
        <div id="chartInteractiveBox" class="chart-container-rel">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="card col-4" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
            <h2 class="card-title">Ketersediaan Unit</h2>
            <p class="card-desc" style="margin-bottom:16px;">Status kendaraan hari ini.</p>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <!-- Tersedia -->
                <div class="progress-status-row">
                    <div class="progress-liquid-fill fill-tersedia" data-fill-percent="<?= $pctTersedia ?>"></div>
                    <div class="progress-row-content">
                        <span style="display:flex;align-items:center;gap:9px;font-weight:600;font-size:13px;">
                            <span class="radar-dot" style="width:8px;height:8px;border-radius:50%;background:#10B981;"></span>
                            <span>Tersedia</span>
                        </span>
                        <strong style="font-size:13.5px;">
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
                        <strong style="font-size:13.5px;color:var(--accent);">
                            <?= (int)$stats['kendaraan_disewa'] ?> Unit 
                            <small style="font-size:11px;color:var(--text-muted);font-weight:500;">(<?= $pctDisewa ?>%)</small>
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
                        <strong style="font-size:13.5px;">
                            <?= (int)$stats['kendaraan_perawatan'] ?> Unit 
                            <small style="font-size:11px;color:var(--text-muted);font-weight:500;">(<?= $pctPerawatan ?>%)</small>
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <a href="<?= base_url('kendaraan') ?>" class="btn btn-subtle btn-magnetic" style="width:100%;margin-top:16px;">
            <span>Lihat Semua Kendaraan</span>
            <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
        </a>
    </div>
</div>

<!-- Active Rentals Table -->
<div class="card" style="overflow:hidden;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Sewa Aktif & Booking</h2>
            <p class="card-desc">Unit yang sedang dipakai atau menunggu diambil.</p>
        </div>
        <a href="<?= base_url('penyewaan') ?>" class="btn btn-outline btn-sm btn-magnetic">
            <span>Lihat Semua Transaksi</span>
            <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
        </a>
    </div>

    <div class="table-wrap">
        <table class="ui-table table-interactive">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Penyewa</th>
                    <th>Kendaraan</th>
                    <th>Jadwal Sewa</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th class="actions">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiBerjalan)): ?>
                    <tr>
                        <td colspan="7">
                            <?php
                            $icon = 'check-circle-2';
                            $title = 'Tidak Ada Sewa Berjalan';
                            $message = 'Saat ini seluruh kendaraan yang disewa telah dikembalikan atau belum ada pemesanan aktif.';
                            $actionUrl = base_url('penyewaan/create');
                            $actionLabel = 'Buat Sewa Baru';
                            require BASE_PATH . '/app/Views/partials/empty-state.php';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transaksiBerjalan as $r): ?>
                    <tr>
                        <td>
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" style="font-weight:700;color:var(--accent);">
                                <?= e($r['kode_transaksi']) ?>
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['nama_pelanggan']) ?></div>
                            <div style="font-size:11.5px;color:var(--text-muted);"><?= e($r['no_hp']) ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['merek'] . ' ' . $r['model']) ?></div>
                            <span class="plate-tag"><?= e($r['plat_nomor']) ?></span>
                        </td>
                        <td style="font-size:12.5px;color:var(--text-secondary);">
                            <?= tgl_indo($r['tgl_sewa']) ?> &rarr; <?= tgl_indo($r['tgl_kembali_rencana']) ?>
                        </td>
                        <td><strong><?= rupiah($r['total_biaya']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <span class="badge-dot"></span><?= e($r['status']) ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" class="btn btn-outline btn-sm btn-magnetic">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('revenueChart');
    const containerEl = document.getElementById('chartInteractiveBox');

    if (el && window.Chart) {
        const chart = new Chart(el, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($chartValues) ?>,
                    backgroundColor: '#C0410E',
                    hoverBackgroundColor: '#222021',
                    borderRadius: 6,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1300,
                    easing: 'easeOutElastic',
                    delay: function (ctx) {
                        let delay = 0;
                        if (ctx.type === 'data' && ctx.mode === 'default') {
                            delay = ctx.dataIndex * 95;
                        }
                        return delay;
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false } // Managed by custom glassmorphism inertia tooltip
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#EEF1F3' },
                        ticks: {
                            callback: function (val) {
                                if (val >= 1000000) return (val / 1000000) + 'jt';
                                if (val >= 1000) return (val / 1000) + 'rb';
                                return val;
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        if (window.initDashboardChartMotion) {
            window.initDashboardChartMotion(chart, containerEl);
        }
    }
});
</script>
