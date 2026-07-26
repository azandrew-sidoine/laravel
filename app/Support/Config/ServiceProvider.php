<?php

namespace App\Support\Config;

use App\Support\Config\Database\Driver as DatabaseDriver;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use App\Support\Config\Driver as ConfigDriver;
use Drewlabs\Support\Config\Repository;
use Drewlabs\Support\Config\RepositoryInterface;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register application services.
     * 
     * @return void
     */
    public function register()
    {
        return $this->app->bind(RepositoryInterface::class, fn()  =>  new Repository(!is_null($value = config('app.config.database.table')) ? [new DatabaseDriver($value), new ConfigDriver() ]: [new ConfigDriver()]));
    }
}
