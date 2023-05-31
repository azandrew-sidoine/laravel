<?php

namespace App\Providers;

use Drewlabs\Contracts\Validator\Validator;
use Drewlabs\Laravel\Http\JsonApiProvider;
use Drewlabs\Validation\ValidatorAdapter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register bindings for validation adapter
        $this->app->bind(Validator::class, function ($app) {
            return new ValidatorAdapter($app['validator']);
        });

        // Register json providers for laravel-http library
        JsonApiProvider::provide($this->app);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }


    public function provides()
    {
        return [Validator::class];
    }
}
