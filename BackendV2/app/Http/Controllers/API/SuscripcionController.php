<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/suscripciones",
     *     summary="Listar todas las suscripciones",
     *     tags={"Suscripciones"},
     *     @OA\Response(response=200, description="Lista de suscripciones")
     * )
     */
    public function index()
    {
        $suscripciones = Suscripcion::with(['torneo', 'equipo', 'recibos'])->get();
        return response()->json($suscripciones);
    }

    /**
     * @OA\Post(
     *     path="/api/suscripciones",
     *     summary="Crear una nueva suscripción",
     *     tags={"Suscripciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"torneo_id", "equipo_id", "fecha_suscripcion", "estado"},
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="equipo_id", type="integer", example=1),
     *             @OA\Property(property="fecha_suscripcion", type="string", format="date", example="2024-10-01"),
     *             @OA\Property(property="estado", type="string", example="pendiente")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Suscripción creada exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/suscripciones/{id}",
     *     summary="Mostrar una suscripción específica",
     *     tags={"Suscripciones"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información de la suscripción"),
     *     @OA\Response(response=404, description="Suscripción no encontrada")
     * )
     */
    public function show($id)
    {
        $suscripcion = Suscripcion::with(['torneo', 'equipo', 'recibos'])->findOrFail($id);
        return response()->json($suscripcion);
    }

    /**
     * @OA\Put(
     *     path="/api/suscripciones/{id}",
     *     summary="Actualizar una suscripción",
     *     tags={"Suscripciones"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="torneo_id", type="integer"),
     *             @OA\Property(property="equipo_id", type="integer"),
     *             @OA\Property(property="fecha_suscripcion", type="string", format="date"),
     *             @OA\Property(property="estado", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Suscripción actualizada exitosamente"),
     *     @OA\Response(response=404, description="Suscripción no encontrada")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/suscripciones/{id}",
     *     summary="Eliminar una suscripción",
     *     tags={"Suscripciones"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Suscripción eliminada exitosamente"),
     *     @OA\Response(response=404, description="Suscripción no encontrada")
     * )
     */
    public function destroy($id)
    {
        $suscripcion = Suscripcion::findOrFail($id);
        $suscripcion->delete();

        return response()->json(null, 204);
    }
}
