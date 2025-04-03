<?php

namespace App\Console\Commands\Credentials;

use App\Console\Commands\BaseCommand;
use App\Models\TokenType;

class CreateTokenType extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:token-type {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new token type';

    protected function validationRules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:token_types,name',
        ];
    }

    protected function validationMessages(): array
    {
        return [
            'name.unique' => "Token type \"{$this->argument('name')}\" already exists.",
        ];
    }

    /**
     * Execute the console command.
     */
    protected function handleCommand(): int
    {
        $tokenType = TokenType::create([
            'name' => $this->argument('name')
        ]);

        $this->info("Token Type \"{$tokenType->name}\" created successfully. ID: {$tokenType->id}");

        return self::SUCCESS;
    }
}
