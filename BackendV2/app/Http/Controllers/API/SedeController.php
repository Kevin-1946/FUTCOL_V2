<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    // Mostrar todas las sedes
    public function index()
    {
        return response()->json(Sede::with('torneo', 'encuentros')->get());
    }

    // Crear una nueva sede
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'torneo_id' => 'required|exists:torneos,id',
        ]);

        $sede = Sede::create($validated);
        return response()->json($sede, 201);
    }

    // Mostrar una sede específica
    public function show($id)
    {
        $sede = Sede::with('torneo', 'encuentros')->findOrFail($id);
        return response()->json($sede);
    }

    // Actualizar una sede
    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'torneo_id' => 'sometimes|exists:torneos,id',
        ]);

        $sede->update($validated);
        return response()->json($sede);
    }

    // Eliminar una sede
    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);
        $sede->delete();

        return response()->json(['message' => 'Sede eliminada correctamente']);
    }
}