<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sedes",
     *     summary="Listar todas las sedes",
     *     tags={"Sedes"},
     *     @OA\Response(
     *         response=200,
<<<<<<< HEAD
     *         description="Lista de sedes con su torneo asociado"
=======
     *         description="Lista de sedes con su torneo asociado (si existe)"
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Sede::with('torneo')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/sedes",
     *     summary="Crear una nueva sede",
     *     tags={"Sedes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
<<<<<<< HEAD
     *             required={"nombre", "direccion", "torneo_id"},
     *             @OA\Property(property="nombre", type="string", example="Cancha el Dorado"),
     *             @OA\Property(property="direccion", type="string", example="Carrera 12 #45-67"),
     *             @OA\Property(property="torneo_id", type="integer", example=1)
=======
     *             required={"nombre", "direccion"},
     *             @OA\Property(property="nombre", type="string", example="Cancha El Dorado"),
     *             @OA\Property(property="direccion", type="string", example="Carrera 12 #45-67"),
     *             @OA\Property(property="torneo_id", type="integer", nullable=true, example=null)
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
     *         )
     *     ),
     *     @OA\Response(response=201, description="Sede creada exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'torneo_id' => 'required|exists:torneos,id',
        ]);

        $sede = Sede::create($validated);
        return response()->json($sede, 201);
=======
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'torneo_id' => 'nullable|exists:torneos,id', // ✅ opcional
        ]);

        $sede = Sede::create($validated);
        return response()->json($sede->load('torneo'), 201);
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    }

    /**
     * @OA\Get(
     *     path="/api/sedes/{id}",
     *     summary="Mostrar una sede específica",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la sede",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Información de la sede"),
     *     @OA\Response(response=404, description="Sede no encontrada")
     * )
     */
    public function show($id)
    {
        $sede = Sede::with(['torneo', 'encuentros'])->findOrFail($id);
        return response()->json($sede);
    }

    /**
     * @OA\Put(
     *     path="/api/sedes/{id}",
     *     summary="Actualizar una sede",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la sede",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Nuevo nombre de sede"),
     *             @OA\Property(property="direccion", type="string", example="Nueva dirección"),
<<<<<<< HEAD
     *             @OA\Property(property="torneo_id", type="integer", example=2)
=======
     *             @OA\Property(property="torneo_id", type="integer", nullable=true, example=null)
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
     *         )
     *     ),
     *     @OA\Response(response=200, description="Sede actualizada correctamente"),
     *     @OA\Response(response=404, description="Sede no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);

        $validated = $request->validate([
<<<<<<< HEAD
            'nombre' => 'sometimes|required|string|max:255',
            'direccion' => 'sometimes|required|string|max:255',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
        ]);

        $sede->update($validated);
        return response()->json($sede);
=======
            'nombre'    => 'sometimes|required|string|max:255',
            'direccion' => 'sometimes|required|string|max:255',
            'torneo_id' => 'sometimes|nullable|exists:torneos,id', // ✅ opcional si viene
        ]);

        $sede->update($validated);
        return response()->json($sede->load('torneo'));
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    }

    /**
     * @OA\Delete(
     *     path="/api/sedes/{id}",
     *     summary="Eliminar una sede",
     *     tags={"Sedes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la sede",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Sede eliminada con éxito"),
     *     @OA\Response(response=404, description="Sede no encontrada")
     * )
     */
    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);
        $sede->delete();

        return response()->json(['message' => 'Sede eliminada con éxito.']);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
