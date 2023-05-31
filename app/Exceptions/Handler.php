<?php

namespace App\Exceptions;

use Drewlabs\Http\Factory\AuthorizationErrorResponseFactoryInterface;
use Drewlabs\Http\Factory\BadRequestResponseFactoryInterface;
use Drewlabs\Http\Factory\ServerErrorResponseFactoryInterface;
use Drewlabs\Validation\Exceptions\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
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
                    return Container::getInstance()->make(BadRequestResponseFactoryInterface::class)->create($e->getErrors());
                }
                if ($e instanceof AuthenticationException) {
                    return Container::getInstance()->make(AuthorizationErrorResponseFactoryInterface::class)->create($request, $e);
                }
                if ($e instanceof AuthorizationException) {
                    return Container::getInstance()->make(AuthorizationErrorResponseFactoryInterface::class)->create($request, $e);
                }
                return Container::getInstance()->make(ServerErrorResponseFactoryInterface::class)->create($e);
            }
        });
    }
}
