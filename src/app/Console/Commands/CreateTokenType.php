<?php

namespace App\Console\Commands;

use App\Models\TokenType;
use Illuminate\Console\Command;

class CreateTokenType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:token-type {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new token type';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tokenType = TokenType::create([
            'type' => $this->argument('type')
        ]);

        $this->info("Token Type \"{$tokenType->type}\" created successfully. ID: {$tokenType->id}");
    }
}
