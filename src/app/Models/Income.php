<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use ColumnsForUpsert;

    protected $table = 'incomes';
    protected $guarded = ['id'];
    public $timestamps = false;

    private static $uniqueColumn = 'income_id';
    private static $updatableColumns = [
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'quantity',
        'barcode',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
        'account_id'
    ];
}

