<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Services\Api\FetchApiService;

class GetIncomes extends BaseApiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:incomes {dateFrom} {dateTo} {api_service_id} {account_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get incomes from API';

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
            '/api/incomes',
            [
                'dateFrom' => $params['dateFrom'],
                'dateTo' => $params['dateTo'],
                'key' => $params['apiToken']->token,
            ],
            Income::class
        );

        $this->info('Import finished');
//        exit(0);
    }
}
