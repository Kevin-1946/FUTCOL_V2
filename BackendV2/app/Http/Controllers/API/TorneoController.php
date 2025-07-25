<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use Illuminate\Http\Request;

class TorneoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/torneos",
     *     summary="Listar todos los torneos con sus relaciones",
     *     tags={"Torneos"},
     *     @OA\Response(response=200, description="Lista de torneos")
     * )
     */
    public function index()
    {
        return response()->json(
            Torneo::with(['equipos', 'sedes', 'inscripciones', 'recibosDePago', 'encuentros'])->get()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/torneos",
     *     summary="Crear un nuevo torneo",
     *     tags={"Torneos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "categoria", "fecha_inicio", "fecha_fin"},
     *             @OA\Property(property="nombre", type="string", example="Liga Clausura 2024"),
     *             @OA\Property(property="categoria", type="string", example="Sub-18"),
     *             @OA\Property(property="fecha_inicio", type="string", format="date", example="2024-09-01"),
     *             @OA\Property(property="fecha_fin", type="string", format="date", example="2024-12-01")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Torneo creado exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $request->merge([
        'modalidad' => strtolower($request->modalidad),
        'nombre' => ucfirst(strtolower($request->nombre)),
        'categoria' => ucfirst(strtolower($request->categoria))
        ]);

        $validated = $request->validate([
            'nombre' => 'required|in:Liga,Relampago,Eliminacion directa,Mixto',
            'categoria' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-0-9]+$/',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'modalidad' => 'required|in:todos contra todos,mixto,competencia rapida,uno contra uno',
            'organizador' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'precio' => 'required|numeric|min:0',
            'sedes' => 'nullable|string|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s,.-]+$/',
        ]);

        $torneo = Torneo::create($validated);
        return response()->json($torneo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/torneos/{id}",
     *     summary="Mostrar un torneo específico con relaciones",
     *     tags={"Torneos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información del torneo"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function show($id)
    {
        $torneo = Torneo::with(['equipos', 'sedes', 'inscripciones', 'recibosDePago', 'encuentros'])->findOrFail($id);
        return response()->json($torneo);
    }

    /**
     * @OA\Put(
     *     path="/api/torneos/{id}",
     *     summary="Actualizar un torneo",
     *     tags={"Torneos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="categoria", type="string"),
     *             @OA\Property(property="fecha_inicio", type="string", format="date"),
     *             @OA\Property(property="fecha_fin", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Torneo actualizado exitosamente"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function update(Request $request, $id)   
    {
        $torneo = Torneo::findOrFail($id);
        $request->merge([
        'modalidad' => strtolower($request->modalidad),
        'nombre' => ucfirst(strtolower($request->nombre)),
        'categoria' => ucfirst(strtolower($request->categoria))
        ]);

        $validated = $request->validate([
            'nombre' => 'required|in:Liga,Relampago,Eliminacion directa,Mixto',
            'categoria' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-0-9]+$/',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'modalidad' => 'required|in:todos contra todos,mixto,competencia rapida,uno contra uno',
            'organizador' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'precio' => 'required|numeric|min:0',
            'sedes' => 'nullable|string|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s,.-]+$/',
        ]);

        $torneo->update($validated);
        return response()->json($torneo);
    }

    /**
     * @OA\Delete(
     *     path="/api/torneos/{id}",
     *     summary="Eliminar un torneo",
     *     tags={"Torneos"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Torneo eliminado correctamente"),
     *     @OA\Response(response=404, description="Torneo no encontrado")
     * )
     */
    public function destroy($id)
    {
        $torneo = Torneo::findOrFail($id);
        $torneo->delete();

        return response()->json(['message' => 'Torneo eliminado correctamente.']);
    }
}