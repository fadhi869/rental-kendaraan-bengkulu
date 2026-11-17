<?php
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\JenisKendaraanController;
use App\Controllers\KendaraanController;
use App\Controllers\PelangganController;
use App\Controllers\PenyewaanController;
use App\Controllers\PengembalianController;
use App\Controllers\RiwayatController;
use App\Controllers\LaporanController;
use App\Controllers\UserController;
use App\Controllers\ActivityController;

// Auth
$router->get('/', [AuthController::class, 'loginForm']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', [DashboardController::class, 'index']);

// Jenis Kendaraan / Kategori
$router->get('/jenis-kendaraan', [JenisKendaraanController::class, 'index']);
$router->get('/jenis-kendaraan/create', [JenisKendaraanController::class, 'create']);
$router->post('/jenis-kendaraan', [JenisKendaraanController::class, 'store']);
$router->get('/jenis-kendaraan/{id}/edit', [JenisKendaraanController::class, 'edit']);
$router->post('/jenis-kendaraan/{id}', [JenisKendaraanController::class, 'update']);
$router->post('/jenis-kendaraan/{id}/delete', [JenisKendaraanController::class, 'destroy']);
$router->get('/kategori', [JenisKendaraanController::class, 'index']);

// Kendaraan / Buku
$router->get('/kendaraan', [KendaraanController::class, 'index']);
$router->get('/kendaraan/create', [KendaraanController::class, 'create']);
$router->post('/kendaraan', [KendaraanController::class, 'store']);
$router->get('/kendaraan/{id}/edit', [KendaraanController::class, 'edit']);
$router->post('/kendaraan/{id}', [KendaraanController::class, 'update']);
$router->post('/kendaraan/{id}/delete', [KendaraanController::class, 'destroy']);
$router->get('/buku', [KendaraanController::class, 'index']);

// Pelanggan / Anggota
$router->get('/pelanggan', [PelangganController::class, 'index']);
$router->get('/pelanggan/create', [PelangganController::class, 'create']);
$router->post('/pelanggan', [PelangganController::class, 'store']);
$router->get('/pelanggan/{id}/edit', [PelangganController::class, 'edit']);
$router->post('/pelanggan/{id}', [PelangganController::class, 'update']);
$router->post('/pelanggan/{id}/delete', [PelangganController::class, 'destroy']);
$router->get('/anggota', [PelangganController::class, 'index']);

// Penyewaan / Peminjaman
$router->get('/penyewaan', [PenyewaanController::class, 'index']);
$router->get('/penyewaan/create', [PenyewaanController::class, 'create']);
$router->post('/penyewaan', [PenyewaanController::class, 'store']);
$router->get('/penyewaan/{id}', [PenyewaanController::class, 'show']);
$router->post('/penyewaan/{id}/serah-kunci', [PenyewaanController::class, 'serahKunci']);
$router->post('/penyewaan/{id}/batal', [PenyewaanController::class, 'batal']);
$router->get('/peminjaman', [PenyewaanController::class, 'index']);

// Pengembalian / Detail Peminjaman
$router->get('/pengembalian', [PengembalianController::class, 'index']);
$router->post('/pengembalian/{id}/proses', [PengembalianController::class, 'proses']);
$router->get('/detail-peminjaman', [PengembalianController::class, 'index']);

// Riwayat & Laporan
$router->get('/riwayat', [RiwayatController::class, 'index']);
$router->get('/laporan', [LaporanController::class, 'index']);
$router->get('/laporan/export', [LaporanController::class, 'export']);

// Admin: Manajemen User & Log Aktivitas
$router->get('/admin/users', [UserController::class, 'index']);
$router->get('/admin/users/create', [UserController::class, 'create']);
$router->post('/admin/users', [UserController::class, 'store']);
$router->get('/admin/users/{id}/edit', [UserController::class, 'edit']);
$router->post('/admin/users/{id}', [UserController::class, 'update']);
$router->post('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
$router->post('/admin/users/{id}/delete', [UserController::class, 'destroy']);
$router->get('/admin', [UserController::class, 'index']);
$router->get('/aktivitas', [ActivityController::class, 'index']);
