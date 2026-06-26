<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'iae.apikey' => \App\Http\Middleware\IaeApiKey::class,
            'iae.jwt'    => \App\Http\Middleware\VerifyIaeJwt::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $statusCode = 500;
                $message = $e->getMessage();

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $statusCode = $e->getStatusCode();
                } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $statusCode = 404;
                    $message = 'Resource not found';
                }

                // Default messages for common status codes
                if (empty($message)) {
                    if ($statusCode === 404) {
                        $message = 'Resource not found';
                    } else {
                        $message = 'An error occurred';
                    }
                }

                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'errors' => null
                ], $statusCode);
            }
        });
    })->create();