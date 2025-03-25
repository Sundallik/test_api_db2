<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\Api\ApiService;
use App\Services\Api\OrdersService;
use Illuminate\Console\Command;

class GetOrders extends Command
{
    use ValidateAccount;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:orders {dateFrom} {dateTo} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get orders from API';

    /**
     * Execute the console command.
     */
    public function handle(OrdersService $ordersService)
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $accountId = $this->argument('account_id');

        $this->validateAccount();

        $ordersService->getOrders($dateFrom, $dateTo, $accountId);

        $this->info('Import finished');
    }
}
