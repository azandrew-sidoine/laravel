<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->afterLoadingEnvironment(function () {
    /*
    |--------------------------------------------------------------------------
    | Modules configuration
    |--------------------------------------------------------------------------
    |
    | We Load into memory configurations for various packages that are specifically
    | not framework dependant. We wait for framework to load environment variable
    | before loading configration as some packages can depends of some environment
    | variables.
    |
    */
    \Drewlabs\Support\PackagesConfigurationManifest::load(require __DIR__ . '/../config/packages.php');
});

return $app;
