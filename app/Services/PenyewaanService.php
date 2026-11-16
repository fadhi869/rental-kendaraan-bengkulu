<?php
namespace App\Services;

use Auth;
use Database;
use Exception;

class PenyewaanService
{
    public function getDendaMultiplier(): float
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT nilai FROM pengaturan WHERE kunci IN ('denda_multiplier', 'pengali_denda') LIMIT 1");
        $stmt->execute();
        $val = $stmt->fetchColumn();
        return $val ? (float)$val : 1.5;
    }

    public function generateKode(): string
    {
        $pdo = Database::connection();
        $prefix = 'SEWA-' . date('Ym') . '-';
        $stmt = $pdo->prepare("SELECT kode_transaksi FROM penyewaan WHERE kode_transaksi LIKE :p ORDER BY id DESC LIMIT 1");
        $stmt->execute(['p' => $prefix . '%']);
        $last = $stmt->fetchColumn();
        $next = 1;
        if ($last) {
            $num = (int)substr($last, strlen($prefix));
            $next = $num + 1;
        }
        return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    public function createRental(array $input): int
    {
        $pdo = Database::connection();
        $pdo->beginTransaction();
        try {
            $stmtK = $pdo->prepare("SELECT * FROM kendaraan WHERE id = :id FOR UPDATE");
            $stmtK->execute(['id' => (int)$input['kendaraan_id']]);
            $kendaraan = $stmtK->fetch();
            if (!$kendaraan) {
                throw new Exception('Kendaraan tidak ditemukan.');
            }

            $statusSewa = ($input['status'] ?? 'Berjalan') === 'Dipesan' ? 'Dipesan' : 'Berjalan';
            if ($statusSewa === 'Berjalan' && $kendaraan['status'] !== 'Tersedia') {
                throw new Exception('Unit kendaraan tidak dalam status Tersedia.');
            }

            $tglSewa = $input['tgl_sewa'];
            $tglKembali = $input['tgl_kembali_rencana'];
            $days = max(1, (int)round((strtotime($tglKembali) - strtotime($tglSewa)) / 86400));
            $tarif = (float)$kendaraan['tarif_saat_sewa'] ?: (float)$kendaraan['tarif_per_hari'];
            $total = $tarif * $days;
            $kode = $this->generateKode();

            $stmt = $pdo->prepare("
                INSERT INTO penyewaan
                (kode_transaksi, pelanggan_id, kendaraan_id, created_by, tgl_sewa, tgl_kembali_rencana, tarif_saat_sewa, jumlah_hari, total_biaya, status, catatan)
                VALUES
                (:kode, :pid, :kid, :uid, :tgl_sewa, :tgl_kembali, :tarif, :hari, :total, :status, :catatan)
            ");
            $stmt->execute([
                'kode'        => $kode,
                'pid'         => (int)$input['pelanggan_id'],
                'kid'         => (int)$input['kendaraan_id'],
                'uid'         => Auth::id(),
                'tgl_sewa'    => $tglSewa,
                'tgl_kembali' => $tglKembali,
                'tarif'       => $tarif,
                'hari'        => $days,
                'total'       => $total,
                'status'      => $statusSewa,
                'catatan'     => trim((string)($input['catatan'] ?? '')),
            ]);
            $newId = (int)$pdo->lastInsertId();

            if ($statusSewa === 'Berjalan') {
                $pdo->prepare("UPDATE kendaraan SET status = 'Disewa' WHERE id = :id")
                    ->execute(['id' => $kendaraan['id']]);
            }

            ActivityLogger::log('BUAT_SEWA', 'penyewaan', "Membuat transaksi {$kode} untuk unit {$kendaraan['plat_nomor']}.");
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
            $stmt = $pdo->prepare("SELECT p.*, k.plat_nomor, k.status AS k_status FROM penyewaan p JOIN kendaraan k ON k.id = p.kendaraan_id WHERE p.id = :id FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $sewa = $stmt->fetch();
            if (!$sewa || $sewa['status'] !== 'Dipesan') {
                throw new Exception('Transaksi tidak valid untuk serah kunci.');
            }
            if ($sewa['k_status'] !== 'Tersedia') {
                throw new Exception('Unit kendaraan belum berstatus Tersedia.');
            }
            $pdo->prepare("UPDATE penyewaan SET status = 'Berjalan' WHERE id = :id")->execute(['id' => $id]);
            $pdo->prepare("UPDATE kendaraan SET status = 'Disewa' WHERE id = :id")->execute(['id' => $sewa['kendaraan_id']]);
            ActivityLogger::log('SERAH_KUNCI', 'penyewaan', "Serah kunci unit {$sewa['plat_nomor']} untuk {$sewa['kode_transaksi']}.");
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
            $stmt = $pdo->prepare("SELECT p.*, k.plat_nomor FROM penyewaan p JOIN kendaraan k ON k.id = p.kendaraan_id WHERE p.id = :id FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $sewa = $stmt->fetch();
            if (!$sewa || !in_array($sewa['status'], ['Dipesan', 'Berjalan'], true)) {
                throw new Exception('Transaksi tidak dapat dibatalkan.');
            }
            $pdo->prepare("UPDATE penyewaan SET status = 'Dibatalkan' WHERE id = :id")->execute(['id' => $id]);
            if ($sewa['status'] === 'Berjalan') {
                $pdo->prepare("UPDATE kendaraan SET status = 'Tersedia' WHERE id = :id")->execute(['id' => $sewa['kendaraan_id']]);
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
            $stmt = $pdo->prepare("SELECT p.*, k.plat_nomor FROM penyewaan p JOIN kendaraan k ON k.id = p.kendaraan_id WHERE p.id = :id FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $sewa = $stmt->fetch();
            if (!$sewa || $sewa['status'] !== 'Berjalan') {
                throw new Exception('Transaksi tidak berstatus Berjalan.');
            }

            $diffDays = (int)floor((strtotime($tglAktual) - strtotime($sewa['tgl_kembali_rencana'])) / 86400);
            $hariTerlambat = max(0, $diffDays);
            $mult = $this->getDendaMultiplier();
            $denda = $hariTerlambat * (float)$sewa['tarif_saat_sewa'] * $mult;

            $newNote = trim(($sewa['catatan'] ? $sewa['catatan'] . ' | ' : '') . $catatan);
            $pdo->prepare("
                UPDATE penyewaan
                SET tgl_kembali_aktual = :tgl,
                    hari_terlambat = :ht,
                    denda = :denda,
                    status = 'Selesai',
                    catatan = :catatan
                WHERE id = :id
            ")->execute([
                'tgl'     => $tglAktual,
                'ht'      => $hariTerlambat,
                'denda'   => $denda,
                'catatan' => mb_substr($newNote, 0, 255),
                'id'      => $id,
            ]);

            $pdo->prepare("UPDATE kendaraan SET status = 'Tersedia' WHERE id = :id")
                ->execute(['id' => $sewa['kendaraan_id']]);

            ActivityLogger::log('KEMBALIKAN', 'pengembalian', "Pengembalian unit {$sewa['plat_nomor']} ({$sewa['kode_transaksi']}), denda Rp " . number_format($denda, 0, ',', '.'));
            $pdo->commit();
            return ['hari_terlambat' => $hariTerlambat, 'denda' => $denda];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
