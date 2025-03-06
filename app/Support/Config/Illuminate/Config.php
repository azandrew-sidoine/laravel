<?php

namespace App\Support\Config\Illuminate;

use App\Support\Config\RepositoryInterface;
use Illuminate\Support\Facades\Facade;


/**
 * @method static mixed get(string $name, $default)
 */
final class Config extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RepositoryInterface::class;
    }
}
