<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    // Listar todos los torneos con sus relaciones
    public function index()
    {
        return response()->json(
            Torneo::with(['equipos', 'sedes', 'suscripciones', 'recibosDePago', 'encuentros'])->get()
        );
    }

    // Crear un nuevo torneo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $torneo = Torneo::create($validated);
        return response()->json($torneo, 201);
    }

    // Mostrar un torneo específico con relaciones
    public function show($id)
    {
        $torneo = Torneo::with(['equipos', 'sedes', 'suscripciones', 'recibosDePago', 'encuentros'])->findOrFail($id);
        return response()->json($torneo);
    }

    // Actualizar un torneo
    public function update(Request $request, $id)
    {
        $torneo = Torneo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'categoria' => 'sometimes|string|max:100',
            'fecha_inicio' => 'sometimes|date',
            'fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio',
        ]);

        $torneo->update($validated);
        return response()->json($torneo);
    }

    // Eliminar un torneo
    public function destroy($id)
    {
        $torneo = Torneo::findOrFail($id);
        $torneo->delete();

        return response()->json(['message' => 'Torneo eliminado correctamente.']);
    }
}