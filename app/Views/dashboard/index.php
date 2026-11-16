<div class="page-header">
    <div>
        <h1 class="page-title">Ringkasan Operasional</h1>
        <p class="page-desc">Status armada, penyewaan berjalan, dan penerimaan rental saat ini.</p>
    </div>
    <div class="page-actions">
        <a href="<?= base_url('laporan') ?>" class="btn btn-outline">
            <i data-lucide="bar-chart-2" style="width:15px;height:15px;"></i>
            <span>Laporan</span>
        </a>
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-primary">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            <span>Sewa Baru</span>
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid-kpi" style="margin-bottom:20px;">
    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Total Armada</span>
            <span class="kpi-icon">
                <i data-lucide="car-front" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value"><?= (int)$stats['total_kendaraan'] ?> <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span></div>
            <div style="margin-top:6px;">
                <span class="badge badge-tersedia"><?= (int)$stats['kendaraan_tersedia'] ?> Tersedia</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Sedang Disewa</span>
            <span class="kpi-icon" style="color:var(--accent);background:var(--accent-subtle);border-color:var(--accent-border);">
                <i data-lucide="key" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value"><?= (int)$stats['kendaraan_disewa'] ?> <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Unit</span></div>
            <div style="margin-top:6px;">
                <span class="badge badge-dipesan"><?= (int)$stats['sewa_dipesan'] ?> Dipesan</span>
            </div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-header">
            <span class="kpi-label">Pelanggan</span>
            <span class="kpi-icon">
                <i data-lucide="users" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div class="kpi-value"><?= (int)$stats['total_pelanggan'] ?> <span style="font-size:13px;font-weight:500;color:var(--text-secondary);">Orang</span></div>
            <div class="kpi-sub"><?= (int)$stats['sewa_selesai'] ?> transaksi selesai</div>
        </div>
    </div>

    <div class="card-dark" style="padding:18px 20px;display:flex;flex-direction:column;justify-content:space-between;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.04em;color:#9E9B9C;">Total Penerimaan</span>
            <span style="width:32px;height:32px;border-radius:var(--radius-md);background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="wallet" style="width:16px;height:16px;"></i>
            </span>
        </div>
        <div>
            <div style="font-size:21px;font-weight:800;color:#FFFFFF;"><?= rupiah($stats['total_pendapatan']) ?></div>
            <div style="font-size:11.5px;color:#A8A5A6;margin-top:2px;">Akumulasi sewa & denda</div>
        </div>
    </div>
</div>

<!-- Chart & Fleet Status -->
<div class="grid-12" style="margin-bottom:20px;">
    <div class="card col-8" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <h2 class="card-title">Grafik Pendapatan Sewa</h2>
                <p class="card-desc">Pendapatan per hari berdasarkan tanggal mulai transaksi</p>
            </div>
        </div>
        <div style="height:240px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="card col-4" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
            <h2 class="card-title">Ketersediaan Unit</h2>
            <p class="card-desc" style="margin-bottom:16px;">Kondisi operasional kendaraan hari ini</p>

            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="background:var(--bg-warm);border:1px solid var(--border);border-radius:var(--radius-md);padding:10px 14px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:13px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#10B981;"></span> Tersedia
                    </span>
                    <strong style="font-size:14px;"><?= (int)$stats['kendaraan_tersedia'] ?> Unit</strong>
                </div>

                <div style="background:var(--bg-warm);border:1px solid var(--border);border-radius:var(--radius-md);padding:10px 14px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:13px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:var(--accent);"></span> Disewa
                    </span>
                    <strong style="font-size:14px;color:var(--accent);"><?= (int)$stats['kendaraan_disewa'] ?> Unit</strong>
                </div>

                <div style="background:var(--bg-warm);border:1px solid var(--border);border-radius:var(--radius-md);padding:10px 14px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:13px;">
                        <span style="width:8px;height:8px;border-radius:50%;background:#F59E0B;"></span> Perawatan
                    </span>
                    <strong style="font-size:14px;"><?= (int)$stats['kendaraan_perawatan'] ?> Unit</strong>
                </div>
            </div>
        </div>

        <a href="<?= base_url('kendaraan') ?>" class="btn btn-subtle" style="width:100%;margin-top:16px;">
            <span>Lihat Semua Kendaraan</span>
            <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
        </a>
    </div>
</div>

<!-- Active Rentals Table -->
<div class="card" style="overflow:hidden;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Penyewaan Aktif & Reservasi</h2>
            <p class="card-desc">Unit yang sedang berjalan atau menunggu jadwal serah kunci</p>
        </div>
        <a href="<?= base_url('penyewaan') ?>" class="btn btn-outline btn-sm">
            <span>Lihat Semua Transaksi</span>
            <i data-lucide="arrow-right" style="width:13px;height:13px;"></i>
        </a>
    </div>

    <div class="table-wrap">
        <table class="ui-table">
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
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" class="btn btn-outline btn-sm">Detail</a>
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
    if (el && window.Chart) {
        new Chart(el, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($chartValues) ?>,
                    backgroundColor: '#C0410E',
                    hoverBackgroundColor: '#222021',
                    borderRadius: 6,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return 'Rp ' + Number(ctx.raw).toLocaleString('id-ID');
                            }
                        }
                    }
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
    }
});
</script>
