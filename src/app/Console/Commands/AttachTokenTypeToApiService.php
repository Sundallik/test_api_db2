<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use App\Models\TokenType;
use Illuminate\Console\Command;

class AttachTokenTypeToApiService extends Command
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiService = ApiService::find($this->argument('api_service_id'));
        $tokenType = TokenType::find($this->argument('token_type_id'));

        if (!$apiService || !$tokenType) {
            $this->error('API Service or Token Type not found!');
            exit(1);
        }

        $apiService->tokenTypes()->attach($tokenType->id);
        $this->info("Token Type \"{$tokenType->type}\" attached to API Service \"{$apiService->name}\" successfully");
        exit(0);
    }
}
