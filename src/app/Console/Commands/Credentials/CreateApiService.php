<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\ApiService;

class CreateApiService extends BaseCommand
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
    protected $description = 'Create a new API Service';

    protected function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:api_services,name',
            'base_url' => 'required|string|url',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'name.unique' => "API Service \"{$this->argument('name')}\" already exists.",
        ];
    }

    public function handleCommand(): int
    {
        $apiService = ApiService::create([
            'name' => $this->argument('name'),
            'base_url' => $this->argument('base_url')
        ]);

        $this->info("API Service \"{$apiService->name}\" created successfully. ID: {$apiService->id}");

        return self::SUCCESS;
    }
}
