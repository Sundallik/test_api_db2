<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\Api\ApiService;
use App\Services\Api\StocksService;
use Illuminate\Console\Command;

class GetStocks extends Command
{
    use ValidateAccount;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:stocks {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stocks from API';

    /**
     * Execute the console command.
     */
    public function handle(StocksService $stocksService)
    {
        $accountId = $this->argument('account_id');

        $this->validateAccount();

        $stocksService->getStocks($accountId);

        $this->info('Import finished');
    }
}
