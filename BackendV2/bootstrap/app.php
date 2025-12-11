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
            'check.capitan' => \App\Http\Middleware\CheckCapitan::class,
            'check.administrador' => \App\Http\Middleware\CheckAdministrador::class,
            'check.participante' => \App\Http\Middleware\CheckParticipante::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'team.owner' => \App\Http\Middleware\CheckTeamOwner::class,
            'jugador.owner' => \App\Http\Middleware\CheckJugadorOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();