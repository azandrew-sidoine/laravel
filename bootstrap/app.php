<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;


$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php', // uncomment to register web routes
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {

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
                    \Illuminate\Http\Middleware\TrustProxies::class,
                    \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
                    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
                    \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
                ],
                !is_null($hosts) ? [\Illuminate\Http\Middleware\TrustHosts::class] : []
            )
        )->web(append: [
            // // \App\Http\Middleware\EncryptCookies::class,
            // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // \Illuminate\Session\Middleware\StartSession::class,
            // // \Illuminate\Session\Middleware\AuthenticateSession::class,
            // \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // // \App\Http\Middleware\VerifyCsrfToken::class,
            // \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ])->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ])->alias([
            // TODO: add middlewares alias
        ]);
    })
    ->withCommands([__DIR__ . '/app/Console/Commands'])
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->dontReport([ /** exceptions not to report */ ]);

        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                /** api/<route> exception handlers */
            }

            if (app()->environment('production')) {
                Log::error($e->getMessage());

                return new Response('Server error', 500);
            }
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
