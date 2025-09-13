<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Torneo;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    // Mantengo un index alterno si lo necesitas
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
            $torneo = Torneo::with(['equipos', 'sedes', 'inscripciones', 'recibosDePago', 'encuentros'])->findOrFail($id);
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
        // Normalización simple de campos de texto
        $request->merge([
            'modalidad' => strtolower($request->modalidad),
            'nombre'    => ucfirst(strtolower($request->nombre)),
            'categoria' => ucfirst(strtolower($request->categoria)),
        ]);

        // Validación principal (SIN meter 'sedes' como string)
        $validated = $request->validate([
            'nombre'       => ['required', Rule::in(['Liga','Relampago','Eliminacion directa','Mixto'])],
            'categoria'    => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-0-9]+$/',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'modalidad'    => ['required', Rule::in(['todos contra todos','mixto','competencia rapida','uno contra uno'])],
            'organizador'  => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'precio'       => 'required|numeric|min:0',

            // 👉 Soportamos ambas formas (una sede o varias):
            'sede_id'   => 'nullable|integer|exists:sedes,id',
            'sedes'     => 'sometimes|array',
            'sedes.*'   => 'integer|exists:sedes,id',
        ]);

        // Creamos el torneo (solo con los campos de la tabla 'torneos')
        $torneo = Torneo::create([
            'nombre'       => $validated['nombre'],
            'categoria'    => $validated['categoria'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin'    => $validated['fecha_fin'],
            'modalidad'    => $validated['modalidad'],
            'organizador'  => $validated['organizador'],
            'precio'       => $validated['precio'],
        ]);

        // Enlazar sedes -> torneo vía columna 'torneo_id' de Sede
        if ($request->filled('sede_id')) {
            Sede::whereKey($request->integer('sede_id'))->update(['torneo_id' => $torneo->id]);
        }

        if ($request->filled('sedes')) {
            $ids = collect($request->input('sedes'))->map(fn($id) => (int) $id)->all();
            Sede::whereIn('id', $ids)->update(['torneo_id' => $torneo->id]);
        }

        // Devolvemos con relaciones para que el frontend vea las sedes asignadas
        return response()->json($torneo->load('sedes'), 201);
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
            'nombre'    => ucfirst(strtolower($request->nombre)),
            'categoria' => ucfirst(strtolower($request->categoria)),
        ]);

        $validated = $request->validate([
            'nombre'       => ['required', Rule::in(['Liga','Relampago','Eliminacion directa','Mixto'])],
            'categoria'    => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-0-9]+$/',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'modalidad'    => ['required', Rule::in(['todos contra todos','mixto','competencia rapida','uno contra uno'])],
            'organizador'  => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'precio'       => 'required|numeric|min:0',

            'sede_id'   => 'sometimes|nullable|integer|exists:sedes,id',
            'sedes'     => 'sometimes|array',
            'sedes.*'   => 'integer|exists:sedes,id',
        ]);

        $torneo->update([
            'nombre'       => $validated['nombre'],
            'categoria'    => $validated['categoria'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin'    => $validated['fecha_fin'],
            'modalidad'    => $validated['modalidad'],
            'organizador'  => $validated['organizador'],
            'precio'       => $validated['precio'],
        ]);

        // Si se envían sedes en update, sincronizamos:
        if ($request->hasAny(['sede_id', 'sedes'])) {
            // 1) Quitamos sedes anteriores que ya no estén (dejarlas en null)
            $nuevosIds = collect((array) $request->input('sedes', []))->map(fn($i) => (int) $i)->all();
            if ($request->filled('sede_id')) {
                $nuevosIds[] = (int) $request->input('sede_id');
            }
            $nuevosIds = array_values(array_unique($nuevosIds));

            // Desasociar las que ya no están
            Sede::where('torneo_id', $torneo->id)
                ->whereNotIn('id', $nuevosIds ?: [0])
                ->update(['torneo_id' => null]);

            // Asociar las nuevas
            if (!empty($nuevosIds)) {
                Sede::whereIn('id', $nuevosIds)->update(['torneo_id' => $torneo->id]);
            }
        }

        return response()->json($torneo->load('sedes'));
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

        // Dejamos libres las sedes asociadas
        Sede::where('torneo_id', $torneo->id)->update(['torneo_id' => null]);

        $torneo->delete();

        return response()->json(['message' => 'Torneo eliminado correctamente.']);
    }
}
