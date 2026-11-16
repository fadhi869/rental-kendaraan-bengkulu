<div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;">
    <div>
        <h1 style="margin:0;font-size:24px;font-weight:800;letter-spacing:-0.02em;color:#282627;">Dashboard Operasional</h1>
        <p style="margin:4px 0 0;color:#646163;">Ringkasan performa penyewaan, status armada, dan pendapatan usaha.</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="<?= base_url('laporan') ?>" class="btn btn-warm">
            <i data-lucide="file-spreadsheet" style="width:16px;height:16px;"></i>
            <span>Laporan</span>
        </a>
        <a href="<?= base_url('penyewaan/create') ?>" class="btn btn-accent">
            <i data-lucide="plus" style="width:16px;height:16px;"></i>
            <span>Transaksi Sewa</span>
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid-kpi" style="margin-bottom:24px;">
    <div class="ui-card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;">Total Armada</span>
            <span style="width:36px;height:36px;border-radius:10px;background:#F8F7F3;border:1px solid #E5E9EC;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="car" style="width:17px;height:17px;"></i>
            </span>
        </div>
        <div style="margin-top:14px;display:flex;align-items:baseline;justify-content:space-between;">
            <span style="font-size:26px;font-weight:800;"><?= (int)$stats['total_kendaraan'] ?> <small style="font-size:13px;font-weight:500;color:#646163;">Unit</small></span>
            <span class="badge badge-tersedia"><?= (int)$stats['kendaraan_tersedia'] ?> Tersedia</span>
        </div>
    </div>

    <div class="ui-card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;">Sedang Disewa</span>
            <span style="width:36px;height:36px;border-radius:10px;background:rgba(192,65,14,0.1);color:#C0410E;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="key-round" style="width:17px;height:17px;"></i>
            </span>
        </div>
        <div style="margin-top:14px;display:flex;align-items:baseline;justify-content:space-between;">
            <span style="font-size:26px;font-weight:800;"><?= (int)$stats['kendaraan_disewa'] ?> <small style="font-size:13px;font-weight:500;color:#646163;">Unit</small></span>
            <span class="badge badge-disewa"><?= (int)$stats['sewa_dipesan'] ?> Booking</span>
        </div>
    </div>

    <div class="ui-card" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:12px;font-weight:700;text-transform:uppercase;color:#646163;">Pelanggan</span>
            <span style="width:36px;height:36px;border-radius:10px;background:#F8F7F3;border:1px solid #E5E9EC;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="users" style="width:17px;height:17px;"></i>
            </span>
        </div>
        <div style="margin-top:14px;display:flex;align-items:baseline;justify-content:space-between;">
            <span style="font-size:26px;font-weight:800;"><?= (int)$stats['total_pelanggan'] ?> <small style="font-size:13px;font-weight:500;color:#646163;">Orang</small></span>
            <span style="font-size:12px;color:#646163;"><?= (int)$stats['sewa_selesai'] ?> Sewa Selesai</span>
        </div>
    </div>

    <div class="ui-card-dark" style="padding:20px;">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:12px;font-weight:700;text-transform:uppercase;color:#B8B5B6;">Total Pendapatan</span>
            <span style="width:36px;height:36px;border-radius:10px;background:#C0410E;color:#fff;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="wallet" style="width:17px;height:17px;"></i>
            </span>
        </div>
        <div style="margin-top:14px;">
            <div style="font-size:23px;font-weight:800;color:#FFFFFF;"><?= rupiah($stats['total_pendapatan']) ?></div>
            <div style="font-size:12px;color:#A19EA0;margin-top:2px;">Akumulasi sewa & denda</div>
        </div>
    </div>
</div>

<!-- Chart & Fleet Status -->
<div class="grid-12" style="margin-bottom:24px;">
    <div class="ui-card col-8" style="padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <div>
                <h2 style="margin:0;font-size:16px;font-weight:700;">Grafik Pendapatan Penyewaan</h2>
                <p style="margin:2px 0 0;font-size:12.5px;color:#646163;">Pergerakan pendapatan berdasarkan tanggal mulai sewa</p>
            </div>
            <span style="background:#F8F7F3;border:1px solid #E5E9EC;padding:5px 12px;border-radius:9px;font-size:12px;font-weight:600;">Terbaru</span>
        </div>
        <div style="height:250px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="ui-card col-4" style="padding:24px;display:flex;flex-direction:column;justify-content:space-between;">
        <div>
            <h2 style="margin:0;font-size:16px;font-weight:700;">Komposisi Armada</h2>
            <p style="margin:2px 0 18px;font-size:12.5px;color:#646163;">Status kesiapan kendaraan saat ini</p>

            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="background:#F8F7F3;border:1px solid #E5E9EC;border-radius:12px;padding:13px 16px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:10px;font-weight:600;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#10B981;"></span> Tersedia
                    </span>
                    <strong style="font-size:15px;"><?= (int)$stats['kendaraan_tersedia'] ?> Unit</strong>
                </div>

                <div style="background:#F8F7F3;border:1px solid #E5E9EC;border-radius:12px;padding:13px 16px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:10px;font-weight:600;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#C0410E;"></span> Disewa
                    </span>
                    <strong style="font-size:15px;color:#C0410E;"><?= (int)$stats['kendaraan_disewa'] ?> Unit</strong>
                </div>

                <div style="background:#F8F7F3;border:1px solid #E5E9EC;border-radius:12px;padding:13px 16px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="display:flex;align-items:center;gap:10px;font-weight:600;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#F59E0B;"></span> Perawatan
                    </span>
                    <strong style="font-size:15px;"><?= (int)$stats['kendaraan_perawatan'] ?> Unit</strong>
                </div>
            </div>
        </div>

        <a href="<?= base_url('kendaraan') ?>" class="btn btn-warm" style="width:100%;margin-top:18px;">
            <span>Kelola Seluruh Unit</span>
            <i data-lucide="chevron-right" style="width:15px;height:15px;"></i>
        </a>
    </div>
</div>

<!-- Active Rentals Table -->
<div class="ui-card" style="overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid #E5E9EC;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
        <div>
            <h2 style="margin:0;font-size:16px;font-weight:700;">Penyewaan Aktif & Reservasi</h2>
            <p style="margin:2px 0 0;font-size:12.5px;color:#646163;">Daftar kendaraan yang sedang digunakan pelanggan atau menunggu serah kunci</p>
        </div>
        <a href="<?= base_url('penyewaan') ?>" style="font-size:13px;font-weight:700;color:#C0410E;">Lihat Semua &rarr;</a>
    </div>

    <div class="table-wrap">
        <table class="ui-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Periode</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transaksiBerjalan)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:#646163;">Tidak ada penyewaan aktif saat ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($transaksiBerjalan as $r): ?>
                    <tr>
                        <td><strong><?= e($r['kode_transaksi']) ?></strong></td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['nama_pelanggan']) ?></div>
                            <div style="font-size:12px;color:#646163;"><?= e($r['no_hp']) ?></div>
                        </td>
                        <td>
                            <div style="font-weight:600;"><?= e($r['merek'] . ' ' . $r['model']) ?></div>
                            <div style="font-size:12px;color:#646163;"><?= e($r['plat_nomor']) ?></div>
                        </td>
                        <td style="font-size:13px;color:#646163;">
                            <?= tgl_indo($r['tgl_sewa']) ?> &rarr; <?= tgl_indo($r['tgl_kembali_rencana']) ?>
                        </td>
                        <td><strong><?= rupiah($r['total_biaya']) ?></strong></td>
                        <td>
                            <span class="badge badge-<?= strtolower($r['status']) ?>">
                                <span class="badge-dot"></span><?= e($r['status']) ?>
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <a href="<?= base_url('penyewaan/' . $r['id']) ?>" class="btn btn-warm btn-sm">Detail</a>
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
                    hoverBackgroundColor: '#282627',
                    borderRadius: 8,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#E5E9EC' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
});
</script>
