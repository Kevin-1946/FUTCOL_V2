<?php

namespace App\Http\Controllers;

use App\Models\Amonestacion;
use Illuminate\Http\Request;

class AmonestacionController extends Controller
{
    // Mostrar todas las amonestaciones
    public function index()
    {
        $amonestaciones = Amonestacion::with(['jugador', 'equipo', 'encuentro'])->get();
        return response()->json($amonestaciones);
    }

    // Crear una nueva amonestación
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadors,id',
            'equipo_id' => 'required|exists:equipos,id',
            'encuentro_id' => 'required|exists:encuentros,id',
            'numero_camiseta' => 'required|integer|min:0',
            'tarjeta_roja' => 'nullable|boolean',
            'tarjeta_amarilla' => 'nullable|boolean',
            'tarjeta_azul' => 'nullable|boolean',
        ]);

        $amonestacion = Amonestacion::create($request->all());

        return response()->json($amonestacion, 201);
    }

    // Mostrar una amonestación específica
    public function show($id)
    {
        $amonestacion = Amonestacion::with(['jugador', 'equipo', 'encuentro'])->findOrFail($id);
        return response()->json($amonestacion);
    }

    // Actualizar una amonestación
    public function update(Request $request, $id)
    {
        $amonestacion = Amonestacion::findOrFail($id);

        $request->validate([
            'jugador_id' => 'sometimes|required|exists:jugadors,id',
            'equipo_id' => 'sometimes|required|exists:equipos,id',
            'encuentro_id' => 'sometimes|required|exists:encuentros,id',
            'numero_camiseta' => 'sometimes|required|integer|min:0',
            'tarjeta_roja' => 'nullable|boolean',
            'tarjeta_amarilla' => 'nullable|boolean',
            'tarjeta_azul' => 'nullable|boolean',
        ]);

        $amonestacion->update($request->all());

        return response()->json($amonestacion);
    }

    // Eliminar una amonestación
    public function destroy($id)
    {
        $amonestacion = Amonestacion::findOrFail($id);
        $amonestacion->delete();

        return response()->json(null, 204);
    }
}