<?php

namespace Lib\Config\Http;

use Drewlabs\Query\Http\Query;
use Drewlabs\Support\Lang\Value;
use Drewlabs\Support\Config\Contracts\Driver as AbstractDriver;
use Drewlabs\Support\Config\None;
use Illuminate\Container\Container;

final class Driver implements AbstractDriver
{
    public function __construct(private string $path) {}

    public function get(string $name)
    {
        $items = [];
        try {
            $items = $this->withAuthorization(Query::new()->from($this->path))->select(['*'])->and('name', $name)->getResult(); 
        } catch (\Exception) { }

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


    /**
     * adds authorization header to the query object
     * 
     * @param Query $query
     * 
     * @return Query 
     */
    private function withAuthorization(Query $query)
    {
        try {
            /** @var \Illuminate\Http\Request $request */
            $request = Container::getInstance()->make('request');
            if (null !== $request) {
                $query = $query->withAuthorization($request->bearerToken());
            }

        } catch (\Throwable $e) {
        }

        return $query;
    }
}
