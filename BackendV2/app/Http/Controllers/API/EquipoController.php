<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    // Mostrar lista de equipos
    public function index()
    {
        $equipos = Equipo::with(['torneo', 'jugadores', 'capitan'])->get();
        return response()->json($equipos);
    }

    // Crear un nuevo equipo
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'torneo_id' => 'required|exists:torneos,id',
            'capitan_id' => 'nullable|exists:jugadors,id',
        ]);

        $equipo = Equipo::create($request->all());

        return response()->json($equipo, 201);
    }

    // Mostrar un equipo específico
    public function show($id)
    {
        $equipo = Equipo::with(['torneo', 'jugadores', 'capitan'])->findOrFail($id);
        return response()->json($equipo);
    }

    // Actualizar un equipo
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'capitan_id' => 'nullable|exists:jugadors,id',
        ]);

        $equipo = Equipo::findOrFail($id);
        $equipo->update($request->all());

        return response()->json($equipo);
    }

    // Eliminar un equipo
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return response()->json(null, 204);
    }
}
