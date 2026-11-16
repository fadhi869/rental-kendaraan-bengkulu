<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:36px;">
                    <div class="brand-icon">
                        <i data-lucide="car-front" style="width:20px;height:20px;"></i>
                    </div>
                    <div>
                        <div style="font-weight:800;font-size:16px;color:#282627;">Armada Bengkulu</div>
                        <div style="font-size:12px;color:#646163;">Sistem Manajemen Rental Kendaraan</div>
                    </div>
                </div>

                <h1 style="margin:0 0 8px;font-size:26px;font-weight:800;letter-spacing:-0.02em;color:#282627;">Selamat Datang</h1>
                <p style="margin:0 0 28px;color:#646163;font-size:14px;">Masuk menggunakan akun petugas atau administrator untuk mengelola armada dan transaksi.</p>

                <?php require BASE_PATH . '/app/Views/partials/flash.php'; ?>

                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="ui-input" placeholder="Masukkan username" required autofocus>
                    </div>
                    <div class="form-group" style="margin-bottom:24px;">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="ui-input" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" class="btn btn-dark" style="width:100%;padding:12px;">
                        <span>Masuk ke Portal</span>
                        <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                    </button>
                </form>
            </div>

            <div style="margin-top:32px;padding-top:18px;border-top:1px solid #E5E9EC;display:flex;justify-content:space-between;font-size:12px;color:#646163;">
                <span>&copy; <?= date('Y') ?> Rental Armada Bengkulu</span>
                <span style="font-weight:600;color:#282627;">v2.0</span>
            </div>
        </div>

        <div class="auth-right">
            <div>
                <span style="display:inline-flex;align-items:center;gap:6px;background:#C0410E;color:#fff;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:700;">
                    Operasional Terintegrasi
                </span>
                <h2 style="font-size:28px;font-weight:800;line-height:1.25;margin:20px 0 12px;color:#FFFFFF;">
                    Kendali Penuh Armada & Penyewaan dalam Satu Dashboard.
                </h2>
                <p style="color:#B8B5B6;font-size:14px;line-height:1.6;margin:0;">
                    Pantau unit tersedia, jadwal pengembalian, denda keterlambatan otomatis, dan laporan pendapatan harian.
                </p>
            </div>

            <div style="background:#333132;border:1px solid rgba(255,255,255,0.08);border-radius:18px;padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <span style="font-size:12px;color:#B8B5B6;font-weight:600;">Ringkasan Armada</span>
                    <span style="font-size:11px;background:rgba(192,65,14,0.2);color:#FF8B5E;padding:3px 10px;border-radius:999px;font-weight:700;">Real-Time</span>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                    <div style="background:#282627;padding:12px;border-radius:12px;">
                        <div style="font-size:11px;color:#9E9B9C;">Siap Sewa</div>
                        <div style="font-size:18px;font-weight:800;color:#fff;margin-top:4px;">Tersedia</div>
                    </div>
                    <div style="background:#282627;padding:12px;border-radius:12px;">
                        <div style="font-size:11px;color:#9E9B9C;">Aktif</div>
                        <div style="font-size:18px;font-weight:800;color:#C0410E;margin-top:4px;">Disewa</div>
                    </div>
                    <div style="background:#282627;padding:12px;border-radius:12px;">
                        <div style="font-size:11px;color:#9E9B9C;">Servis</div>
                        <div style="font-size:18px;font-weight:800;color:#FBBF24;margin-top:4px;">Terpantau</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
