<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $table = 'api_tokens';
    protected $guarded = ['id'];

    protected $with = ['apiService'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function apiService()
    {
        return $this->belongsTo(ApiService::class);
    }

    public function tokenType()
    {
        return $this->belongsTo(TokenType::class);
    }
}
