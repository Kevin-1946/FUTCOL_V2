<?php

namespace App\Http\Controllers;

use App\Models\ResetContrasena;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ResetContrasenaController extends Controller
{
    // Crear o actualizar solicitud de restablecimiento
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Generar token único
        $token = Str::random(64);

        // Insertar o actualizar el token
        ResetContrasena::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        // Aquí puedes enviar el token por correo si deseas

        return response()->json(['message' => 'Token generado', 'token' => $token]);
    }

    // Verificar si un token es válido (por ejemplo, para mostrar el formulario de reset)
    public function verifyToken($token)
    {
        $registro = ResetContrasena::where('token', $token)
            ->where('created_at', '>=', Carbon::now()->subMinutes(60)) // Token válido por 60 min
            ->first();

        if (!$registro) {
            return response()->json(['message' => 'Token inválido o expirado'], 404);
        }

        return response()->json(['message' => 'Token válido', 'email' => $registro->email]);
    }

    // Eliminar token (por ejemplo, después de restablecer la contraseña)
    public function destroy($token)
    {
        ResetContrasena::where('token', $token)->delete();

        return response()->json(['message' => 'Token eliminado']);
    }
}