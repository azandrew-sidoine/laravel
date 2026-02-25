<?php

namespace App\Support\Config\Database;

use App\Support\Config\Contracts\Driver as AbstractDriver;
use App\Support\Config\NoSymbol;
use App\Support\Lang\Value;
use Drewlabs\Laravel\Query\Query;

final class Driver implements AbstractDriver
{
    public function __construct(private string $table) {}

    public function get(string $name)
    {
        $result = [];
        try {
            $result = Query::new()
                ->fromTable($this->table)->select(['*'])->and('name', $name)->getResult();
        } catch (\Illuminate\Database\QueryException) {
        }

        if (empty($result)) {
            return new NoSymbol;
        }

        // collection of configuration values
        if (count($result) > 1) {
            return array_values(array_map(function ($current) {
                return Value::new($current['value'], $current['value_type'] ?? $current['type'] ?? null)->decode();
            }, $result));
        }

        if (!is_null($instance = $result[0] ?? null)) {
            return Value::new($instance['value'], $instance['value_type'] ?? $instance['type'] ?? null)->decode();
        }

        return new NoSymbol;
    }
}
