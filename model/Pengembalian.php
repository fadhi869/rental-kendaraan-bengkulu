<?php
declare(strict_types=1);

/**
 * Model Pengembalian (Pengembalian Kendaraan & Hitung Denda)
 */
class Pengembalian extends Model
{
    protected string $table = 'penyewaan';

    public function getReturnsList(): array
    {
        return (new Penyewaan())->getReturnsList();
    }

    public function prosesPengembalian(int $id, string $tglAktual, string $catatan = ''): array
    {
        $pdo = $this->db();
        $pdo->beginTransaction();

        try {
            $penyewaan = new Penyewaan();
            $sewa = $penyewaan->findByIdWithLock($id, $pdo);
            if (!$sewa || $sewa['status'] !== 'Berjalan') {
                throw new Exception('Transaksi tidak berstatus Berjalan.');
            }

            $diffDays = (int)floor((strtotime($tglAktual) - strtotime($sewa['tgl_kembali_rencana'])) / 86400);
            $hariTerlambat = max(0, $diffDays);
            $denda = $hariTerlambat * (float)$sewa['tarif_saat_sewa'] * 1.5;

            $newNote = trim(($sewa['catatan'] ? $sewa['catatan'] . ' | ' : '') . $catatan);

            $stmt = $pdo->prepare("
                UPDATE penyewaan
                SET tgl_kembali_aktual = :tgl, hari_terlambat = :ht, denda = :denda, status = 'Selesai', catatan = :catatan
                WHERE id = :id
            ");
            $stmt->execute([
                'tgl'     => $tglAktual,
                'ht'      => $hariTerlambat,
                'denda'   => $denda,
                'catatan' => mb_substr($newNote, 0, 255),
                'id'      => $id,
            ]);

            (new Kendaraan())->updateStatus((int)$sewa['kendaraan_id'], 'Tersedia', $pdo);

            $pdo->commit();
            return ['hari_terlambat' => $hariTerlambat, 'denda' => $denda];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

// Backward Compatibility Aliases
class DetailPeminjaman extends Pengembalian {}
if (!class_exists('App\Models\Pengembalian', false)) class_alias('Pengembalian', 'App\Models\Pengembalian');
if (!class_exists('App\Models\DetailPeminjaman', false)) class_alias('Pengembalian', 'App\Models\DetailPeminjaman');
