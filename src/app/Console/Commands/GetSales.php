<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\Api\ApiService;
use App\Services\Api\SalesService;
use Illuminate\Console\Command;

class GetSales extends Command
{
    use ValidateAccount;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:sales {dateFrom} {dateTo} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get sales from API';

    /**
     * Execute the console command.
     */
    public function handle(SalesService $salesService)
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $accountId = $this->argument('account_id');

        $this->validateAccount();

        $salesService->getSales($dateFrom, $dateTo, $accountId);

        $this->info('Import finished');
    }
}
