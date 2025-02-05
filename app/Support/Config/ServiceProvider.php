<?php

namespace App\Support\Config;

use Drewlabs\Laravel\Query\Query;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register application services.
     * 
     *
     * @return void
     */
    public function register()
    {
        return $this->app->bind(RepositoryInterface::class, function () {
            return new Repository(fn (string $name) => Query::new()
                ->fromTable(config('app.sys_configs.table', 'sys_configs'))
                ->select(['*'])
                ->and('name', $name)
                ->getResult());
        });
    }
}
