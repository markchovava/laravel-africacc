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
        $middleware->validateCsrfTokens(except: [
            'http://127.0.0.1:8000',
            'http://127.0.0.1:8000/',
            'http://127.0.0.1:8000/*',
            'https://fldesigners.xyz',
            'https://fldesigners.xyz/',
            'https://fldesigners.xyz/*',
            'https://next-recycle-center-olive.vercel.app',
            'https://next-recycle-center-olive.vercel.app/',
            'https://next-recycle-center-olive.vercel.app/*',
            'https://www.fldesigners.xyz/schedule-of-customer-unauth',
            'https://www.fldesigners.xyz/schedule-of-customer-unauth/*',
            'schedule-of-customer-unauth/*',
            'schedule-of-customer-unauth',
            'login',
            'register',
            'login/*',
            'register/*',
            'message',
            'message/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
