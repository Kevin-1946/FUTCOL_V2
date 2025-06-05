<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 🆕 Registro del capitán y su equipo
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'equipo_nombre' => 'required|string|max:255',
        ]);

        // Crear usuario con rol de capitán (role_id = 2)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Capitán
        ]);

        // Crear equipo y asociar al capitán
        $equipo = Equipo::create([
            'nombre' => $request->equipo_nombre,
            'capitan_id' => $user->id,
        ]);

        // Asignar equipo al usuario (si tienes `equipo_id` en `users`)
        $user->equipo_id = $equipo->id;
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('role', 'equipo'),
        ], 201);
    }

    // 🔐 Login
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('role', 'equipo'),
        ]);
    }

    // 🚪 Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

    // 👤 Perfil del usuario autenticado
    public function perfil(Request $request)
    {
        return response()->json(
            $request->user()->load('role', 'equipo')
        );
    }

    // 🔁 Verificar sesión activa (opcional)
    public function refresh(Request $request)
    {
        return response()->json([
            'message' => 'Sesión activa',
            'user' => $request->user()->load('role', 'equipo')
        ]);
    }
}