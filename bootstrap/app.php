<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Tambahkan ini agar Laravel mempercayai Proxy/Tunnel
        $middleware->trustProxies(at: '*');

        // Mendaftarkan alias middleware agar bisa digunakan di web.php
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2. Mengecualikan route webhook Xendit dari proteksi CSRF
        $middleware->validateCsrfTokens(except: [
            'xendit/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();