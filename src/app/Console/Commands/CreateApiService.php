<?php

namespace App\Console\Commands;

use App\Models\ApiService;
use Illuminate\Console\Command;

class CreateApiService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:api-service {name} {base_url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiService = ApiService::create([
            'name' => $this->argument('name'),
            'base_url' => $this->argument('base_url')
        ]);

        $this->info("API Service \"{$apiService->name}\" created successfully. ID: {$apiService->id}");
    }
}
