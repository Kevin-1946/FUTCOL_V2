<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jugador;
use Symfony\Component\HttpFoundation\Response;

class CheckJugadorOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Debes iniciar sesión para acceder a este recurso.',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        $user = Auth::user();

        // 2. Verificar que el usuario tenga rol de capitán
        if ($user->role !== 'capitan') {
            return response()->json([
                'success' => false,
                'message' => 'Solo los capitanes pueden modificar jugadores.',
                'error_code' => 'INSUFFICIENT_PERMISSIONS',
                'required_role' => 'capitan',
                'user_role' => $user->role
            ], 403);
        }

        // 3. Obtener el ID del jugador de la URL
        $jugadorId = $request->route('jugador');
        
        if (!$jugadorId) {
            return response()->json([
                'success' => false,
                'message' => 'ID del jugador no proporcionado en la URL.',
                'error_code' => 'MISSING_PLAYER_ID'
            ], 400);
        }

        // 4. Buscar el jugador en la base de datos
        $jugador = Jugador::find($jugadorId);

        if (!$jugador) {
            return response()->json([
                'success' => false,
                'message' => 'Jugador no encontrado.',
                'error_code' => 'PLAYER_NOT_FOUND'
            ], 404);
        }

        // 5. Verificar que el jugador pertenezca al equipo del capitán
        if ($jugador->team_id !== $user->team_id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes modificar jugadores de otros equipos.',
                'error_code' => 'TEAM_OWNERSHIP_MISMATCH',
                'player_team_id' => $jugador->team_id,
                'user_team_id' => $user->team_id
            ], 403);
        }

        // 6. Agregar información útil al request para uso posterior
        $request->merge([
            'authenticated_user' => $user,
            'jugador' => $jugador,
            'user_role' => $user->role
        ]);

        return $next($request);
    }
}