<?php

namespace App\Console\Commands\Api;

use App\Models\Account;
use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update API info';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accounts = Account::with('apiTokens')->get();

        foreach ($accounts as $account) {
            foreach ($account->apiTokens as $apiToken) {
                $timings = $this->getTimings();
                $credentials = $this->getCredentials($apiToken->api_service_id, $account->id);
                switch ($apiToken->apiService->base_url) {
                    case 'http://89.108.115.241:6969':
                    case 'http://109.73.206.144:6969':
                        $this->call('get:incomes', [...$timings, ...$credentials]);
                        $this->call('get:sales', [...$timings, ...$credentials]);
                        $this->call('get:orders', [...$timings, ...$credentials]);
                        $this->call('get:stocks', [...$credentials]);
                        break;
                }
            }
        }
    }

    private function getTimings(): array
    {
        return [
            'dateFrom' => now()->subDay()->toDateString(),
            'dateTo' => now()->toDateString()
        ];
    }

    private function getCredentials(int $api_service_id, int $account_id): array
    {
        return [
            'api_service_id' => $api_service_id,
            'account_id' => $account_id,
        ];
    }
}
