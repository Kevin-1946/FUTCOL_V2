<?php

namespace App\Http\Controllers;

use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JugadorController extends Controller
{
    // Mostrar lista de jugadores
    public function index()
    {
        $jugadores = Jugador::with('equipo', 'equiposCapitaneados')->get();
        return response()->json($jugadores);
    }

    // Crear un nuevo jugador
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'n_documento' => 'required|string|unique:jugadors,n_documento',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|email|unique:jugadors,email',
            'password' => 'required|string|min:6',
            'equipo_id' => 'nullable|exists:equipos,id',
        ]);

        $jugador = Jugador::create([
            'nombre' => $request->nombre,
            'n_documento' => $request->n_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'equipo_id' => $request->equipo_id,
        ]);

        return response()->json($jugador, 201);
    }

    // Mostrar un jugador específico
    public function show($id)
    {
        $jugador = Jugador::with('equipo', 'equiposCapitaneados')->findOrFail($id);
        return response()->json($jugador);
    }

    // Actualizar un jugador
    public function update(Request $request, $id)
    {
        $jugador = Jugador::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'n_documento' => 'sometimes|required|string|unique:jugadors,n_documento,' . $jugador->id,
            'fecha_nacimiento' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:jugadors,email,' . $jugador->id,
            'password' => 'nullable|string|min:6',
            'equipo_id' => 'nullable|exists:equipos,id',
        ]);

        $jugador->nombre = $request->nombre ?? $jugador->nombre;
        $jugador->n_documento = $request->n_documento ?? $jugador->n_documento;
        $jugador->fecha_nacimiento = $request->fecha_nacimiento ?? $jugador->fecha_nacimiento;
        $jugador->email = $request->email ?? $jugador->email;
        if ($request->filled('password')) {
            $jugador->password = Hash::make($request->password);
        }
        $jugador->equipo_id = $request->equipo_id ?? $jugador->equipo_id;

        $jugador->save();

        return response()->json($jugador);
    }

    // Eliminar un jugador
    public function destroy($id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->delete();

        return response()->json(null, 204);
    }
}
