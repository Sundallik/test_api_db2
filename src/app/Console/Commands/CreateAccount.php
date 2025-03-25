<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Console\Command;

class CreateAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:account {company_id} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new account for a company';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $company = Company::find($this->argument('company_id'));

        if ($company === null) {
            $this->error("Company with id {$this->argument('company_id')} not found");
            exit(1);
        }

        $account = Account::create([
            'company_id' => $company->id,
            'name' => $this->argument('name')
        ]);

        $this->info("Account \"{$account->name}\" for company \"{$company->name}\" created successfully. ID: {$account->id}");
        exit(0);
    }
}
