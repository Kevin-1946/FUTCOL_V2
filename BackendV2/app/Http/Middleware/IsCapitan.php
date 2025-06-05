<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCapitan
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()?->role_id !== 2) {
            abort(403, 'Acceso restringido solo a capitanes');
        }

        return $next($request);
    }
}
