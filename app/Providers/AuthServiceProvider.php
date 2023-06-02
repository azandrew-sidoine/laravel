<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Configure the Http-Guard library to use cache
        // Uncomment the code below to enable caching in authentication guard
        // \Drewlabs\HttpGuard\HttpGuardGlobals::usesCache(true);

        // Configure the http-guard library to use PHP 'memcached' storage as default driver
        // Uncomment the code below to enable using memcached driver as authentication guard caching
        // \Drewlabs\HttpGuard\HttpGuardGlobals::useCacheDriver('memcached');

        \Drewlabs\HttpGuard\HttpGuardGlobals::userPath('auth/v2/user');
        \Drewlabs\HttpGuard\HttpGuardGlobals::revokePath('auth/v2/logout');
        // ...
        //
    }
}
