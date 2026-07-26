<?php

namespace App\Support\Config\Database;

use Drewlabs\Support\Lang\Value;
use Drewlabs\Laravel\Query\Query;
use Drewlabs\Support\Config\Contracts\Driver as AbstractDriver;
use Drewlabs\Support\Config\None;


final class Driver implements AbstractDriver
{
    public function __construct(private string $table) {}

    public function get(string $name)
    {
        $items = [];
        try {
            $items = Query::new()->fromTable($this->table)->select(['*'])->and('name', $name)->getResult();
        } catch (\Illuminate\Database\QueryException) { }

        if (empty($items)) {
            return new None;
        }

        if (count($items) > 1) {
            return array_values(array_map(fn($current) => Value::new($current['value'], $current['value_type'] ?? $current['type'] ?? null)->decode(), $items));
        }

        if (!is_null($value = $items[0] ?? null)) {
            return Value::new($value['value'], $value['value_type'] ?? $value['type'] ?? null)->decode();
        }

        return new None;
    }
}
