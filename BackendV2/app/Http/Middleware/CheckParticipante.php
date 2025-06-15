<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckParticipante
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

        if (Auth::user()->role !== 'participante') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los participantes pueden acceder a este recurso',
                'error_code' => 'PARTICIPANT_REQUIRED'
            ], 403);
        }

        return $next($request);
    }
}