<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\ApiService;
use App\Models\TokenType;

class AttachTokenTypeToApiService extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attach:token-type-to-api-service {api_service_id} {token_type_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attach a token type to an API service';

    protected function validationRules(): array
    {
        return [
            'api_service_id' => 'required|integer|exists:api_services,id',
            'token_type_id' => 'required|integer|exists:token_types,id',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'api_service_id.exists' => "API Service with id \"{$this->argument('api_service_id')}\" does not exists.",
            'token_type_id.exists' => "Token type with id \"{$this->argument('token_type_id')}\" does not exists.",
        ];
    }

    protected function handleCommand(): int
    {
        $apiService = ApiService::findOrFail($this->argument('api_service_id'));
        $tokenType = TokenType::findOrFail($this->argument('token_type_id'));

        if ($apiService->tokenTypes()->where('token_type_id', $tokenType->id)->exists()) {
            $this->warn("Token Type \"$tokenType->name\" is already attached to API Service \"$apiService->name\"");
            return self::SUCCESS;
        }

        $apiService->tokenTypes()->syncWithoutDetaching($tokenType->id);
        $this->info("Token Type \"$tokenType->name\" attached to API Service \"$apiService->name\" successfully");

        return self::SUCCESS;
    }
}
