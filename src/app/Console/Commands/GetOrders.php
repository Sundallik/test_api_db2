<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Api\FetchApiService;

class GetOrders extends BaseApiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:orders {dateFrom} {dateTo} {api_service_id} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get orders from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $params = $this->getAndValidateParams(
            $this->argument('api_service_id'),
            $this->argument('account_id'),
            $this->argument('dateFrom'),
            $this->argument('dateTo')
        );

        FetchApiService::fetchData(
            $params['apiService'],
            $params['account'],
            '/api/orders',
            [
                'dateFrom' => $params['dateFrom'],
                'dateTo' => $params['dateTo'],
                'key' => $params['apiToken']->token,
            ],
            Order::class
        );

        $this->info('Import finished');
//        exit(0);
    }
}
