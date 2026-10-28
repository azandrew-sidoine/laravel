<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php', // uncomment for web routes
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        if (!is_null($hosts = env('TRUSTED_HOSTS', null))) {
            $middleware = $middleware->trustHosts(explode(',', $hosts));
        }
        return $middleware->trustProxies(
            explode(',', env('TRUSTED_PROXIES', '*')),
            env('TRUSTED_HEADER', \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_AWS_ELB)
        )->use(
            array_merge(
                [
                    // Add a localization middleware which set the application
                    // locale for the current application request
                    \Illuminate\Http\Middleware\TrustProxies::class,
                    \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
                    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
                    \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                ],
                !is_null($hosts) ? [\Illuminate\Http\Middleware\TrustHosts::class] : []
            )
        )->web(append: [
            // \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ])->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ])->alias([
            // 'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            // 'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        ]);
    })
    ->withCommands([
        __DIR__ . '/app/Console/Commands'
    ])
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->dontReport([]);

        // Laravel error message handlers
        $exceptions->renderable(function (Throwable $e, Request $request) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return new \Illuminate\Http\Response('Server error', 500);
        });
    })->create();

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
$app->afterLoadingEnvironment(function () { });

return $app;
