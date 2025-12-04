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
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            'https://next-africacclub.vercel.app/',
            'https://next-africacclub.vercel.app/login',
            'https://africacapitalclub.com',
            'https://www.africacapitalclub.com',
            'https://africacc.fldesigners.co.zw',
            'https://africacc.fldesigners.co.zw/',
            'https://africacc.fldesigners.co.zw/*',
            'https://*.vercel.app', // Allow all preview deployments
            'login',
            'register',
            'login/*',
            'register/*',
            'message',
            'message/*',
            'contact',
            'contact/',
            'contact/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
