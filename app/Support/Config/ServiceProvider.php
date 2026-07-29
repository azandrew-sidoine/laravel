<?php

namespace App\Support\Config;

use App\Support\Config\Database\Driver as DatabaseDriver;
use App\Support\Config\Http\Driver as HttpDriver;
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
        return $this->app->bind(RepositoryInterface::class, function () {

            $drivers = [new ConfigDriver()];

            if (!is_null($name = config('app.config.drivers.database.table'))) {
                array_push($drivers, new DatabaseDriver($name));
            }

            if (!is_null($path = config('app.config.drivers.http.path'))) {
                array_push($drivers, new HttpDriver($path));
            }

            return new Repository($drivers);
        });
    }
}
