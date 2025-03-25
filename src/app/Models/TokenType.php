<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenType extends Model
{
    protected $table = 'token_types';
    protected $guarded = ['id'];

    public function apiServices()
    {
        return $this->hasMany(ApiService::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }
}
