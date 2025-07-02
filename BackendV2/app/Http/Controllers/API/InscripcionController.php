<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Inscripciones",
 *     description="Operaciones relacionadas con las inscripciones de equipos en torneos"
 * )
 */
class InscripcionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/inscripciones",
     *     summary="Listar todas las inscripciones",
     *     tags={"Inscripciones"},
     *     @OA\Response(response=200, description="Lista de inscripciones")
     * )
     */
    public function index()
    {
        $inscripciones = Inscripcion::with(['equipo', 'torneo'])->get();
        return response()->json($inscripciones);
    }

    /**
     * @OA\Post(
     *     path="/api/inscripciones",
     *     summary="Crear una nueva inscripción",
     *     tags={"Inscripciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"equipo_id", "torneo_id", "fecha_de_inscripcion", "forma_pago", "estado_pago", "correo_confirmado", "total_pagado"},
     *             @OA\Property(property="equipo_id", type="integer", example=1),
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="fecha_de_inscripcion", type="string", format="date", example="2024-09-01"),
     *             @OA\Property(property="forma_pago", type="string", example="Transferencia"),
     *             @OA\Property(property="estado_pago", type="string", example="Pagado"),
     *             @OA\Property(property="correo_confirmado", type="boolean", example=true),
     *             @OA\Property(property="total_pagado", type="number", format="float", example=150000)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Inscripción creada correctamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'torneo_id' => 'required|exists:torneos,id',
            'fecha_de_inscripcion' => 'required|date',
            'forma_pago' => 'required|string|max:100',
            'estado_pago' => 'required|string|max:100',
            'correo_confirmado' => 'required|boolean',
            'total_pagado' => 'required|numeric|min:0',
        ]);

        $inscripcion = Inscripcion::create($request->all());

        return response()->json($inscripcion, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/inscripciones/{id}",
     *     summary="Mostrar una inscripción específica",
     *     tags={"Inscripciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la inscripción"
     *     ),
     *     @OA\Response(response=200, description="Detalles de la inscripción"),
     *     @OA\Response(response=404, description="Inscripción no encontrada")
     * )
     */
    public function show($id)
    {
        $inscripcion = Inscripcion::with(['equipo', 'torneo'])->findOrFail($id);
        return response()->json($inscripcion);
    }

    /**
     * @OA\Put(
     *     path="/api/inscripciones/{id}",
     *     summary="Actualizar una inscripción",
     *     tags={"Inscripciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la inscripción a actualizar"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="forma_pago", type="string", example="Efectivo"),
     *             @OA\Property(property="estado_pago", type="string", example="Pendiente"),
     *             @OA\Property(property="total_pagado", type="number", format="float", example=75000)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Inscripción actualizada correctamente"),
     *     @OA\Response(response=404, description="Inscripción no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        $request->validate([
            'equipo_id' => 'sometimes|required|exists:equipos,id',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'fecha_de_inscripcion' => 'sometimes|required|date',
            'forma_pago' => 'sometimes|required|string|max:100',
            'estado_pago' => 'sometimes|required|string|max:100',
            'correo_confirmado' => 'sometimes|required|boolean',
            'total_pagado' => 'sometimes|required|numeric|min:0',
        ]);

        $inscripcion->update($request->all());

        return response()->json($inscripcion);
    }

    /**
     * @OA\Delete(
     *     path="/api/inscripciones/{id}",
     *     summary="Eliminar una inscripción",
     *     tags={"Inscripciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la inscripción a eliminar"
     *     ),
     *     @OA\Response(response=204, description="Inscripción eliminada correctamente"),
     *     @OA\Response(response=404, description="Inscripción no encontrada")
     * )
     */
    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();

        return response()->json(null, 204);
    }
}
