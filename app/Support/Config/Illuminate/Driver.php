<?php

namespace App\Support\Config\Illuminate;

use App\Support\Config\Contracts\Driver as AbstractDriver;
use App\Support\Config\NoSymbol;

final class Driver implements AbstractDriver
{
    public function get(string $name)
    {
        return config($name, new NoSymbol);
    }
}
