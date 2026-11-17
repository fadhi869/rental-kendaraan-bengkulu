<?php
namespace App\Services;

use App\Models\Penyewaan;

class RiwayatService
{
    public function getHistory(string $status = '', string $search = ''): array
    {
        return (new Penyewaan())->getHistory($status, $search);
    }
}
