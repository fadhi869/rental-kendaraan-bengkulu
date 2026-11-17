<?php
namespace App\Services;

use App\Models\Kendaraan;
use App\Models\Pengaturan;
use App\Models\Penyewaan;
use Auth;
use Database;
use Exception;

class PenyewaanService
{
    private Pengaturan $pengaturan;
    private Penyewaan $penyewaan;
    private Kendaraan $kendaraan;

    public function __construct()
    {
        $this->pengaturan = new Pengaturan();
        $this->penyewaan  = new Penyewaan();
        $this->kendaraan  = new Kendaraan();
    }

    public function getDendaMultiplier(): float
    {
        return $this->pengaturan->getDendaMultiplier();
    }

    public function generateKode(): string
    {
        return $this->penyewaan->generateKodeTransaksi();
    }

    public function createRental(array $input): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $unit = $this->kendaraan->findByIdWithLock((int)$input['kendaraan_id'], $pdo);
            if (!$unit) {
                throw new Exception('Kendaraan tidak ditemukan.');
            }

            $statusSewa = ($input['status'] ?? 'Berjalan') === 'Dipesan' ? 'Dipesan' : 'Berjalan';
            if ($statusSewa === 'Berjalan' && $unit['status'] !== 'Tersedia') {
                throw new Exception('Kendaraan sedang tidak tersedia untuk langsung disewa.');
            }

            $tglSewa = $input['tgl_sewa'];
            $tglKembali = $input['tgl_kembali_rencana'];
            if (strtotime($tglKembali) < strtotime($tglSewa)) {
                throw new Exception('Tanggal kembali tidak boleh lebih awal dari tanggal sewa.');
            }

            $days = max(1, (int)round((strtotime($tglKembali) - strtotime($tglSewa)) / 86400));
            $tarif = (float)$unit['tarif_per_hari'];
            $total = $tarif * $days;
            $kode = $this->penyewaan->generateKodeTransaksi();

            $newId = $this->penyewaan->createRecord([
                'kode_transaksi'      => $kode,
                'pelanggan_id'        => (int)$input['pelanggan_id'],
                'kendaraan_id'        => (int)$input['kendaraan_id'],
                'created_by'          => Auth::id(),
                'tgl_sewa'            => $tglSewa,
                'tgl_kembali_rencana' => $tglKembali,
                'tarif_saat_sewa'     => $tarif,
                'jumlah_hari'         => $days,
                'total_biaya'         => $total,
                'status'              => $statusSewa,
                'catatan'             => trim((string)($input['catatan'] ?? '')),
            ], $pdo);

            if ($statusSewa === 'Berjalan') {
                $this->kendaraan->updateStatus((int)$unit['id'], 'Disewa', $pdo);
            }

            ActivityLogger::log('BUAT_SEWA', 'penyewaan', "Membuat transaksi {$kode} untuk unit {$unit['plat_nomor']}.");
            $pdo->commit();
            return $newId;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function serahKunci(int $id): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $sewa = $this->penyewaan->findByIdWithLock($id, $pdo);
            if (!$sewa || $sewa['status'] !== 'Dipesan') {
                throw new Exception('Transaksi tidak valid untuk serah kunci.');
            }

            if ($sewa['k_status'] !== 'Tersedia') {
                throw new Exception('Kendaraan belum dalam status Tersedia.');
            }

            $this->penyewaan->updateStatus($id, 'Berjalan', $pdo);
            $this->kendaraan->updateStatus((int)$sewa['kendaraan_id'], 'Disewa', $pdo);

            ActivityLogger::log('SERAH_KUNCI', 'penyewaan', "Serah kunci unit {$sewa['plat_nomor']} untuk transaksi {$sewa['kode_transaksi']}.");
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function batalkan(int $id): void
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $sewa = $this->penyewaan->findByIdWithLock($id, $pdo);
            if (!$sewa || !in_array($sewa['status'], ['Dipesan', 'Berjalan'], true)) {
                throw new Exception('Transaksi tidak dapat dibatalkan.');
            }

            $this->penyewaan->updateStatus($id, 'Dibatalkan', $pdo);
            if ($sewa['status'] === 'Berjalan') {
                $this->kendaraan->updateStatus((int)$sewa['kendaraan_id'], 'Tersedia', $pdo);
            }

            ActivityLogger::log('BATAL_SEWA', 'penyewaan', "Membatalkan transaksi {$sewa['kode_transaksi']}.");
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function prosesPengembalian(int $id, string $tglAktual, string $catatan = ''): array
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $sewa = $this->penyewaan->findByIdWithLock($id, $pdo);
            if (!$sewa || $sewa['status'] !== 'Berjalan') {
                throw new Exception('Transaksi tidak berstatus Berjalan.');
            }

            $diffDays = (int)floor((strtotime($tglAktual) - strtotime($sewa['tgl_kembali_rencana'])) / 86400);
            $hariTerlambat = max(0, $diffDays);
            $multiplier = $this->getDendaMultiplier();
            $denda = $hariTerlambat * (float)$sewa['tarif_saat_sewa'] * $multiplier;

            $newNote = trim(($sewa['catatan'] ? $sewa['catatan'] . ' | ' : '') . $catatan);
            $this->penyewaan->completeRental($id, $tglAktual, $hariTerlambat, $denda, $newNote, $pdo);
            $this->kendaraan->updateStatus((int)$sewa['kendaraan_id'], 'Tersedia', $pdo);

            $dendaFormat = $denda > 0 ? ' (Denda: ' . rupiah($denda) . ')' : '';
            ActivityLogger::log('KEMBALIKAN', 'pengembalian', "Pengembalian unit {$sewa['plat_nomor']} ({$sewa['kode_transaksi']}){$dendaFormat}.");
            $pdo->commit();

            return ['hari_terlambat' => $hariTerlambat, 'denda' => $denda];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
