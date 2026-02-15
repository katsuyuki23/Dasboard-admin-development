<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'block_admin_port' => \App\Http\Middleware\BlockAdminPort::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            '/webhook/whatsapp',
            '/tripay/callback',
            '/api/doku/callback',
            '/api/landing/payment/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Force storage path to /tmp on Vercel environment
// Check for VERCEL env var OR typical Lambda path /var/task
if (
    isset($_ENV['VERCEL']) || 
    isset($_SERVER['VERCEL']) || 
    env('VIEW_COMPILED_PATH') || 
    str_contains(__DIR__, '/var/task')
) {
    // We use /tmp/storage to mimic the structure created in api/index.php
    $app->useStoragePath('/tmp/storage');
}

return $app;
