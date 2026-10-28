<?php

namespace Lib\Config;

use Drewlabs\Support\Config\Contracts\Driver as AbstractDriver;
use Drewlabs\Support\Config\None;

final class Driver implements AbstractDriver
{
    public function get(string $name)
    {
        return config($name, new None);
    }
}
