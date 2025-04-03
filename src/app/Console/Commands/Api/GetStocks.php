<?php

namespace App\Console\Commands\Api;

use App\Console\Commands\BaseApiCommand;
use App\Console\Commands\BaseCommand;
use App\Models\Account;
use App\Models\ApiService;
use App\Models\Sale;
use App\Models\Stock;
use App\Services\FetchApiService;
use Carbon\Carbon;

class GetStocks extends BaseCommand
{
    use FetchTestApi;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:stocks {api_service_id} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get stocks from API';

    protected function validationRules(): array
    {
        return [
            'api_service_id' => 'required|integer|exists:api_services,id',
            'account_id' => 'required|integer|exists:accounts,id'
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'api_service_id.exists' => "API Service with id \"{$this->argument('api_service_id')}\" does not exists.",
            'account_id.exists' => "Account with id \"{$this->argument('account_id')}\" does not exists."
        ];
    }

    protected function handleCommand(): int
    {
        $this->fetch('/api/stocks', Stock::class, ['dateFrom' => Carbon::now()->format('Y-m-d')]);
        $this->info('Import finished');
        return self::SUCCESS;
    }
}
