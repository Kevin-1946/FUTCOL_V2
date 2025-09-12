<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jugador;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Operaciones relacionadas con el registro, inicio de sesión y gestión de usuarios autenticados"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar un nuevo usuario capitán con su equipo",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation","equipo_nombre","n_documento","fecha_nacimiento"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123"),
     *             @OA\Property(property="password_confirmation", type="string", example="secret123"),
     *             @OA\Property(property="equipo_nombre", type="string", example="Los Tigres"),
     *             @OA\Property(property="n_documento", type="string", example="123456789"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="2000-01-01")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Registro exitoso"),
     *     @OA\Response(response=500, description="Error en el registro")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:jugadores,email',
            'password' => 'required|string|min:6|confirmed',
            'equipo_nombre' => 'required|string|max:255',
            'n_documento' => 'required|string|unique:jugadores,n_documento',
            'fecha_nacimiento' => 'required|date',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);

            $jugador = Jugador::create([
                'nombre' => $request->name,
                'n_documento' => $request->n_documento,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_id' => $user->id,
            ]);

            $equipo = Equipo::create([
                'nombre' => $request->equipo_nombre,
                'capitan_id' => $jugador->id,
            ]);

            $jugador->equipo_id = $equipo->id;
            $jugador->save();

            $user->equipo_id = $equipo->id;
            $user->save();

            $user->load('role', 'equipo');
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registro exitoso',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'equipo' => $user->equipo,
                    'role' => [
                        'nombre' => ucfirst(strtolower($user->role->nombre))
                    ]
                ],
                'jugador' => $jugador->load('equipo'),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el registro',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión de usuario",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Inicio de sesión exitoso"),
     *     @OA\Response(response=422, description="Credenciales incorrectas")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $user->load('role', 'equipo');
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nombre' => $user->name,
                'email' => $user->email,
                'equipo' => $user->equipo,
                'role' => [
                    'nombre' => ucfirst(strtolower($user->role->nombre))
                ]
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Sesión cerrada correctamente")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/perfil",
     *     summary="Obtener el perfil del usuario autenticado",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Perfil del usuario")
     * )
     */
    public function perfil(Request $request)
    {
        $user = $request->user()->load('role', 'equipo');

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'equipo' => $user->equipo,
            'role' => [
                'nombre' => ucfirst(strtolower($user->role->nombre))
            ]
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/refresh",
     *     summary="Verificar sesión activa",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Sesión activa")
     * )
     */
    public function refresh(Request $request)
    {
        $user = $request->user()->load('role', 'equipo');

        return response()->json([
            'message' => 'Sesión activa',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'equipo' => $user->equipo,
                'role' => [
                    'nombre' => ucfirst(strtolower($user->role->nombre))
                ]
            ]
        ]);
    }
}
