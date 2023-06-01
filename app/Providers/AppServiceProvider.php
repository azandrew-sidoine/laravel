<?php

namespace App\Providers;

use Drewlabs\Contracts\Validator\Validator;
use Drewlabs\Contracts\Validator\ValidatorFactory;
use Drewlabs\Laravel\Http\JsonApiProvider;
use Drewlabs\Validation\ValidatorAdapter;
use Illuminate\Contracts\Validation\Factory;
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
            return new ValidatorAdapter(new class($app['validator']) implements ValidatorFactory {

                /**
                 * @var Factory
                 */
                private $factory;

                /**
                 * Creates class instance
                 * 
                 * @param Factory $factory 
                 */
                public function __construct(Factory $factory)
                {
                    $this->factory = $factory;
                }

                public function make(...$args)
                {
                    return $this->factory->make(...$args);
                }
            });
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
