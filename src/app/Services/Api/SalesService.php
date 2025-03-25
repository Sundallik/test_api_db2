<?php

namespace App\Services\Api;
use App\Models\Sale;

class SalesService extends ApiService
{
    public function getSales($dateFrom, $dateTo, $accountId)
    {
        $this->fetchData(
            '/api/sales',
            ['dateFrom' => $dateFrom, 'dateTo' => $dateTo],
            Sale::class,
            $accountId
        );
    }
}
