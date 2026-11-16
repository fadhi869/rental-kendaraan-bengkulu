-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Nov 2026 pada 14.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental_kendaraan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(40) NOT NULL,
  `module` varchar(60) NOT NULL,
  `description` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1, 2, 'LOGIN', 'auth', 'Administrator berhasil masuk ke sistem dari terminal kantor.', '127.0.0.1', '2026-11-15 01:00:15'),
(2, 2, 'TAMBAH', 'pelanggan', 'Menambahkan pelanggan baru: Dimas Prasetyo Utomo (NIK: 1771011503920001).', '127.0.0.1', '2026-11-15 01:15:30'),
(3, 1, 'LOGIN', 'auth', 'Petugas Ananda Rizki Pratama berhasil masuk ke sistem.', '192.168.1.104', '2026-11-15 01:30:00'),
(4, 1, 'BUAT_SEWA', 'penyewaan', 'Membuat transaksi sewa baru SEWA-202611-008 untuk kendaraan BD 1024 AY.', '192.168.1.104', '2026-11-15 01:45:10'),
(5, 4, 'LOGIN', 'auth', 'Petugas Siti Aisyah berhasil masuk ke sistem loket.', '192.168.1.105', '2026-11-15 02:00:00'),
(6, 4, 'SERAH_KUNCI', 'penyewaan', 'Melakukan serah kunci unit BD 2981 CT kepada pelanggan Siti Nurhaliza Putri.', '192.168.1.105', '2026-11-15 02:30:22'),
(7, 7, 'LOGIN', 'auth', 'Dispatcher Indra Gunawan masuk ke modul armada.', '192.168.1.108', '2026-11-15 03:15:00'),
(8, 7, 'UPDATE_STATUS', 'kendaraan', 'Mengubah status kendaraan BD 8891 TR menjadi Perawatan (servis berkala 20.000 km).', '192.168.1.108', '2026-11-15 03:20:45'),
(9, 2, 'KEMBALIKAN', 'pengembalian', 'Memproses pengembalian unit BD 4410 ER, denda 1 hari keterlambatan Rp 1.125.000 tercatat.', '127.0.0.1', '2026-11-15 04:10:00'),
(10, 3, 'LOGIN', 'auth', 'Manajer Bambang Wicaksono masuk ke dashboard analitik.', '192.168.1.102', '2026-11-15 06:00:00'),
(11, 3, 'EXPORT_LAPORAN', 'laporan', 'Mengekspor laporan pendapatan bulanan ke format CSV.', '192.168.1.102', '2026-11-15 06:15:40'),
(12, 8, 'LOGIN', 'auth', 'Staf Keuangan Rina Novita melakukan rekonsiliasi kas.', '192.168.1.103', '2026-11-15 07:00:00'),
(13, 5, 'INSPEKSI', 'kendaraan', 'Inspeksi fisik unit BD 1823 OB: ban, oli, dan rem dalam kondisi prima.', '192.168.1.106', '2026-11-15 08:10:18'),
(14, 1, 'BATAL_SEWA', 'penyewaan', 'Membatalkan pemesanan SEWA-202611-016 atas permohonan penyewa.', '192.168.1.104', '2026-11-15 09:00:05'),
(15, 2, 'UPDATE_USER', 'user_management', 'Memperbarui profil staf operasional dan status hak akses akun.', '127.0.0.1', '2026-11-15 10:05:00'),
(16, 2, 'LOGIN', 'auth', 'Super Administrator masuk ke portal pengawasan sistem.', '127.0.0.1', '2026-11-15 11:45:00'),
(17, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: User Unit Test (test_user_unit, Role: petugas)', '', '2026-11-15 12:13:23'),
(18, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794745149, Role: petugas)', '', '2026-11-15 12:19:09'),
(19, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794745162, Role: petugas)', '', '2026-11-15 12:19:22'),
(20, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 14: Staff QA Renamed (test_qa_1794745162, Role: petugas, Status: active)', '', '2026-11-15 12:19:22'),
(21, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794745162 menjadi inactive', '', '2026-11-15 12:19:22'),
(22, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794745162, ID: 14)', '', '2026-11-15 12:19:22'),
(23, 2, 'login', 'Autentikasi', 'Pengguna berhasil login.', '::1', '2026-11-15 12:19:41'),
(24, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794747785, Role: petugas)', '', '2026-11-15 13:03:05'),
(25, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 15: Staff QA Renamed (test_qa_1794747785, Role: petugas, Status: active)', '', '2026-11-15 13:03:05'),
(26, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794747785 menjadi inactive', '', '2026-11-15 13:03:05'),
(27, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794747785, ID: 15)', '', '2026-11-15 13:03:05'),
(28, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794748160, Role: petugas)', '', '2026-11-15 13:09:20'),
(29, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 16: Staff QA Renamed (test_qa_1794748160, Role: petugas, Status: active)', '', '2026-11-15 13:09:20'),
(30, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794748160 menjadi inactive', '', '2026-11-15 13:09:20'),
(31, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794748160, ID: 16)', '', '2026-11-15 13:09:20'),
(32, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794748526, Role: petugas)', '', '2026-11-15 13:15:26'),
(33, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 17: Staff QA Renamed (test_qa_1794748526, Role: petugas, Status: active)', '', '2026-11-15 13:15:26'),
(34, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794748526 menjadi inactive', '', '2026-11-15 13:15:26'),
(35, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794748526, ID: 17)', '', '2026-11-15 13:15:26'),
(36, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794748605, Role: petugas)', '', '2026-11-15 13:16:45'),
(37, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 18: Staff QA Renamed (test_qa_1794748605, Role: petugas, Status: active)', '', '2026-11-15 13:16:45'),
(38, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794748605 menjadi inactive', '', '2026-11-15 13:16:45'),
(39, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794748605, ID: 18)', '', '2026-11-15 13:16:45'),
(40, 2, 'TEST_RUN', 'automated_test', 'Verifikasi test suite berjalan sukses', '', '2026-11-15 13:17:08'),
(41, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794748632, Role: petugas)', '', '2026-11-15 13:17:12'),
(42, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 19: Staff QA Renamed (test_qa_1794748632, Role: petugas, Status: active)', '', '2026-11-15 13:17:12'),
(43, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794748632 menjadi inactive', '', '2026-11-15 13:17:12'),
(44, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794748632, ID: 19)', '', '2026-11-15 13:17:12'),
(45, 2, 'TAMBAH_USER', 'user_management', 'Menambahkan pengguna baru: Staff QA Verification (test_qa_1794748679, Role: petugas)', '', '2026-11-15 13:17:59'),
(46, 2, 'UPDATE_USER', 'user_management', 'Memperbarui akun pengguna ID 20: Staff QA Renamed (test_qa_1794748679, Role: petugas, Status: active)', '', '2026-11-15 13:17:59'),
(47, 2, 'STATUS_USER', 'user_management', 'Mengubah status pengguna test_qa_1794748679 menjadi inactive', '', '2026-11-15 13:17:59'),
(48, 2, 'HAPUS_USER', 'user_management', 'Menonaktifkan / menghapus akun pengguna: Staff QA Renamed (test_qa_1794748679, ID: 20)', '', '2026-11-15 13:17:59'),
(49, 2, 'TEST_RUN', 'automated_test', 'Verifikasi test suite berjalan sukses', '', '2026-11-15 13:17:59'),
(50, 1, 'login', 'Autentikasi', 'Pengguna berhasil login.', '::1', '2026-11-15 13:24:43'),
(51, 1, 'logout', 'Autentikasi', 'Pengguna keluar dari aplikasi.', '::1', '2026-11-15 13:26:02'),
(52, 2, 'login', 'Autentikasi', 'Pengguna berhasil login.', '::1', '2026-11-15 13:26:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_kendaraan`
--

CREATE TABLE `jenis_kendaraan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_jenis` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_kendaraan`
--

INSERT INTO `jenis_kendaraan` (`id`, `nama_jenis`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Sepeda Motor Matic', 'Motor matic irit & gesit untuk mobilitas harian dalam kota (110-125cc).', '2026-11-15 07:58:56', '2026-11-15 12:08:37'),
(2, 'Sepeda Motor Sport & Maxi', 'Motor maxi premium bertenaga untuk touring dan perjalanan nyaman (155-250cc).', '2026-11-15 07:58:56', '2026-11-15 12:08:37'),
(3, 'Mobil City Car / Hatchback', 'Mobil kompak irit bahan bakar, lincah bermanuver dan mudah parkir di pusat kota.', '2026-11-15 07:58:56', '2026-11-15 12:08:37'),
(4, 'Mobil Compact MPV', 'Mobil keluarga favorit kapasitas 7 penumpang dengan bagasi luas dan AC double blower.', '2026-11-15 07:58:56', '2026-11-15 12:08:37'),
(5, 'Mobil Medium & Premium MPV', 'Mobil MPV premium eksekutif (Innova Zenix) untuk perjalanan dinas, tamu VIP & keluarga besar.', '2026-11-15 07:58:56', '2026-11-15 12:08:37'),
(6, 'Mobil SUV Adventure', 'Kendaraan tangguh berpenggerak handal untuk perjalanan dinas luar kota dan medan berbukit.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(7, 'Mobil Niaga & Pick-up', 'Kendaraan angkut barang dan logistik bak terbuka untuk kebutuhan pindahan dan niaga.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(8, 'Minibus Pariwisata', 'Minibus kapasitas 14-16 penumpang untuk rombongan wisata dan instansi pemerintahan.', '2026-11-15 12:08:37', '2026-11-15 12:08:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(10) UNSIGNED NOT NULL,
  `plat_nomor` varchar(15) NOT NULL,
  `merek` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `tahun` smallint(5) UNSIGNED NOT NULL,
  `warna` varchar(30) NOT NULL,
  `tarif_per_hari` decimal(12,2) NOT NULL,
  `status` enum('Tersedia','Disewa','Perawatan') NOT NULL DEFAULT 'Tersedia',
  `jenis_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `plat_nomor`, `merek`, `model`, `tahun`, `warna`, `tarif_per_hari`, `status`, `jenis_id`, `created_at`, `updated_at`) VALUES
(1, 'BD 5539 CG', 'Yamaha', 'Mio M3 125', 2021, 'Putih Solid', 90000.00, 'Tersedia', 1, '2026-11-15 08:11:25', '2026-11-15 12:08:37'),
(2, 'BD 1024 AY', 'Yamaha', 'All New NMAX 155 Connected', 2023, 'Hitam Doff', 140000.00, 'Disewa', 2, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(3, 'BD 2981 CT', 'Toyota', 'All New Avanza 1.5 G CVT', 2023, 'Silver Mica Metallic', 400000.00, 'Disewa', 4, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(4, 'BD 4410 ER', 'Toyota', 'Kijang Innova Zenix 2.0 V', 2024, 'Hitam Metalik', 750000.00, 'Disewa', 5, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(5, 'BD 8172 KZ', 'Toyota', 'Fortuner 2.8 GR Sport 4x2', 2023, 'Abu-abu Grafit Metalik', 1100000.00, 'Tersedia', 6, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(6, 'BD 3901 LW', 'Honda', 'Brio Satya E CVT', 2022, 'Rallye Red (Merah)', 320000.00, 'Tersedia', 3, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(7, 'BD 6214 MB', 'Mitsubishi', 'Xpander Ultimate CVT', 2023, 'Putih Quartz Pearl', 450000.00, 'Disewa', 4, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(8, 'BD 7780 NA', 'Honda', 'HR-V 1.5 SE CVT', 2023, 'Sand Khaki Pearl', 650000.00, 'Tersedia', 6, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(9, 'BD 1823 OB', 'Daihatsu', 'Gran Max Pick-up 1.5 AC/PS', 2022, 'Hitam Pekat', 275000.00, 'Tersedia', 7, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(10, 'BD 9015 PK', 'Toyota', 'HiAce Commuter 2.5 Manual', 2022, 'Silver Metallic', 1250000.00, 'Tersedia', 8, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(11, 'BD 3342 QS', 'Honda', 'PCX 160 ABS', 2023, 'Biru Dongker Metalik', 1450000.00, 'Tersedia', 2, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(12, 'BD 8891 TR', 'Toyota', 'Yaris GR Sport CVT', 2022, 'Super White II', 380000.00, 'Perawatan', 3, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(13, 'BD 4120 VX', 'Suzuki', 'All New Ertiga Hybrid GX', 2023, 'Brave Khaki Pearl', 420000.00, 'Tersedia', 4, '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(14, 'BD 2011 WY', 'Mitsubishi', 'Pajero Sport Dakar 4x2', 2022, 'Deep Bronze Metallic', 1150000.00, 'Perawatan', 6, '2026-11-15 12:08:37', '2026-11-15 12:08:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(10) UNSIGNED NOT NULL,
  `nik` char(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_sim` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nik`, `nama`, `alamat`, `no_hp`, `no_sim`, `email`, `created_at`, `updated_at`) VALUES
(1, '1771011503920001', 'Dimas Prasetyo Utomo', 'Jl. Padang Harapan No. 14, Gading Cempaka, Kota Bengkulu', '081271829910', 'SIM-A-920315-01', 'dimas.prasetyo@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(2, '1771026008940003', 'Rina Kartika Sari, S.Pd', 'Jl. Adam Malik Km. 8, Pagar Dewa, Selebar, Kota Bengkulu', '085269104421', 'SIM-A-940820-02', 'rina.kartika@yahoo.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(3, '1771031206890002', 'Bagus Wahyudi', 'Jl. Mayjen Sutoyo No. 88, Tanah Patah, Kota Bengkulu', '082182903314', 'SIM-C-890612-04', 'bagus.wahyudi89@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(4, '1771015011960005', 'Siti Nurhaliza Putri', 'Jl. Suprapto No. 42, Kel. Ratu Samban, Kota Bengkulu', '081373449102', 'SIM-A-961110-03', 'siti.nurhaliza.p@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(5, '1771042407850001', 'Hendra Gunawan', 'Jl. Danau Dendam No. 7, Dusun Besar, Singaran Pati, Kota Bengkulu', '08117300912', 'SIM-A-850724-05', 'hendra.gunawan.bkl@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(6, '1771021909980004', 'Aditya Pratama', 'Jl. Merapi Raya No. 23, Panorama, Kota Bengkulu', '085382019932', 'SIM-C-980919-01', 'aditya.pratama98@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(7, '1771034404930006', 'dr. Fitriani Wulandari', 'Jl. Salak Raya No. 15, Lingkar Timur, Kota Bengkulu', '081278901123', 'SIM-A-930404-06', 'dr.fitriani.w@klinikbengkulu.id', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(8, '1771010101900007', 'Ahmad Fauzi Rahman', 'Jl. Pariwisata Pantai Panjang No. 5, Kota Bengkulu', '082281905543', 'SIM-A-900101-08', 'ahmad.fauzi.r@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(9, '1771025512950002', 'Dewi Lestari Kusuma', 'Jl. Kalimantan No. 34, Rawa Makmur, Muara Bangka Hulu', '085273118890', 'SIM-A-951215-09', 'dewi.lestari95@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(10, '1771031005880003', 'Tri Cahyo Nugroho', 'Jl. Bhayangkara No. 19, Sidomulyo, Kota Bengkulu', '081369024411', 'SIM-A-880510-10', 'tri.cahyo.n@bengkulu-logistics.co.id', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(11, '1771012202970008', 'Muhammad Rizki Maulana', 'Jl. Ciliwung No. 11, Padang Harapan, Kota Bengkulu', '081273998812', 'SIM-C-970222-07', 'rizki.maulana97@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(12, '1771026809910005', 'Maya Indah Permata', 'Jl. Sedap Malam No. 8, Nusa Indah, Kota Bengkulu', '085377889901', 'SIM-A-910928-11', 'maya.indah.permata@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(13, '1771040510860002', 'Eko Budi Setiawan', 'Jl. WR Supratman No. 50, Kandang Limun, Kota Bengkulu', '08117399201', 'SIM-A-861005-12', 'eko.setiawan@unib.ac.id', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(14, '1771035201990001', 'Ratna Juwita Sari', 'Jl. Semangka No. 9, Panorama, Singaran Pati, Kota Bengkulu', '082381203344', 'SIM-A-990112-13', 'ratna.juwita99@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(15, '1771011808870004', 'Bambang Triyono', 'Jl. Raden Fatah No. 17, Pagar Dewa, Kota Bengkulu', '081271109922', 'SIM-A-870818-14', 'bambang.triyono@gmail.com', '2026-11-15 12:08:37', '2026-11-15 12:08:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `kunci` varchar(50) NOT NULL,
  `nilai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`kunci`, `nilai`) VALUES
('alamat_usaha', 'Jl. Jenderal Sudirman No. 10, Kel. Pintu Batu, Kota Bengkulu'),
('denda_multiplier', '1.5'),
('email_usaha', 'kontak@armadabengkulu.com'),
('nama_usaha', 'Rental Armada Bengkulu'),
('pengali_denda', '1.5'),
('rekening_bank', 'Bank Mandiri 113-00-1928374-1 a.n PT Armada Bengkulu Pratama'),
('telepon_usaha', '0736-22819');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_transaksi` varchar(20) NOT NULL,
  `pelanggan_id` int(10) UNSIGNED NOT NULL,
  `kendaraan_id` int(10) UNSIGNED NOT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `tgl_sewa` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `tarif_saat_sewa` decimal(12,2) NOT NULL,
  `jumlah_hari` int(10) UNSIGNED NOT NULL,
  `total_biaya` decimal(14,2) NOT NULL,
  `hari_terlambat` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `denda` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('Dipesan','Berjalan','Selesai','Dibatalkan') NOT NULL DEFAULT 'Berjalan',
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penyewaan`
--

INSERT INTO `penyewaan` (`id`, `kode_transaksi`, `pelanggan_id`, `kendaraan_id`, `created_by`, `tgl_sewa`, `tgl_kembali_rencana`, `tgl_kembali_aktual`, `tarif_saat_sewa`, `jumlah_hari`, `total_biaya`, `hari_terlambat`, `denda`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'SEWA-202611-001', 1, 1, 1, '2026-10-31', '2026-11-03', '2026-11-03', 90000.00, 3, 270000.00, 0, 0.00, 'Selesai', 'Unit dikembalikan tepat waktu, kondisi bensin penuh & bersih.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(2, 'SEWA-202611-002', 2, 3, 4, '2026-11-03', '2026-11-05', '2026-11-05', 400000.00, 2, 800000.00, 0, 0.00, 'Selesai', 'Perjalanan dinas ke Curup, unit mulus tanpa lecet.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(3, 'SEWA-202611-003', 5, 5, 2, '2026-11-05', '2026-11-08', '2026-11-08', 1100000.00, 3, 3300000.00, 0, 0.00, 'Selesai', 'Kunjungan kerja Pemda ke Mukomuko. Pembayaran transfer lunas.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(4, 'SEWA-202611-004', 7, 4, 4, '2026-11-07', '2026-11-10', '2026-11-11', 750000.00, 3, 2250000.00, 1, 1125000.00, 'Selesai', 'Terlambat 1 hari karena kendala cuaca di Kepahiang. Denda 1,5x tarif harian telah dilunasi di loket.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(5, 'SEWA-202611-005', 8, 6, 1, '2026-11-08', '2026-11-11', '2026-11-11', 320000.00, 3, 960000.00, 0, 0.00, 'Selesai', 'Wisata keliling Kota Bengkulu.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(6, 'SEWA-202611-006', 10, 9, 7, '2026-11-10', '2026-11-12', '2026-11-12', 275000.00, 2, 550000.00, 0, 0.00, 'Selesai', 'Angkutan pindahan kantor cabang.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(7, 'SEWA-202611-007', 13, 10, 2, '2026-11-07', '2026-11-10', '2026-11-10', 1250000.00, 3, 3750000.00, 0, 0.00, 'Selesai', 'Rombongan dosen UNIB ke Danau Mas Harun Bastari.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(8, 'SEWA-202611-008', 3, 2, 1, '2026-11-14', '2026-11-17', NULL, 140000.00, 3, 420000.00, 0, 0.00, 'Berjalan', 'Sewa motor NMAX untuk keliling meeting area Pantai Panjang.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(9, 'SEWA-202611-009', 4, 3, 4, '2026-11-13', '2026-11-16', NULL, 400000.00, 3, 1200000.00, 0, 0.00, 'Berjalan', 'Acara keluarga di Bengkulu Tengah, jaminan KTP asli disimpan kasir.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(10, 'SEWA-202611-010', 6, 4, 2, '2026-11-14', '2026-11-18', NULL, 750000.00, 4, 3000000.00, 0, 0.00, 'Berjalan', 'Sewa Innova Zenix untuk kunjungan investor perhotelan.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(11, 'SEWA-202611-011', 9, 7, 5, '2026-11-10', '2026-11-14', NULL, 450000.00, 4, 1800000.00, 1, 675000.00, 'Berjalan', 'PERHATIAN: Lewat 1 hari dari jadwal. Penyewa konfirmasi sedang dalam perjalanan pulang dari Manna.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(12, 'SEWA-202611-012', 11, 5, 1, '2026-11-17', '2026-11-20', NULL, 1100000.00, 3, 3300000.00, 0, 0.00, 'Dipesan', 'Booking Fortuner untuk tamu dinas kementerian. DP 50% diterima.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(13, 'SEWA-202611-013', 12, 6, 4, '2026-11-18', '2026-11-22', NULL, 320000.00, 4, 1280000.00, 0, 0.00, 'Dipesan', 'Reservasi liburan akhir pekan.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(14, 'SEWA-202611-014', 14, 13, 7, '2026-11-16', '2026-11-18', NULL, 420000.00, 2, 840000.00, 0, 0.00, 'Dipesan', 'Ertiga Hybrid siap serah kunci besok pagi jam 08:00.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(15, 'SEWA-202611-015', 15, 8, 2, '2026-11-12', '2026-11-14', NULL, 650000.00, 2, 1300000.00, 0, 0.00, 'Dibatalkan', 'Penyewa membatalkan sewa karena ada musibah keluarga, tidak ada biaya dipungut.', '2026-11-15 12:08:37', '2026-11-15 12:08:37'),
(16, 'SEWA-202611-016', 2, 11, 1, '2026-11-11', '2026-11-13', NULL, 145000.00, 2, 290000.00, 0, 0.00, 'Dibatalkan', 'Pembatalan jadwal perjalanan ke luar kota oleh penyewa.', '2026-11-15 12:08:37', '2026-11-15 12:08:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','petugas') NOT NULL DEFAULT 'petugas',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`, `role`, `status`, `avatar`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ananda', '$2y$10$LDk.DHz28PM.RXTnreY9d.YtCPj5ML2t1cCh.u0GcgdopGhjNm3uS', 'Ananda Rizki Pratama', 'ananda@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-15 20:24:43', '2026-11-15 08:05:33', '2026-11-15 13:24:43', NULL),
(2, 'admin', '$2y$10$YbVcc8BeombUH60uyeKbJOT.bpjWn.WNMn4Hi3GmufZ8yJgu0vOsW', 'Super Administrator', 'admin@armadabengkulu.com', 'admin', 'active', NULL, '2026-11-15 20:26:05', '2026-11-15 08:08:36', '2026-11-15 13:26:05', NULL),
(3, 'bambang_ops', '$2y$10$OmsRZQAMcoPQz0txqypwK.szysJCVgl1foR5vkhQq1BZzbaoBZF3e', 'Bambang Wicaksono, S.T.', 'bambang@armadabengkulu.com', 'admin', 'active', NULL, '2026-11-15 16:10:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(4, 'siti_kasir', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Siti Aisyah Rahmawati', 'siti.aisyah@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-15 14:22:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(5, 'wahyu_fleet', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Wahyu Hidayat', 'wahyu.fleet@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-15 12:05:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(6, 'dina_adm', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Dina Marisa Putri', 'dina.adm@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-14 16:40:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(7, 'indra_dispatch', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Indra Gunawan', 'indra.dispatch@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-15 11:15:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(8, 'rina_keuangan', '$2y$10$OmsRZQAMcoPQz0txqypwK.szysJCVgl1foR5vkhQq1BZzbaoBZF3e', 'Rina Novita, S.E.', 'rina.keuangan@armadabengkulu.com', 'admin', 'active', NULL, '2026-11-15 15:50:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(9, 'fajar_petugas', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Fajar Ramadhan', 'fajar@armadabengkulu.com', 'petugas', 'active', NULL, '2026-11-13 09:30:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(10, 'agus_nonaktif', '$2y$10$uQJ04MJ8gfYs9IECI48fD.P2lP.tfjA1ntc.YXtkYELmlIRx./9Aq', 'Agus Santoso', 'agus.santoso@armadabengkulu.com', 'petugas', 'inactive', NULL, '2026-10-20 10:00:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(11, 'maya_auditor', '$2y$10$OmsRZQAMcoPQz0txqypwK.szysJCVgl1foR5vkhQq1BZzbaoBZF3e', 'Maya Anggraini, M.M.', 'maya.auditor@armadabengkulu.com', 'admin', 'active', NULL, '2026-11-15 08:20:00', '2026-11-15 12:08:37', '2026-11-15 12:08:37', NULL),
(13, 'test_qa_1794745149', '$2y$10$dWD25I8FxLGcpuLZesnTg.ndKX3b7f7LxMkLjLEIBiLYZTUGeUguW', 'Staff QA Verification', 'qa_1794745149@armadabengkulu.com', 'petugas', 'active', NULL, NULL, '2026-11-15 12:19:09', '2026-11-15 12:19:09', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activity_created` (`created_at`),
  ADD KEY `idx_activity_user` (`user_id`);

--
-- Indeks untuk tabel `jenis_kendaraan`
--
ALTER TABLE `jenis_kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_jenis_nama` (`nama_jenis`);

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kendaraan_plat` (`plat_nomor`),
  ADD KEY `idx_kendaraan_status` (`status`),
  ADD KEY `idx_kendaraan_jenis` (`jenis_id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_pelanggan_nik` (`nik`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`kunci`);

--
-- Indeks untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_penyewaan_kode` (`kode_transaksi`),
  ADD KEY `idx_penyewaan_kendaraan_status` (`kendaraan_id`,`status`),
  ADD KEY `idx_penyewaan_pelanggan` (`pelanggan_id`),
  ADD KEY `idx_penyewaan_tanggal` (`tgl_sewa`,`tgl_kembali_rencana`),
  ADD KEY `fk_penyewaan_user` (`created_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `jenis_kendaraan`
--
ALTER TABLE `jenis_kendaraan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `fk_kendaraan_jenis` FOREIGN KEY (`jenis_id`) REFERENCES `jenis_kendaraan` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD CONSTRAINT `fk_penyewaan_kendaraan` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penyewaan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penyewaan_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
