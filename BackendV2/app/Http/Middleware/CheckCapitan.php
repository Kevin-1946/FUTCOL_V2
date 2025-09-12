<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCapitan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $access_type - 'read-only' para solo lectura, null para acceso completo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $access_type = null)
    {
        // 1. Verificar autenticación
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        $user = Auth::user();

        // 2. Verificar que sea capitán
        if ($user->role !== 'capitan') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los capitanes pueden acceder a este recurso',
                'error_code' => 'CAPTAIN_REQUIRED'
            ], 403);
        }

        // 3. Si es acceso de solo lectura, verificar que solo haga GET
        if ($access_type === 'read-only' && !$request->isMethod('GET')) {
            return response()->json([
                'success' => false,
                'message' => 'Los capitanes solo pueden consultar esta información, no modificarla.',
                'error_code' => 'READ_ONLY_ACCESS',
                'allowed_methods' => ['GET'],
                'attempted_method' => $request->method()
            ], 403);
        }

        // 4. Agregar información útil al request
        $request->merge([
            'authenticated_user' => $user,
            'user_role' => $user->role,
            'is_read_only' => ($access_type === 'read-only')
        ]);

        return $next($request);
    }
}