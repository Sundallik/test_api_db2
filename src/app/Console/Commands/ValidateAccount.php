<?php
namespace App\Console\Commands;
use App\Models\Account;

trait ValidateAccount
{
    public function validateAccount()
    {
        if (!Account::find($this->argument('account_id'))) {
            $this->error('Account not found!');
            exit(1);
        }
    }
}
