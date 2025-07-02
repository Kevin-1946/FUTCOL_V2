<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Encuentro;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Encuentros",
 *     description="Operaciones relacionadas con los encuentros deportivos"
 * )
 */
class EncuentroController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/encuentros",
     *     summary="Listar todos los encuentros",
     *     tags={"Encuentros"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de encuentros"
     *     )
     * )
     */
    public function index()
    {
        $encuentros = Encuentro::with([
            'torneo',
            'sede',
            'equipoLocal',
            'equipoVisitante'
        ])->get();

        return response()->json($encuentros);
    }

    /**
     * @OA\Post(
     *     path="/api/encuentros",
     *     summary="Crear un nuevo encuentro",
     *     tags={"Encuentros"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"torneo_id","sede_id","fecha","equipo_local_id","equipo_visitante_id"},
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="sede_id", type="integer", example=2),
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-10-10"),
     *             @OA\Property(property="equipo_local_id", type="integer", example=3),
     *             @OA\Property(property="equipo_visitante_id", type="integer", example=4),
     *             @OA\Property(property="goles_equipo_local", type="integer", example=2),
     *             @OA\Property(property="goles_equipo_visitante", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Encuentro creado correctamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha' => 'required|date',
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'goles_equipo_local' => 'nullable|integer|min:0',
            'goles_equipo_visitante' => 'nullable|integer|min:0',
        ]);

        $encuentro = Encuentro::create($request->all());

        return response()->json($encuentro, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/encuentros/{id}",
     *     summary="Mostrar un encuentro específico",
     *     tags={"Encuentros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del encuentro",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detalles del encuentro"),
     *     @OA\Response(response=404, description="Encuentro no encontrado")
     * )
     */
    public function show($id)
    {
        $encuentro = Encuentro::with([
            'torneo',
            'sede',
            'equipoLocal',
            'equipoVisitante'
        ])->findOrFail($id);

        return response()->json($encuentro);
    }

    /**
     * @OA\Put(
     *     path="/api/encuentros/{id}",
     *     summary="Actualizar un encuentro",
     *     tags={"Encuentros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del encuentro a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="sede_id", type="integer", example=2),
     *             @OA\Property(property="fecha", type="string", format="date", example="2024-10-11"),
     *             @OA\Property(property="equipo_local_id", type="integer", example=3),
     *             @OA\Property(property="equipo_visitante_id", type="integer", example=4),
     *             @OA\Property(property="goles_equipo_local", type="integer", example=2),
     *             @OA\Property(property="goles_equipo_visitante", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Encuentro actualizado correctamente"),
     *     @OA\Response(response=404, description="Encuentro no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $encuentro = Encuentro::findOrFail($id);

        $request->validate([
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'sede_id' => 'sometimes|required|exists:sedes,id',
            'fecha' => 'sometimes|required|date',
            'equipo_local_id' => 'sometimes|required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'sometimes|required|exists:equipos,id',
            'goles_equipo_local' => 'nullable|integer|min:0',
            'goles_equipo_visitante' => 'nullable|integer|min:0',
        ]);

        $encuentro->update($request->all());

        return response()->json($encuentro);
    }

    /**
     * @OA\Delete(
     *     path="/api/encuentros/{id}",
     *     summary="Eliminar un encuentro",
     *     tags={"Encuentros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del encuentro a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Encuentro eliminado exitosamente"),
     *     @OA\Response(response=404, description="Encuentro no encontrado")
     * )
     */
    public function destroy($id)
    {
        $encuentro = Encuentro::findOrFail($id);
        $encuentro->delete();

        return response()->json(null, 204);
    }
}
