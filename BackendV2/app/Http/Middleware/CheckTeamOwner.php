<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Team; // Asumiendo que tienes un modelo Team
use Symfony\Component\HttpFoundation\Response;

class CheckTeamOwner
{
    /**
     * Handle an incoming request.
     * Verifica que el capitán solo pueda acceder a recursos de su propio equipo
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        $user = Auth::user();
        
        // Si es administrador, puede acceder a todo
        if ($user->role === 'administrador') {
            return $next($request);
        }

        // Si es capitán, verificar que sea de su equipo
        if ($user->role === 'capitan') {
            $teamId = $this->getTeamIdFromRequest($request);
            
            if (!$teamId) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID del equipo no encontrado en la solicitud',
                    'error_code' => 'TEAM_ID_NOT_FOUND'
                ], 400);
            }

            // Verificar que el capitán sea el dueño del equipo
            $team = Team::find($teamId);
            
            if (!$team) {
                return response()->json([
                    'success' => false,
                    'message' => 'Equipo no encontrado',
                    'error_code' => 'TEAM_NOT_FOUND'
                ], 404);
            }

            if ($team->capitan_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo puedes gestionar tu propio equipo',
                    'error_code' => 'NOT_TEAM_OWNER'
                ], 403);
            }

            // Agregar el equipo al request para uso posterior
            $request->merge(['team' => $team]);
            
            return $next($request);
        }

        // Los participantes no pueden acceder a rutas de gestión de equipos
        return response()->json([
            'success' => false,
            'message' => 'No tienes permisos para gestionar equipos',
            'error_code' => 'INSUFFICIENT_PERMISSIONS'
        ], 403);
    }

    /**
     * Extraer el ID del equipo desde diferentes fuentes de la request
     */
    private function getTeamIdFromRequest(Request $request): ?int
    {
        // Buscar en parámetros de ruta
        if ($request->route('team')) {
            return (int) $request->route('team');
        }

        if ($request->route('team_id')) {
            return (int) $request->route('team_id');
        }

        // Buscar en el cuerpo de la request
        if ($request->has('team_id')) {
            return (int) $request->input('team_id');
        }

        // Buscar en query parameters
        if ($request->query('team_id')) {
            return (int) $request->query('team_id');
        }

        return null;
    }
}