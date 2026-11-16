<div class="auth-wrapper">
    <div class="auth-box">
        <div class="auth-form-side">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:30px;">
                    <div class="brand-badge">
                        <i data-lucide="car" style="width:18px;height:18px;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:15px;color:var(--text-primary);line-height:1.2;">Armada Bengkulu</div>
                        <div style="font-size:11.5px;color:var(--text-secondary);">Rental Kendaraan</div>
                    </div>
                </div>

                <h1 style="margin:0 0 6px;font-size:22px;font-weight:700;color:var(--text-primary);letter-spacing:-0.015em;">Masuk ke Akun</h1>
                <p style="margin:0 0 24px;color:var(--text-secondary);font-size:13px;">Silakan masukkan username dan password Anda untuk melanjutkan.</p>

                <?php require BASE_PATH . '/app/Views/partials/flash.php'; ?>

                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="ui-input" placeholder="Masukkan username Anda" required autofocus>
                    </div>
                    <div class="form-group" style="margin-bottom:20px;">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="ui-input" placeholder="Masukkan password Anda" required>
                    </div>
                    <button type="submit" class="btn btn-dark" style="width:100%;padding:10px 14px;font-size:13.5px;">
                        <span>Masuk</span>
                        <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
                    </button>
                </form>
            </div>

            <div style="margin-top:28px;padding-top:16px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:11.5px;color:var(--text-muted);">
                <span>Rental Armada Bengkulu &copy; <?= date('Y') ?></span>
                <span>Aplikasi v2.0</span>
            </div>
        </div>

        <div class="auth-info-side">
            <div>
                <span style="display:inline-block;background:var(--accent);color:#fff;padding:4px 10px;border-radius:var(--radius-full);font-size:11px;font-weight:700;letter-spacing:0.03em;">
                    Sistem Manajemen Rental
                </span>
                <h2 style="font-size:22px;font-weight:700;line-height:1.35;margin:18px 0 10px;color:#FFFFFF;">
                    Pengelolaan unit dan transaksi sewa yang rapi dan teratur.
                </h2>
                <p style="color:#B8B5B6;font-size:13px;line-height:1.6;margin:0;">
                    Catat pemesanan pelanggan, cek ketersediaan armada, proses pengembalian unit, dan pantau ringkasan pendapatan dalam satu sistem.
                </p>
            </div>

            <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius-md);padding:16px;">
                <div style="font-size:11px;color:#9E9B9C;font-weight:600;margin-bottom:10px;text-transform:uppercase;letter-spacing:0.04em;">
                    Status Kesiapan Unit
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                    <div style="background:rgba(0,0,0,0.25);padding:10px;border-radius:var(--radius-sm);">
                        <div style="font-size:10.5px;color:#A8A5A6;">Siap Pakai</div>
                        <div style="font-size:15px;font-weight:700;color:#10B981;margin-top:2px;">Tersedia</div>
                    </div>
                    <div style="background:rgba(0,0,0,0.25);padding:10px;border-radius:var(--radius-sm);">
                        <div style="font-size:10.5px;color:#A8A5A6;">Sedang Jalan</div>
                        <div style="font-size:15px;font-weight:700;color:#FF8A5E;margin-top:2px;">Disewa</div>
                    </div>
                    <div style="background:rgba(0,0,0,0.25);padding:10px;border-radius:var(--radius-sm);">
                        <div style="font-size:10.5px;color:#A8A5A6;">Bengkel</div>
                        <div style="font-size:15px;font-weight:700;color:#FBBF24;margin-top:2px;">Perawatan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
