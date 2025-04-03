<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiToken;
use App\Models\TokenType;

class CreateApiToken extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:api-token {account_id} {api_service_id} {token_type_id} {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API token for an account';

    protected function validationRules(): array
    {
        return [
            'account_id' => 'required|int|exists:accounts,id',
            'api_service_id' => 'required|int|exists:api_services,id',
            'token_type_id' => 'required|int|exists:token_types,id',
            'token' => 'required|string|max:255',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'account_id.exists' => "Account with id \"{$this->argument('api_service_id')}\" does not exists.",
            'api_service_id.exists' => "API Service with id \"{$this->argument('api_service_id')}\" does not exists.",
            'token_type_id.exists' => "Token type with id \"{$this->argument('token_type_id')}\" does not exists.",
        ];
    }
    public function handleCommand(): int
    {
        $account = Account::with('company')->findOrFail($this->argument('account_id'));
        $apiService = ApiService::findOrFail($this->argument('api_service_id'));
        $tokenType = TokenType::findOrFail($this->argument('token_type_id'));

//        if (!$apiService->tokenTypes()->where('token_type_id', $tokenType->id)->exists()) {
//            $this->error("Token type \"$tokenType->type\" is not allowed for API service \"$apiService->name\"");
//            exit(1);
//        }
//
//        if (ApiToken::where('account_id', $account->id)->where('api_service_id', $apiService->id)->where('token_type_id', $tokenType->id)->exists()) {
//            $this->error("Account $account->name already has a token of type {$tokenType->name} for API service {$apiService->name}");
//            exit(1);
//        }

        $apiToken = ApiToken::create([
            'account_id' => $account->id,
            'api_service_id' => $apiService->id,
            'token_type_id' => $tokenType->id,
            'token' => $this->argument('token'),
        ]);

        $this->info("API Token for account \"$account->name\" ({$account->company->name}) for API service \"$apiService->name\" created successfully. ID: {$apiToken->id}");
        return self::SUCCESS;
    }
}
