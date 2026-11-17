<?php
declare(strict_types=1);

/**
 * Model Peminjaman / Penyewaan (Mencakup Transaksi Sewa & Laporan)
 */
class Peminjaman extends Model
{
    protected string $table = 'penyewaan';

    public function getFiltered(string $status = ''): array
    {
        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp, k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE 1=1
        ";
        $params = [];
        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        $sql .= " ORDER BY p.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getReturnsList(): array
    {
        return $this->db()->query("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp, k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status = 'Berjalan'
            ORDER BY p.tgl_kembali_rencana ASC
        ")->fetchAll();
    }

    public function getHistory(string $status = '', string $search = ''): array
    {
        $sql = "
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp, k.plat_nomor, k.merek, k.model, u.nama AS petugas_nama
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            LEFT JOIN users u ON u.id = p.created_by
            WHERE 1=1
        ";
        $params = [];
        if ($status !== '') {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        if ($search !== '') {
            $sql .= " AND (p.kode_transaksi LIKE :q1 OR pl.nama LIKE :q2 OR k.plat_nomor LIKE :q3 OR k.model LIKE :q4)";
            $like = "%{$search}%";
            $params = ['q1' => $like, 'q2' => $like, 'q3' => $like, 'q4' => $like];
        }
        $sql .= " ORDER BY p.created_at DESC, p.id DESC";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findByIdWithDetails(int $id): ?array
    {
        $stmt = $this->db()->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.nik, pl.no_hp, pl.no_sim, pl.alamat,
                   k.plat_nomor, k.merek, k.model, k.warna, k.tahun, jk.nama_jenis, u.nama AS petugas_nama
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            LEFT JOIN users u ON u.id = p.created_by
            WHERE p.id = :id LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function findByIdWithLock(int $id, PDO $pdo): ?array
    {
        $stmt = $pdo->prepare("
            SELECT p.*, k.plat_nomor, k.status AS k_status
            FROM penyewaan p
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.id = :id FOR UPDATE
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function getActiveAndReserved(int $limit = 8): array
    {
        $stmt = $this->db()->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, pl.no_hp, k.plat_nomor, k.merek, k.model
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            WHERE p.status IN ('Berjalan', 'Dipesan')
            ORDER BY FIELD(p.status, 'Berjalan', 'Dipesan'), p.tgl_kembali_rencana ASC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function generateKodeTransaksi(): string
    {
        $prefix = 'SEWA-' . date('Ym') . '-';
        $stmt = $this->db()->prepare("SELECT kode_transaksi FROM penyewaan WHERE kode_transaksi LIKE :p ORDER BY id DESC LIMIT 1");
        $stmt->execute(['p' => $prefix . '%']);
        $last = $stmt->fetchColumn();
        $next = $last ? ((int)substr($last, strlen($prefix)) + 1) : 1;
        return $prefix . str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    public function createRental(array $input): int
    {
        $pdo = $this->db();
        $pdo->beginTransaction();
        try {
            $bukuModel = new Buku();
            $unit = $bukuModel->findByIdWithLock((int)$input['kendaraan_id'], $pdo);
            if (!$unit) throw new Exception('Kendaraan tidak ditemukan.');

            $statusSewa = ($input['status'] ?? 'Berjalan') === 'Dipesan' ? 'Dipesan' : 'Berjalan';
            if ($statusSewa === 'Berjalan' && $unit['status'] !== 'Tersedia') {
                throw new Exception('Kendaraan sedang tidak tersedia.');
            }

            $tglSewa = $input['tgl_sewa'];
            $tglKembali = $input['tgl_kembali_rencana'];
            if (strtotime($tglKembali) < strtotime($tglSewa)) {
                throw new Exception('Tanggal kembali tidak boleh lebih awal dari tanggal sewa.');
            }

            $days = max(1, (int)round((strtotime($tglKembali) - strtotime($tglSewa)) / 86400));
            $tarif = (float)$unit['tarif_per_hari'];
            $total = $tarif * $days;
            $kode = $this->generateKodeTransaksi();

            $stmt = $pdo->prepare("
                INSERT INTO penyewaan (kode_transaksi, pelanggan_id, kendaraan_id, created_by, tgl_sewa, tgl_kembali_rencana, tarif_saat_sewa, jumlah_hari, total_biaya, status, catatan)
                VALUES (:kode, :pid, :kid, :uid, :tgl_sewa, :tgl_kembali, :tarif, :hari, :total, :status, :catatan)
            ");
            $stmt->execute([
                'kode'        => $kode,
                'pid'         => (int)$input['pelanggan_id'],
                'kid'         => (int)$input['kendaraan_id'],
                'uid'         => Auth::id() ?: 1,
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
                $bukuModel->updateStatus((int)$unit['id'], 'Disewa', $pdo);
            }

            $pdo->commit();
            return $newId;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function serahKunci(int $id): void
    {
        $pdo = $this->db();
        $pdo->beginTransaction();
        try {
            $sewa = $this->findByIdWithLock($id, $pdo);
            if (!$sewa || $sewa['status'] !== 'Dipesan') throw new Exception('Transaksi tidak valid untuk serah kunci.');
            if ($sewa['k_status'] !== 'Tersedia') throw new Exception('Kendaraan belum dalam status Tersedia.');

            $pdo->prepare("UPDATE penyewaan SET status = 'Berjalan' WHERE id = :id")->execute(['id' => $id]);
            (new Buku())->updateStatus((int)$sewa['kendaraan_id'], 'Disewa', $pdo);
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function batalkan(int $id): void
    {
        $pdo = $this->db();
        $pdo->beginTransaction();
        try {
            $sewa = $this->findByIdWithLock($id, $pdo);
            if (!$sewa || !in_array($sewa['status'], ['Dipesan', 'Berjalan'], true)) throw new Exception('Transaksi tidak dapat dibatalkan.');

            $pdo->prepare("UPDATE penyewaan SET status = 'Dibatalkan' WHERE id = :id")->execute(['id' => $id]);
            if ($sewa['status'] === 'Berjalan') {
                (new Buku())->updateStatus((int)$sewa['kendaraan_id'], 'Tersedia', $pdo);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function getSummaryStats(): array
    {
        $row = $this->db()->query("
            SELECT
                COALESCE(SUM(total_biaya + denda), 0) AS total_pendapatan,
                COALESCE(SUM(status = 'Berjalan'), 0) AS sewa_berjalan,
                COALESCE(SUM(status = 'Dipesan'), 0) AS sewa_dipesan,
                COALESCE(SUM(status = 'Selesai'), 0) AS sewa_selesai
            FROM penyewaan WHERE status != 'Dibatalkan'
        ")->fetch();

        return [
            'total_pendapatan' => (float)($row['total_pendapatan'] ?? 0),
            'sewa_berjalan'    => (int)($row['sewa_berjalan'] ?? 0),
            'sewa_dipesan'     => (int)($row['sewa_dipesan'] ?? 0),
            'sewa_selesai'     => (int)($row['sewa_selesai'] ?? 0),
        ];
    }

    public function getDailyRevenueChart(int $limit = 10): array
    {
        $rows = $this->db()->query("
            SELECT DATE_FORMAT(tgl_sewa, '%d %b') AS label, SUM(total_biaya + denda) AS total
            FROM penyewaan WHERE status != 'Dibatalkan'
            GROUP BY tgl_sewa ORDER BY tgl_sewa ASC LIMIT {$limit}
        ")->fetchAll();

        $labels = [];
        $values = [];
        foreach ($rows as $r) {
            $labels[] = $r['label'];
            $values[] = (float)$r['total'];
        }
        return ['labels' => $labels ?: ['Hari 1', 'Hari 2'], 'values' => $values ?: [0, 0]];
    }

    public function getRevenueReport(?string $dari = null, ?string $sampai = null): array
    {
        $dari = $dari ?: date('Y-m-01');
        $sampai = $sampai ?: date('Y-m-t');

        $stmt = $this->db()->prepare("
            SELECT p.*, pl.nama AS nama_pelanggan, k.plat_nomor, k.merek, k.model, jk.nama_jenis
            FROM penyewaan p
            JOIN pelanggan pl ON pl.id = p.pelanggan_id
            JOIN kendaraan k ON k.id = p.kendaraan_id
            JOIN jenis_kendaraan jk ON jk.id = k.jenis_id
            WHERE p.status != 'Dibatalkan' AND p.tgl_sewa BETWEEN :dari AND :sampai
            ORDER BY p.tgl_sewa DESC, p.id DESC
        ");
        $stmt->execute(['dari' => $dari, 'sampai' => $sampai]);
        $rows = $stmt->fetchAll();

        $totalSewa = 0.0;
        $totalDenda = 0.0;
        foreach ($rows as $r) {
            $totalSewa += (float)$r['total_biaya'];
            $totalDenda += (float)$r['denda'];
        }

        return [
            'dari'        => $dari,
            'sampai'      => $sampai,
            'rows'        => $rows,
            'total_sewa'  => $totalSewa,
            'total_denda' => $totalDenda,
            'grand_total' => $totalSewa + $totalDenda,
        ];
    }
}

// Backward Compatibility Aliases
class Penyewaan extends Peminjaman {}
if (!class_exists('App\Models\Penyewaan', false)) class_alias('Peminjaman', 'App\Models\Penyewaan');
if (!class_exists('App\Models\Peminjaman', false)) class_alias('Peminjaman', 'App\Models\Peminjaman');
