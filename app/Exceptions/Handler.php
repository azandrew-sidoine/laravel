<?php

namespace App\Exceptions;

use Drewlabs\Http\Factory\AuthorizationErrorResponseFactoryInterface;
use Drewlabs\Http\Factory\BadRequestResponseFactoryInterface;
use Drewlabs\Http\Factory\ResponseFactoryInterface;
use Drewlabs\Http\Factory\ServerErrorResponseFactoryInterface;
use Drewlabs\Validation\Exceptions\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException as BaseHttpException;
use Drewlabs\Http\Exceptions\HttpException;
use Exception;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
        ValidationException::class,
        AuthenticationException::class,
        AuthorizationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
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

                if ($e instanceof HttpException || $e instanceof BaseHttpException){
                    return $this->container->make(ResponseFactoryInterface::class)->create(sprintf("/%s %s %s", strtoupper($request->method()), $request->path(), $e->getMessage()), intval($e->getStatusCode()), $e->getHeaders());
                }

                // Case application is running in production return a Server Error message to the client
                if (\Illuminate\Foundation\Application::getInstance()->isProduction()) {
                    return $this->container->make(ServerErrorResponseFactoryInterface::class)->create(new Exception('Server Error', 500));
                }

                return $this->container->make(ServerErrorResponseFactoryInterface::class)->create($e);
            }
        });
    }
}
