<?php

namespace App\Console\Commands\Api;

use App\Console\Commands\BaseCommand;
use App\Models\Account;
use App\Models\ApiService;
use App\Models\Income;
use App\Services\FetchApiService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

trait FetchTestApi
{
    protected final function fetch(string $endpoint, string $model, array $additionalParams = [], int $maxRetries = 10, int $initialDelay = 10)
    {
        $apiService = ApiService::findOrFail($this->argument('api_service_id'));
        $account = Account::with('apiTokens')->findOrFail($this->argument('account_id'));
        $apiToken = $account->getApiTokenForService($this->argument('api_service_id'));

        if (!$apiToken) $this->fail("$account->name don't have a token for the API service \"$apiService->name\"");

        $paramsFromUser = $this->arguments();
        unset($paramsFromUser['command'], $paramsFromUser['api_service_id'], $paramsFromUser['account_id']);
        $paramsFromUser = array_merge($paramsFromUser, $additionalParams, ['key' => $apiToken->token], );

        FetchApiService::fetchData(
            $apiService,
            $account,
            $endpoint,
            $paramsFromUser,
            $model,
            $maxRetries,
            $initialDelay
        );
    }
}
