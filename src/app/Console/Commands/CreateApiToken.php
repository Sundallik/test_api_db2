<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\ApiToken;
use App\Models\TokenType;
use Illuminate\Console\Command;

class CreateApiToken extends Command
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $account = Account::find($this->argument('account_id'));
        $apiService = ApiService::find($this->argument('api_service_id'));
        $tokenType = TokenType::find($this->argument('token_type_id'));

        if (!$account || !$apiService || !$tokenType) {
            $this->error('Account, API Service or Token Type not found!');
            exit(1);
        }

        if (!$apiService->tokenTypes()->where('token_type_id', $tokenType->id)->exists()) {
            $this->error("Token type {$tokenType->name} is not allowed for API service {$apiService->name}");
            exit(1);
        }

        if (ApiToken::where('account_id', $account->id)->where('api_service_id', $apiService->id)->where('token_type_id', $tokenType->id)->exists()) {
            $this->error("Account $account->name already has a token of type {$tokenType->name} for API service {$apiService->name}");
            exit(1);
        }

        $apiToken = ApiToken::create([
            'account_id' => $account->id,
            'api_service_id' => $apiService->id,
            'token_type_id' => $tokenType->id,
            'token' => $this->argument('token'),
        ]);

        $this->info("API Token created successfully. ID: {$apiToken->id}");
        exit(0);
    }
}
