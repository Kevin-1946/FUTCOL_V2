<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReciboDePago;
use Illuminate\Http\Request;

class ReciboDePagoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/recibos",
     *     summary="Listar todos los recibos de pago",
     *     tags={"Recibos"},
     *     @OA\Response(response=200, description="Lista de recibos de pago")
     * )
     */
    public function index()
    {
        $recibos = ReciboDePago::with(['suscripcion', 'torneo'])->get();
        return response()->json($recibos);
    }

    /**
     * @OA\Post(
     *     path="/api/recibos",
     *     summary="Crear un nuevo recibo de pago",
     *     tags={"Recibos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"suscripcion_id", "torneo_id", "monto", "fecha_emision", "confirmado", "metodo_pago", "numero_comprobante"},
     *             @OA\Property(property="suscripcion_id", type="integer", example=1),
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="monto", type="number", format="float", example=150000),
     *             @OA\Property(property="fecha_emision", type="string", format="date", example="2024-10-01"),
     *             @OA\Property(property="confirmado", type="boolean", example=true),
     *             @OA\Property(property="metodo_pago", type="string", example="Transferencia"),
     *             @OA\Property(property="numero_comprobante", type="string", example="ABC12345")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Recibo creado exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'suscripcion_id' => 'required|exists:suscripcions,id',
            'torneo_id' => 'required|exists:torneos,id',
            'monto' => 'required|numeric|min:0',
            'fecha_emision' => 'required|date',
            'confirmado' => 'required|boolean',
            'metodo_pago' => 'required|string|max:100',
            'numero_comprobante' => 'required|string|max:100',
        ]);

        $recibo = ReciboDePago::create($request->all());

        return response()->json($recibo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/recibos/{id}",
     *     summary="Mostrar un recibo de pago específico",
     *     tags={"Recibos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información del recibo de pago"),
     *     @OA\Response(response=404, description="Recibo no encontrado")
     * )
     */
    public function show($id)
    {
        $recibo = ReciboDePago::with(['suscripcion', 'torneo'])->findOrFail($id);
        return response()->json($recibo);
    }

    /**
     * @OA\Put(
     *     path="/api/recibos/{id}",
     *     summary="Actualizar un recibo de pago",
     *     tags={"Recibos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="suscripcion_id", type="integer"),
     *             @OA\Property(property="torneo_id", type="integer"),
     *             @OA\Property(property="monto", type="number", format="float"),
     *             @OA\Property(property="fecha_emision", type="string", format="date"),
     *             @OA\Property(property="confirmado", type="boolean"),
     *             @OA\Property(property="metodo_pago", type="string"),
     *             @OA\Property(property="numero_comprobante", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Recibo actualizado exitosamente"),
     *     @OA\Response(response=404, description="Recibo no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $recibo = ReciboDePago::findOrFail($id);

        $request->validate([
            'suscripcion_id' => 'sometimes|required|exists:suscripcions,id',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'monto' => 'sometimes|required|numeric|min:0',
            'fecha_emision' => 'sometimes|required|date',
            'confirmado' => 'sometimes|required|boolean',
            'metodo_pago' => 'sometimes|required|string|max:100',
            'numero_comprobante' => 'sometimes|required|string|max:100',
        ]);

        $recibo->update($request->all());

        return response()->json($recibo);
    }

    /**
     * @OA\Delete(
     *     path="/api/recibos/{id}",
     *     summary="Eliminar un recibo de pago",
     *     tags={"Recibos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Recibo eliminado exitosamente"),
     *     @OA\Response(response=404, description="Recibo no encontrado")
     * )
     */
    public function destroy($id)
    {
        $recibo = ReciboDePago::findOrFail($id);
        $recibo->delete();

        return response()->json(null, 204);
    }
}
