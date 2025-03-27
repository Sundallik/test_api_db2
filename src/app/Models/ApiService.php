<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiService extends Model
{
    protected $table = 'api_services';
    protected $guarded = ['id'];

    public function tokenTypes()
    {
        return $this->belongsToMany(TokenType::class, 'api_service_token_types', 'api_service_id', 'token_type_id');
    }

    public function tokens()
    {
        return $this->hasMany(ApiToken::class);
    }
}
