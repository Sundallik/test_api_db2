<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ColumnsForUpsert;

    protected $table = 'orders';
    protected $guarded = ['id'];
    public $timestamps = false;

    private static $uniqueColumn = 'g_number';
    private static $updatableColumns = [
        "date",
        "last_change_date",
        "supplier_article",
        "tech_size",
        "barcode",
        "total_price",
        "discount_percent",
        "warehouse_name",
        "oblast",
        "income_id",
        "odid",
        "nm_id",
        "subject",
        "category",
        "brand",
        "is_cancel",
        "cancel_dt",
    ];
}

