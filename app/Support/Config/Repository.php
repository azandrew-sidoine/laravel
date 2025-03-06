<?php

namespace App\Support\Config;

use BadMethodCallException;
use InvalidArgumentException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use App\Support\Config\Contracts\Driver;

class Repository implements RepositoryInterface
{
    /**
     * Create repository instance
     * 
     * @param Driver[] $drivers 
     * @return void 
     */
    public function __construct(private array $drivers = []) {}

    /**
     * returns a configuration value for the provided configuration name
     * 
     * @param string $name 
     * @param mixed $default 
     * @return mixed 
     * @throws BadMethodCallException 
     * @throws InvalidArgumentException 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public function get(string $name, $default = null)
    {
        foreach ($this->drivers as $driver) {
            if (!(($result = $driver->get($name)) instanceof NoSymbol)) {
                return $result;
            }
        }
        
        return $default;
    }
}
