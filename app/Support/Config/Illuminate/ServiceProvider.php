<?php

namespace App\Support\Config\Illuminate;

use App\Support\Config\Database\Driver as DatabaseDriver;
use App\Support\Config\Illuminate\Driver as IlluminateDriver;
use App\Support\Config\Repository;
use App\Support\Config\RepositoryInterface;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register application services.
     * 
     * @return void
     */
    public function register()
    {
        return $this->app->bind(
            RepositoryInterface::class,
            fn()  =>  new Repository([
                new DatabaseDriver(config('app.sys_configs.table', 'sys_configs')),
                new IlluminateDriver()
            ])
        );
    }
}
