<?php

namespace App\Models;
trait ColumnsForUpsert
{
    public static function getUniqueColumn(): string
    {
        return static::$uniqueColumn ?? '';
    }

    public static function getUpdatableColumns(): array
    {
        return static::$updatableColumns ?? [];
    }
}
