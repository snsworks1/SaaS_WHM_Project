<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\VerifyCsrfToken;


return [
'admin' => \App\Http\Middleware\AdminMiddleware::class,
'csrf' => [
        // Toss Webhook 예외 처리 등록
        'except' => [
            '/webhook/toss',
        ],
        'middleware' => VerifyCsrfToken::class,
    ],
    'web' => [
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ],
];
