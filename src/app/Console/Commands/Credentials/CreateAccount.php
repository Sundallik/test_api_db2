<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\Account;
use App\Models\Company;

class CreateAccount extends BaseCommand
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

    protected function validationRules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'name' => 'required|string|max:255|unique:accounts,name,NULL,id,company_id,' . $this->argument('company_id'),
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'company_id.exists' => "Company with id \"{$this->argument('company_id')}\" does not exists",
            'name.unique' => "Account \"{$this->argument('name')}\" already exists for the company {$this->argument('company_id')}"
        ];
    }

    protected function handleCommand(): int
    {
        $company = Company::findOrFail($this->argument('company_id'));

        $account = Account::create([
            'company_id' => $company->id,
            'name' => $this->argument('name')
        ]);

        $this->info("Account \"{$account->name}\" for company \"{$company->name}\" created successfully. ID: {$account->id}");

        return self::SUCCESS;
    }
}
