<?php

namespace App\Console\Commands\Api;

use App\Console\Commands\BaseCommand;
use App\Models\Order;

class GetOrders extends BaseCommand
{
    use FetchTestApi;
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

    protected function validationRules(): array
    {
        return [
            'api_service_id' => 'required|integer|exists:api_services,id',
            'account_id' => 'required|integer|exists:accounts,id',
            'dateFrom' => 'required|date|date_format:Y-m-d',
            'dateTo' => 'required|date|date_format:Y-m-d|after_or_equal:dateFrom',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'api_service_id.exists' => "API Service with id \"{$this->argument('api_service_id')}\" does not exists.",
            'account_id.exists' => "Account with id \"{$this->argument('account_id')}\" does not exists.",
            'dateTo.after_or_equal' => 'Start date is greater than end date.',
        ];
    }

    protected function handleCommand(): int
    {
        $this->fetch('/api/orders', Order::class);
        $this->info('Import finished');
        return self::SUCCESS;
    }
}
