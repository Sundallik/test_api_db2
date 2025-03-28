<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Services\Api\FetchApiService;
use Carbon\Carbon;

class GetStocks extends BaseApiCommand
{
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $params = $this->getAndValidateParams(
            $this->argument('api_service_id'),
            $this->argument('account_id')
        );

        FetchApiService::fetchData(
            $params['apiService'],
            $params['account'],
            '/api/stocks',
            [
                'dateFrom' => Carbon::now()->format('Y-m-d'),
                'key' => $params['apiToken']->token,
            ],
            Stock::class
        );

        $this->info('Import finished');
//        exit(0);
    }
}
