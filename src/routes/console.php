<?php

use App\Console\Commands\GetIncome;
use App\Console\Commands\GetOrders;
use App\Console\Commands\GetSales;
use App\Console\Commands\GetStocks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

$account_id = 1;

Schedule::command(GetStocks::class, [1])->twiceDaily(8, 20);
Schedule::command(GetOrders ::class, getTimings($account_id))->twiceDaily(9, 21);
Schedule::command(GetIncome::class, getTimings($account_id))->twiceDaily(10, 22);
Schedule::command(GetSales::class, getTimings($account_id))->twiceDaily(11, 23);

function getTimings(int $account_id): array
{
    return [
        now()->subHours(12)->toDateString(),
        now()->toDateString(),
        $account_id
    ];
}
