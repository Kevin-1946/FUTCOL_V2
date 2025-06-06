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
        // Registrar tus middlewares personalizados
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'capitan' => \App\Http\Middleware\IsCapitan::class,
            'participante' => \App\Http\Middleware\IsParticipante::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();