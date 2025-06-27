<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    // Listar todas las suscripciones
    public function index()
    {
        $suscripciones = Suscripcion::with(['torneo', 'equipo', 'recibos'])->get();
        return response()->json($suscripciones);
    }

    // Crear una nueva suscripción
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'equipo_id' => 'required|exists:equipos,id',
            'fecha_suscripcion' => 'required|date',
            'estado' => 'required|string|max:50',
        ]);

        $suscripcion = Suscripcion::create($request->all());

        return response()->json($suscripcion, 201);
    }

    // Mostrar una suscripción específica
    public function show($id)
    {
        $suscripcion = Suscripcion::with(['torneo', 'equipo', 'recibos'])->findOrFail($id);
        return response()->json($suscripcion);
    }

    // Actualizar una suscripción
    public function update(Request $request, $id)
    {
        $suscripcion = Suscripcion::findOrFail($id);

        $request->validate([
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'equipo_id' => 'sometimes|required|exists:equipos,id',
            'fecha_suscripcion' => 'sometimes|required|date',
            'estado' => 'sometimes|required|string|max:50',
        ]);

        $suscripcion->update($request->all());

        return response()->json($suscripcion);
    }

    // Eliminar una suscripción
    public function destroy($id)
    {
        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->delete();

        return response()->json(null, 204);
    }
}
