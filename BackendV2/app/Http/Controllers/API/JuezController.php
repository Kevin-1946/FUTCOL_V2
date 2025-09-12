<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Juez;
use Illuminate\Http\Request;

class JuezController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jueces",
     *     summary="Listar todos los jueces",
     *     tags={"Jueces"},
     *     @OA\Response(response=200, description="Lista de jueces")
     * )
     */
    public function index()
    {
        $jueces = Juez::all();
        return response()->json($jueces);
    }

    /**
     * @OA\Post(
     *     path="/api/jueces",
     *     summary="Registrar un nuevo juez",
     *     tags={"Jueces"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","numero_de_contacto","correo"},
     *             @OA\Property(property="nombre", type="string", example="Carlos Pérez"),
     *             @OA\Property(property="numero_de_contacto", type="string", example="3111234567"),
     *             @OA\Property(property="correo", type="string", example="carlos@example.com"),
     *             @OA\Property(property="sede_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Juez creado exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_de_contacto' => 'required|string|max:20',
            'correo' => 'required|email|unique:jueces,correo',
            'sede_id' => 'nullable|exists:sedes,id',
        ]);

        $juez = Juez::create($request->all());

        return response()->json($juez, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/jueces/{id}",
     *     summary="Obtener información de un juez específico",
     *     tags={"Jueces"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Información del juez"),
     *     @OA\Response(response=404, description="Juez no encontrado")
     * )
     */
    public function show($id)
    {
        $juez = Juez::findOrFail($id);
        return response()->json($juez);
    }

    /**
     * @OA\Put(
     *     path="/api/jueces/{id}",
     *     summary="Actualizar información de un juez",
     *     tags={"Jueces"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Carlos Pérez"),
     *             @OA\Property(property="numero_de_contacto", type="string", example="3111234567"),
     *             @OA\Property(property="correo", type="string", example="carlos@example.com"),
     *             @OA\Property(property="sede_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Juez actualizado exitosamente"),
     *     @OA\Response(response=404, description="Juez no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $juez = Juez::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'numero_de_contacto' => 'sometimes|required|string|max:20',
            'correo' => 'sometimes|required|email|unique:jueces,correo,' . $juez->id,
            'sede_id' => 'sometimes|required|string|max:255',
        ]);

        $juez->update($request->all());

        return response()->json($juez);
    }

    /**
     * @OA\Delete(
     *     path="/api/jueces/{id}",
     *     summary="Eliminar un juez",
     *     tags={"Jueces"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Juez eliminado exitosamente"),
     *     @OA\Response(response=404, description="Juez no encontrado")
     * )
     */
    public function destroy($id)
    {
        $juez = Juez::findOrFail($id);
        $juez->delete();

        return response()->json(null, 204);
    }
}
