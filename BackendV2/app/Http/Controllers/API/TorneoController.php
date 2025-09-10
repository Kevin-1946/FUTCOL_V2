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
        try {
            // Versión con relaciones que funciona
            $torneos = Torneo::with(['equipos', 'sedes', 'inscripciones', 'recibosDePago', 'encuentros'])->get();
            return response()->json($torneos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener torneos',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    // Nuevo método para probar con relaciones
    public function indexWithRelations()
    {
        try {
            $torneos = Torneo::with(['equipos', 'sedes', 'inscripciones', 'recibosDePago', 'encuentros'])->get();
            return response()->json($torneos);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener torneos con relaciones',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $torneo = Torneo::findOrFail($id);
            return response()->json($torneo);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Torneo no encontrado',
                'message' => $e->getMessage()
            ], 404);
        }
    }

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