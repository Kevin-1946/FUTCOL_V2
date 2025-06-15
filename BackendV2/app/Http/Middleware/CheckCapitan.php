<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCapitan
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

        if (Auth::user()->role !== 'capitan') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los capitanes pueden acceder a este recurso',
                'error_code' => 'CAPTAIN_REQUIRED'
            ], 403);
        }

        return $next($request);
    }
}