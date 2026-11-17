<?php
namespace App\Services;

use App\Models\Laporan;

class LaporanService
{
    public function getReport(?string $dari = null, ?string $sampai = null): array
    {
        return (new Laporan())->getRevenueReport($dari, $sampai);
    }
}
