<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\Api\ApiService;
use App\Services\Api\IncomesService;
use Illuminate\Console\Command;

class GetIncome extends Command
{
    use ValidateAccount;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:incomes {dateFrom} {dateTo} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get incomes from API';

    /**
     * Execute the console command.
     */
    public function handle(IncomesService $incomesService)
    {
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo');
        $accountId = $this->argument('account_id');

        $this->validateAccount();

        $incomesService->getIncomes($dateFrom, $dateTo, $accountId);

        $this->info('Import finished');
    }
}
