<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
//    use ColumnsForUpsert;

    protected $table = 'stocks';
    protected $guarded = ['id'];
    public $timestamps = false;
//    private static $uniqueColumns = ['supplier_article', 'account_id'];
//    private static $updatableColumns = [
//        'date',
//        'last_change_date',
//        'tech_size',
//        'barcode',
//        'quantity',
//        'is_supply',
//        'is_realization',
//        'quantity_full',
//        'warehouse_name',
//        'in_way_to_client',
//        'in_way_from_client',
//        'nm_id',
//        'subject',
//        'category',
//        'brand',
//        'sc_code',
//        'price',
//        'discount'
//    ];
}


