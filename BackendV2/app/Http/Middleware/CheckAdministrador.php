<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdministrador
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        if (Auth::user()->role !== 'administrador') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los administradores pueden acceder a este recurso',
                'error_code' => 'ADMIN_REQUIRED'
            ], 403);
        }

        return $next($request);
    }
}