<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EstadisticaEquipo;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Estadísticas de Equipos",
 *     description="Operaciones para gestionar estadísticas de equipos en torneos"
 * )
 */
class EstadisticaEquipoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/estadisticas-equipos",
     *     summary="Listar todas las estadísticas de equipos",
     *     tags={"Estadísticas de Equipos"},
     *     @OA\Response(response=200, description="Lista de estadísticas")
     * )
     */
    public function index()
    {
        $estadisticas = EstadisticaEquipo::with(['equipo', 'torneo'])->get();
        return response()->json($estadisticas);
    }

    /**
     * @OA\Post(
     *     path="/api/estadisticas-equipos",
     *     summary="Crear una nueva estadística de equipo",
     *     tags={"Estadísticas de Equipos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"equipo_id","torneo_id","partidos_jugados","partidos_ganados","partidos_empatados","partidos_perdidos","goles_a_favor","goles_en_contra","diferencia_de_goles","puntos"},
     *             @OA\Property(property="equipo_id", type="integer", example=1),
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="partidos_jugados", type="integer", example=5),
     *             @OA\Property(property="partidos_ganados", type="integer", example=3),
     *             @OA\Property(property="partidos_empatados", type="integer", example=1),
     *             @OA\Property(property="partidos_perdidos", type="integer", example=1),
     *             @OA\Property(property="goles_a_favor", type="integer", example=10),
     *             @OA\Property(property="goles_en_contra", type="integer", example=6),
     *             @OA\Property(property="diferencia_de_goles", type="integer", example=4),
     *             @OA\Property(property="puntos", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Estadística creada exitosamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/estadisticas-equipos/{id}",
     *     summary="Mostrar una estadística de equipo específica",
     *     tags={"Estadísticas de Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la estadística"
     *     ),
     *     @OA\Response(response=200, description="Detalles de la estadística"),
     *     @OA\Response(response=404, description="Estadística no encontrada")
     * )
     */
    public function show($id)
    {
        $estadistica = EstadisticaEquipo::with(['equipo', 'torneo'])->findOrFail($id);
        return response()->json($estadistica);
    }

    /**
     * @OA\Put(
     *     path="/api/estadisticas-equipos/{id}",
     *     summary="Actualizar una estadística de equipo",
     *     tags={"Estadísticas de Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la estadística"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="partidos_jugados", type="integer", example=6),
     *             @OA\Property(property="puntos", type="integer", example=12)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Estadística actualizada correctamente"),
     *     @OA\Response(response=404, description="Estadística no encontrada")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/estadisticas-equipos/{id}",
     *     summary="Eliminar una estadística de equipo",
     *     tags={"Estadísticas de Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la estadística"
     *     ),
     *     @OA\Response(response=204, description="Estadística eliminada exitosamente"),
     *     @OA\Response(response=404, description="Estadística no encontrada")
     * )
     */
    public function destroy($id)
    {
        $estadistica = EstadisticaEquipo::findOrFail($id);
        $estadistica->delete();

        return response()->json(null, 204);
    }
}
