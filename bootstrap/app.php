<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(require __DIR__.'/../routes/middleware.php');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 아무것도 넣지 않아도 App\Exceptions\Handler 자동 인식
    })
    ->create();
