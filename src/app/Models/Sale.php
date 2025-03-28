<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use ColumnsForUpsert;

    protected $table = 'sales';
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
        "is_supply",
        "is_realization",
        "promo_code_discount",
        "warehouse_name",
        "country_name",
        "oblast_okrug_name",
        "region_name",
        "income_id",
        "sale_id",
        "odid",
        "spp",
        "for_pay",
        "finished_price",
        "price_with_disc",
        "nm_id",
        "subject",
        "category",
        "brand",
        "is_storno"
    ];
}

