<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()?->role_id !== 1) {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}  
