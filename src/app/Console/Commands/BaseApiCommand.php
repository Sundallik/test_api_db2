<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\ApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;

abstract class BaseApiCommand extends Command
{
    protected function getAndValidateParams(int $apiServiceId, int $accountId, string $dateFrom = null, string $dateTo = null): array
    {
        $this->validateDates($dateFrom, $dateTo);

        $apiService = ApiService::find($apiServiceId);
        $account = Account::with('apiTokens')->find($accountId);
        $apiToken = $account ? $account->apiTokens->where('api_service_id', $apiServiceId)->first() : null;

        $this->validateCredentials($apiService, $account, $apiToken);

        return [
            'apiService' => $apiService,
            'account' => $account,
            'apiToken' => $apiToken,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];
    }

    private function validateCredentials($apiService, $account, $apiToken): void
    {

        if (!$apiService) {
            $this->error('Api Service not found!');
            exit(1);
        }

        if (!$account) {
            $this->error('Account not found!');
            exit(1);
        }

        if (!$apiToken) {
            $this->error('Account does not have a token for this API service!');
            exit(1);
        }
    }

    private function validateDates(string|null $dateFrom, string|null $dateTo): void
    {
        if ($dateFrom === null || $dateTo === null) return;

        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        if ($dateFrom->greaterThan($dateTo)) {
            $this->error('Start date is greater than end date.');
            exit(1);
        }
    }
}
