<?php

namespace App\Services\Api;
use App\Models\Stock;
use Illuminate\Support\Facades\Date;

class StocksService extends ApiService
{
    public function getStocks($accountId)
    {
        $this->fetchData(
            '/api/stocks',
            ['dateFrom' => Date::now()->format("Y-m-d")],
            Stock::class,
            $accountId
        );
    }
}
