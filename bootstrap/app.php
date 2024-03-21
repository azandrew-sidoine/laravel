<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Drewlabs\Http\Factory\AuthorizationErrorResponseFactoryInterface;
use Drewlabs\Http\Factory\BadRequestResponseFactoryInterface;
use Drewlabs\Http\Factory\ResponseFactoryInterface;
use Drewlabs\Http\Factory\ServerErrorResponseFactoryInterface;
use Drewlabs\Validation\Exceptions\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;
use Drewlabs\Http\Exceptions\HttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->use([
            // Add a localization middleware which set the application
            // locale for the current application request
            \App\Http\Middleware\LocalizationMiddleware::class,
            \Drewlabs\Laravel\Http\Middleware\Cors::class,
            \Illuminate\Http\Middleware\TrustHosts::class,
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $middleware->web(append: [
            // \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->api(append: [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Laravel errors not to report
        $exceptions->dontReport([
            ValidationException::class,
            AuthenticationException::class,
            AuthorizationException::class,
        ]);

        // Laravel error message handlers
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return $this->container->make(BadRequestResponseFactoryInterface::class)->create($e->getErrors());
                }

                if (($e instanceof AuthorizationException) || ($e instanceof AuthenticationException)) {
                    return $this->container->make(AuthorizationErrorResponseFactoryInterface::class)->create($request, $e);
                }

                if (($e instanceof AccessDeniedHttpException)) {
                    return $this->container->make(ResponseFactoryInterface::class)->create(sprintf("/%s %s %s", strtoupper($request->method()), $request->path(), $e->getMessage()), intval($e->getStatusCode()), $e->getHeaders());
                }

                if ($e instanceof HttpException || $e instanceof BaseHttpException) {
                    return $this->container->make(ResponseFactoryInterface::class)->create(sprintf("/%s %s %s", strtoupper($request->method()), $request->path(), $e->getMessage()), intval($e->getStatusCode()), $e->getHeaders());
                }

                // Case application is running in production return a Server Error message to the client
                if (\Illuminate\Foundation\Application::getInstance()->isProduction()) {
                    return $this->container->make(ServerErrorResponseFactoryInterface::class)->create(new Exception('Server Error', 500));
                }

                return $this->container->make(ServerErrorResponseFactoryInterface::class)->create($e);
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
$app->afterLoadingEnvironment(function () {
    \Drewlabs\Support\PackagesConfigurationManifest::load(require __DIR__ . '/../config/packages.php');
});

return $app;
