<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    // Definir constantes para los roles
    const ADMINISTRADOR = 'administrador';
    const CAPITAN = 'capitan';
    const PARTICIPANTE = 'participante';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado. Debes iniciar sesión para acceder a este recurso.',
                'error_code' => 'UNAUTHENTICATED'
            ], 401);
        }

        $user = Auth::user();
        
        // Verificar si el usuario tiene un rol válido
        if (empty($user->role)) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario sin rol asignado. Contacta al administrador.',
                'error_code' => 'NO_ROLE_ASSIGNED'
            ], 403);
        }

        // Verificar si el rol del usuario está en los roles permitidos
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para acceder a este recurso.',
                'error_code' => 'INSUFFICIENT_PERMISSIONS',
                'required_roles' => $roles,
                'user_role' => $user->role
            ], 403);
        }

        // Agregar información del usuario y rol al request para uso posterior
        $request->merge([
            'authenticated_user' => $user,
            'user_role' => $user->role
        ]);

        return $next($request);
    }

    /**
     * Verificar si un usuario tiene un rol específico
     */
    public static function hasRole($user, string $role): bool
    {
        return $user && $user->role === $role;
    }

    /**
     * Verificar si un usuario es administrador
     */
    public static function isAdministrador($user): bool
    {
        return self::hasRole($user, self::ADMINISTRADOR);
    }

    /**
     * Verificar si un usuario es capitán
     */
    public static function isCapitan($user): bool
    {
        return self::hasRole($user, self::CAPITAN);
    }

    /**
     * Verificar si un usuario es participante
     */
    public static function isParticipante($user): bool
    {
        return self::hasRole($user, self::PARTICIPANTE);
    }

    /**
     * Obtener todos los roles disponibles
     */
    public static function getAllRoles(): array
    {
        return [
            self::ADMINISTRADOR,
            self::CAPITAN,
            self::PARTICIPANTE
        ];
    }
}