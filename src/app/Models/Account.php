<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }

    public function getApiTokenForService(int $serviceId): ?ApiToken
    {
        return $this->apiTokens->where('api_service_id', $serviceId)->first();
    }
}
