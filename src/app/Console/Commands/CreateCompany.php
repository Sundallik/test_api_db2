<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class CreateCompany extends Command
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::create([
            'name' => $this->argument('name')
        ]);

        $this->info("Company \"{$company->name}\" created successfully. ID: {$company->id}");
    }
}
