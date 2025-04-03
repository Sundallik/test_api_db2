<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\Company;

class CreateCompany extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:company {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new company';

    protected function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:companies,id'
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'name.unique' => "Company \"{$this->argument('name')}\" already exists"
        ];
    }

    protected function handleCommand(): int
    {
        $company = Company::create([
            'name' => $this->argument('name')
        ]);

        $this->info("Company \"{$company->name}\" created successfully. ID: {$company->id}");

        return self::SUCCESS;
    }
}
