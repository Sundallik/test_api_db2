<?php

use App\Console\Commands\GetIncomes;
use App\Console\Commands\GetOrders;
use App\Console\Commands\GetSales;
use App\Console\Commands\GetStocks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$api_service_id = 1;
$account_id = 1;

Schedule::command(GetStocks::class, [1, 1])->twiceDaily(8, 20);
Schedule::command(GetOrders::class, getTimings(1, 1))->twiceDaily(9, 21);
Schedule::command(GetIncomes::class, getTimings(1, 1))->twiceDaily(10, 22);
Schedule::command(GetSales::class, getTimings(1, 1))->twiceDaily(11, 23);

function getTimings(int $api_service_id, int $account_id): array
{
    return [
//        now()->subHours(12)->toDateString(),
//        now()->toDateString(),
        '2000-01-01',
        '2099-01-01',
        $api_service_id,
        $account_id
    ];
}
