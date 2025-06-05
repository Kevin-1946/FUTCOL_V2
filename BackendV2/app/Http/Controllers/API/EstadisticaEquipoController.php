<?php

namespace App\Http\Controllers;

use App\Models\EstadisticaEquipo;
use Illuminate\Http\Request;

class EstadisticaEquipoController extends Controller
{
    // Listar todas las estadísticas
    public function index()
    {
        $estadisticas = EstadisticaEquipo::with(['equipo', 'torneo'])->get();
        return response()->json($estadisticas);
    }

    // Crear una nueva estadística
    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'torneo_id' => 'required|exists:torneos,id',
            'partidos_jugados' => 'required|integer|min:0',
            'partidos_ganados' => 'required|integer|min:0',
            'partidos_empatados' => 'required|integer|min:0',
            'partidos_perdidos' => 'required|integer|min:0',
            'goles_a_favor' => 'required|integer|min:0',
            'goles_en_contra' => 'required|integer|min:0',
            'diferencia_de_goles' => 'required|integer',
            'puntos' => 'required|integer|min:0',
        ]);

        $estadistica = EstadisticaEquipo::create($request->all());

        return response()->json($estadistica, 201);
    }

    // Mostrar una estadística específica
    public function show($id)
    {
        $estadistica = EstadisticaEquipo::with(['equipo', 'torneo'])->findOrFail($id);
        return response()->json($estadistica);
    }

    // Actualizar una estadística
    public function update(Request $request, $id)
    {
        $estadistica = EstadisticaEquipo::findOrFail($id);

        $request->validate([
            'equipo_id' => 'sometimes|required|exists:equipos,id',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'partidos_jugados' => 'sometimes|required|integer|min:0',
            'partidos_ganados' => 'sometimes|required|integer|min:0',
            'partidos_empatados' => 'sometimes|required|integer|min:0',
            'partidos_perdidos' => 'sometimes|required|integer|min:0',
            'goles_a_favor' => 'sometimes|required|integer|min:0',
            'goles_en_contra' => 'sometimes|required|integer|min:0',
            'diferencia_de_goles' => 'sometimes|required|integer',
            'puntos' => 'sometimes|required|integer|min:0',
        ]);

        $estadistica->update($request->all());

        return response()->json($estadistica);
    }

    // Eliminar una estadística
    public function destroy($id)
    {
        $estadistica = EstadisticaEquipo::findOrFail($id);
        $estadistica->delete();

        return response()->json(null, 204);
    }
}