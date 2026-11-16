<?php
$flash = Flash::get();
$hasError = ($flash && $flash['type'] === 'error');
?>
<div class="auth-center-wrapper">
    <div class="auth-card-clean <?= $hasError ? 'error-shake' : '' ?>" id="loginCard">
        <!-- Logo & Brand Header -->
        <div class="auth-header">
            <div class="auth-brand-badge">
                <i data-lucide="car" style="width:22px;height:22px;"></i>
            </div>
            <div class="auth-brand-name">Armada Bengkulu</div>
            <h1 class="auth-title">Masuk ke Dashboard</h1>
            <p class="auth-subtitle">Kelola operasional rental dalam satu tempat.</p>
        </div>

        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?>" style="margin-bottom:20px;">
                <span><?= e($flash['message']) ?></span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="POST" id="loginForm" novalidate>
            <?= csrf_field() ?>

            <!-- Username / Email Field -->
            <div class="form-group-animated">
                <label class="floating-label" for="username">Username atau Email</label>
                <div class="input-focus-wrap">
                    <span class="input-leading-icon">
                        <i data-lucide="user" style="width:16px;height:16px;"></i>
                    </span>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="animated-input" 
                        placeholder="Masukkan username atau email" 
                        required 
                        autofocus
                        autocomplete="username"
                    >
                </div>
            </div>

            <!-- Password Field with Interactive Eye Toggle -->
            <div class="form-group-animated">
                <label class="floating-label" for="password">Kata Sandi</label>
                <div class="input-focus-wrap" id="passwordWrap">
                    <span class="input-leading-icon">
                        <i data-lucide="lock" style="width:16px;height:16px;"></i>
                    </span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="animated-input input-has-trailing" 
                        placeholder="Masukkan kata sandi" 
                        required 
                        autocomplete="current-password"
                    >
                    <button 
                        type="button" 
                        id="passwordToggleBtn" 
                        class="password-toggle-btn" 
                        aria-label="Tampilkan atau sembunyikan kata sandi"
                        tabindex="-1"
                    >
                        <i data-lucide="eye" id="passwordToggleIcon" style="width:16px;height:16px;"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="login-options-row">
                <label class="remember-label" for="remember_me">
                    <input type="checkbox" id="remember_me" name="remember_me" class="custom-checkbox">
                    <span>Ingat saya</span>
                </label>
            </div>

            <!-- Primary Submit Button with Tactile Physics & Spinner -->
            <button type="submit" id="btnLogin" class="btn-login-submit">
                <span class="btn-content">
                    <span class="btn-text">Masuk</span>
                    <i data-lucide="arrow-right" class="btn-icon" style="width:15px;height:15px;"></i>
                </span>
                <span class="btn-spinner hidden" aria-hidden="true"></span>
            </button>
        </form>

        <div class="auth-footer-simple">
            <span>&copy; <?= date('Y') ?> Armada Bengkulu &bull; Sistem Rental</span>
        </div>
    </div>
</div>
