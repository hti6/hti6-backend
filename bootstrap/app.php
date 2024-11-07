<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Middleware\Header;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            Header::class
        ]);
        $middleware->alias([
            'type.client' =>    UserMiddleware::class,
            'type.admin' =>     AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'message' => __('Bad method')
            ], 405);
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'message' => __('Not found')
            ], 404);
        });
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'message' => __('Not found')
            ], 404);
        });
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            return response()->json([
                'seconds' => $e->getHeaders()['Retry-After'],
                'message' => __('Maximum attempts, please try again later')
            ], 429);
        });
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'message' => __('Forbidden')
            ], 403);
        });
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => __('Unauthorized')
            ], 401);
        });
    })->create();
