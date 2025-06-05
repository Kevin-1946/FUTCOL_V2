<?php

namespace App\Http\Controllers;

use App\Models\LoginUsuario;
use Illuminate\Http\Request;

class LoginUsuarioController extends Controller
{
    // Listar todos los registros de login
    public function index()
    {
        $logins = LoginUsuario::with('jugador')->get();
        return response()->json($logins);
    }

    // Crear un nuevo registro de login
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadors,id',
            'ip' => 'required|ip',
            'user_agent' => 'required|string|max:255',
            'exitoso' => 'required|boolean',
            'fecha_login' => 'required|date',
        ]);

        $login = LoginUsuario::create($request->all());

        return response()->json($login, 201);
    }

    // Mostrar un login específico
    public function show($id)
    {
        $login = LoginUsuario::with('jugador')->findOrFail($id);
        return response()->json($login);
    }

    // Actualizar un login
    public function update(Request $request, $id)
    {
        $login = LoginUsuario::findOrFail($id);

        $request->validate([
            'jugador_id' => 'sometimes|required|exists:jugadors,id',
            'ip' => 'sometimes|required|ip',
            'user_agent' => 'sometimes|required|string|max:255',
            'exitoso' => 'sometimes|required|boolean',
            'fecha_login' => 'sometimes|required|date',
        ]);

        $login->update($request->all());

        return response()->json($login);
    }

    // Eliminar un login
    public function destroy($id)
    {
        $login = LoginUsuario::findOrFail($id);
        $login->delete();

        return response()->json(null, 204);
    }
}