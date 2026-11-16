<?php
$flash = Flash::get();
$hasError = ($flash && $flash['type'] === 'error');
?>
<div class="auth-center-wrapper" id="authWrapper">
    <!-- Ambient Interactive Dot-Grid & Spotlight Background -->
    <div class="auth-grid-bg" aria-hidden="true"></div>
    <div class="auth-ambient-spotlight" id="authAmbientSpotlight" aria-hidden="true"></div>

    <div class="auth-card-clean <?= $hasError ? 'error-shake' : '' ?>" id="loginCard">
        <!-- Logo & Brand Header with Micro-Motion -->
        <div class="auth-header">
            <div class="auth-brand-badge" id="brandBadge" title="Armada Bengkulu">
                <i data-lucide="car" class="brand-car-icon" style="width:22px;height:22px;"></i>
            </div>
            <div class="auth-brand-name">Armada Bengkulu</div>
            <h1 class="auth-title">Masuk ke Dashboard</h1>
            <p class="auth-subtitle">Kelola operasional rental dalam satu tempat.</p>
        </div>

        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-error' ?> auth-alert-enter" style="margin-bottom:20px;">
                <span><?= e($flash['message']) ?></span>
                <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup notifikasi">&times;</button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="POST" id="loginForm" novalidate>
            <?= csrf_field() ?>

            <!-- Username / Email Field -->
            <div class="form-group-animated" id="groupUsername">
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
            <div class="form-group-animated" id="groupPassword">
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
                        <i data-lucide="eye" id="passwordToggleIcon" class="toggle-eye-icon" style="width:16px;height:16px;"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me with Springy Custom Checkmark -->
            <div class="login-options-row" id="rowOptions">
                <label class="custom-check-wrap" for="remember_me">
                    <input type="checkbox" id="remember_me" name="remember_me" class="custom-check-native">
                    <span class="custom-check-box" aria-hidden="true">
                        <svg class="check-icon" viewBox="0 0 14 14" fill="none">
                            <path class="check-path" d="M2.5 7.5L5.5 10.5L11.5 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="check-label-text">Ingat saya</span>
                </label>
            </div>

            <!-- Primary Submit Button with Tactile Physics, Shimmer & Spinner -->
            <button type="submit" id="btnLogin" class="btn-login-submit">
                <span class="btn-content">
                    <span class="btn-text">Masuk</span>
                    <i data-lucide="arrow-right" class="btn-icon" style="width:15px;height:15px;"></i>
                </span>
                <span class="btn-spinner" aria-hidden="true"></span>
            </button>
        </form>

        <div class="auth-footer-simple" id="authFooter">
            <span>&copy; <?= date('Y') ?> Armada Bengkulu &bull; Sistem Rental</span>
        </div>
    </div>
</div>
