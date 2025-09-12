<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Amonestacion;
use App\Models\Jugador;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Amonestaciones",
 *     description="Operaciones relacionadas con las amonestaciones de los jugadores"
 * )
 */
class AmonestacionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/amonestaciones",
     *     summary="Obtener todas las amonestaciones",
     *     tags={"Amonestaciones"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de amonestaciones"
     *     )
     * )
     */
    public function index()
    {
        $amonestaciones = Amonestacion::with(['jugador', 'equipo', 'encuentro'])->get();
        return response()->json($amonestaciones);
    }

    /**
     * @OA\Post(
     *     path="/api/amonestaciones",
     *     summary="Crear una nueva amonestación",
     *     tags={"Amonestaciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"jugador_id","equipo_id","encuentro_id","numero_camiseta"},
     *             @OA\Property(property="jugador_id", type="integer", example=1),
     *             @OA\Property(property="equipo_id", type="integer", example=2),
     *             @OA\Property(property="encuentro_id", type="integer", example=3),
     *             @OA\Property(property="numero_camiseta", type="integer", example=10),
     *             @OA\Property(property="tarjeta_roja", type="boolean", example=false),
     *             @OA\Property(property="tarjeta_amarilla", type="boolean", example=true),
     *             @OA\Property(property="tarjeta_azul", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Amonestación creada correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errores de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadores,id',
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

    /**
     * @OA\Get(
     *     path="/api/amonestaciones/{id}",
     *     summary="Obtener una amonestación específica",
     *     tags={"Amonestaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la amonestación",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la amonestación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Amonestación no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $amonestacion = Amonestacion::with(['jugador', 'equipo', 'encuentro'])->findOrFail($id);
        return response()->json($amonestacion);
    }

    /**
     * @OA\Put(
     *     path="/api/amonestaciones/{id}",
     *     summary="Actualizar una amonestación",
     *     tags={"Amonestaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la amonestación a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="jugador_id", type="integer", example=1),
     *             @OA\Property(property="equipo_id", type="integer", example=2),
     *             @OA\Property(property="encuentro_id", type="integer", example=3),
     *             @OA\Property(property="numero_camiseta", type="integer", example=7),
     *             @OA\Property(property="tarjeta_roja", type="boolean", example=false),
     *             @OA\Property(property="tarjeta_amarilla", type="boolean", example=true),
     *             @OA\Property(property="tarjeta_azul", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Amonestación actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Amonestación no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $amonestacion = Amonestacion::findOrFail($id);

        $request->validate([
            'jugador_id' => 'sometimes|required|exists:jugadores,id',
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

    /**
     * @OA\Delete(
     *     path="/api/amonestaciones/{id}",
     *     summary="Eliminar una amonestación",
     *     tags={"Amonestaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la amonestación a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Amonestación eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Amonestación no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $amonestacion = Amonestacion::findOrFail($id);
        $amonestacion->delete();

        return response()->json(null, 204);
    }
}
