<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsParticipante
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()?->role_id !== 3) {
            abort(403, 'Acceso restringido solo a participantes');
        }

        return $next($request);
    }
}