<?php

namespace App\Services\Api;
use App\Models\Income;

class IncomesService extends ApiService
{
    public function getIncomes($dateFrom, $dateTo, $accountId)
    {
        $this->fetchData(
            '/api/incomes',
            ['dateFrom' => $dateFrom, 'dateTo' => $dateTo],
            Income::class,
            $accountId
        );
    }
}
