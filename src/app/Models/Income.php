<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use ColumnsForUpsert;

    protected $table = 'incomes';
    protected $guarded = ['id'];
    public $timestamps = false;

    private static $uniqueColumns = ['income_id', 'barcode', 'account_id'];
    private static $updatableColumns = [
//        'income_id',
        'number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
//        'barcode',
        'quantity',
        'total_price',
        'date_close',
        'warehouse_name',
        'nm_id',
    ];
}
