<?php

namespace App\Support\Config;

use BadMethodCallException;
use InvalidArgumentException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use App\Support\Config\ConfigInterface;
use App\Support\Config\Value\_Value;

class Repository implements RepositoryInterface
{
    /**
     * @var callable|null
     */
    private $factory;

    /**
     * Create repository instance
     * 
     * @param callable|null $factory 
     * @return void 
     */
    public function __construct(?callable $factory = null)
    {
        $this->factory = $factory;
    }

    /**
     * Returns a configuration value for the provided configuration name
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
        // Query from configuration from database
        try {
            $result = $this->factory ? call_user_func($this->factory, $name, $default) : [];
        } catch (\Illuminate\Database\QueryException) {
            $result = [];
        }

        // $result empty if an error occurs or no configuration value found for name
        if (empty($result)) {
            return config($name, $default);
        }

        if (count($result) > 1) {
            return array_values(array_map(function (ConfigInterface $current) {
                return _Value::new($current->getValue(), $current->getValueType())->decode();
            }, $result));
        }
        /**
         * @var ConfigInterface|null $instance
         */
        $instance = $result[0] ?? null;

        // Case the configuration is resolved from database, return it value
        if (null !== $instance) {
            return _Value::new($instance->getValue(), $instance->getValueType())->decode();
        }

        return config($name, $default);
    }
}
