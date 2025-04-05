<?php

namespace App\Models;
trait ColumnsForUpsert
{
    public static function getUniqueColumns(): array
    {
        return static::$uniqueColumns ?? [];
    }

    public static function getUpdatableColumns(): array
    {
        return static::$updatableColumns ?? [];
    }
}
