<?php

use App\Http\Middleware\AdminMiddleware;
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
        $middleware->web(append: [
            // Middleware lain jika ada
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class, // Daftarkan AdminMiddleware
        ]);
      
      // === TAMBAHKAN BAGIAN INI ===
        $middleware->validateCsrfTokens(except: [
            'api/receive-upload',
                      // optional: kalau mau semua route /api/* tidak perlu CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();