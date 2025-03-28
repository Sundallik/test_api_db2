<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use ColumnsForUpsert;

    protected $table = 'stocks';
    protected $guarded = ['id'];
    public $timestamps = false;
    private static $uniqueColumn = 'barcode';
    private static $updatableColumns = [
        'supplier_article',
        'date',
        'last_change_date',
        'tech_size',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount'
    ];
}


