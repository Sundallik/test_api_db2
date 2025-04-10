<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $guarded = ['id'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
