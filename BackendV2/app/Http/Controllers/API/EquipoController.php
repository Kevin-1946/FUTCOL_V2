<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Equipos",
 *     description="Operaciones relacionadas con los equipos"
 * )
 */
class EquipoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/equipos",
     *     summary="Mostrar lista de equipos",
     *     tags={"Equipos"},
     *     @OA\Response(response=200, description="Lista de equipos")
     * )
     */
    public function index()
    {
        $equipos = Equipo::with(['torneo', 'jugadores', 'capitan'])->get();
        return response()->json($equipos);
    }

    /**
     * @OA\Post(
     *     path="/api/equipos",
     *     summary="Crear un nuevo equipo",
     *     tags={"Equipos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre","torneo_id"},
     *             @OA\Property(property="nombre", type="string", example="Los Halcones"),
     *             @OA\Property(property="torneo_id", type="integer", example=1),
     *             @OA\Property(property="capitan_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Equipo creado exitosamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'torneo_id' => 'required|exists:torneos,id',
            'capitan_id' => 'nullable|exists:jugadors,id',
        ]);

        $equipo = Equipo::create($request->all());

        return response()->json($equipo, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/equipos/{id}",
     *     summary="Mostrar un equipo específico",
     *     tags={"Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del equipo"
     *     ),
     *     @OA\Response(response=200, description="Detalles del equipo"),
     *     @OA\Response(response=404, description="Equipo no encontrado")
     * )
     */
    public function show($id)
    {
        $equipo = Equipo::with(['torneo', 'jugadores', 'capitan'])->findOrFail($id);
        return response()->json($equipo);
    }

    /**
     * @OA\Put(
     *     path="/api/equipos/{id}",
     *     summary="Actualizar un equipo",
     *     tags={"Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del equipo"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string", example="Nuevo Nombre"),
     *             @OA\Property(property="torneo_id", type="integer", example=2),
     *             @OA\Property(property="capitan_id", type="integer", example=6)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Equipo actualizado correctamente"),
     *     @OA\Response(response=404, description="Equipo no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'capitan_id' => 'nullable|exists:jugadors,id',
        ]);

        $equipo = Equipo::findOrFail($id);
        $equipo->update($request->all());

        return response()->json($equipo);
    }

    /**
     * @OA\Delete(
     *     path="/api/equipos/{id}",
     *     summary="Eliminar un equipo",
     *     tags={"Equipos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del equipo"
     *     ),
     *     @OA\Response(response=204, description="Equipo eliminado exitosamente"),
     *     @OA\Response(response=404, description="Equipo no encontrado")
     * )
     */
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return response()->json(null, 204);
    }
}

    // Mostrar un equipo específico
    public function show($id)
    {
        $equipo = Equipo::with(['torneo', 'jugadores', 'capitan'])->findOrFail($id);
        return response()->json($equipo);
    }

    // Actualizar un equipo
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'capitan_id' => 'nullable|exists:jugadors,id',
        ]);

        $equipo = Equipo::findOrFail($id);
        $equipo->update($request->all());

        return response()->json($equipo);
    }

    // Eliminar un equipo
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return response()->json(null, 204);
    }

    // ===== MÉTODOS ADICIONALES =====

    // Obtener el equipo del usuario autenticado como capitán
    public function miEquipo(Request $request)
    {
        $user = $request->user();
        $equipo = Equipo::with(['torneo', 'jugadores'])
                        ->where('capitan_id', $user->id)
                        ->first();
        
        if (!$equipo) {
            return response()->json([
                'message' => 'No tienes un equipo asignado como capitán'
            ], 404);
        }

        return response()->json($equipo);
    }

    // Agregar jugador al equipo
    public function agregarJugador(Request $request, $equipoId)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadors,id'
        ]);

        $equipo = Equipo::findOrFail($equipoId);
        
        // Verificar que el usuario sea el capitán del equipo
        if ($equipo->capitan_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Solo el capitán puede agregar jugadores al equipo'
            ], 403);
        }

        // Verificar que el jugador no esté ya en un equipo
        $jugador = \App\Models\Jugador::findOrFail($request->jugador_id);
        if ($jugador->equipo_id) {
            return response()->json([
                'message' => 'El jugador ya pertenece a un equipo'
            ], 400);
        }

        $jugador->update(['equipo_id' => $equipo->id]);

        return response()->json([
            'message' => 'Jugador agregado al equipo exitosamente',
            'equipo' => $equipo->load('jugadores')
        ]);
    }

    // Remover jugador del equipo
    public function removerJugador(Request $request, $equipoId, $jugadorId)
    {
        $equipo = Equipo::findOrFail($equipoId);
        
        // Verificar que el usuario sea el capitán del equipo
        if ($equipo->capitan_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Solo el capitán puede remover jugadores del equipo'
            ], 403);
        }

        $jugador = \App\Models\Jugador::findOrFail($jugadorId);
        
        // Verificar que el jugador pertenezca al equipo
        if ($jugador->equipo_id !== $equipo->id) {
            return response()->json([
                'message' => 'El jugador no pertenece a este equipo'
            ], 400);
        }

        // No permitir remover al capitán
        if ($jugador->id === $equipo->capitan_id) {
            return response()->json([
                'message' => 'No se puede remover al capitán del equipo'
            ], 400);
        }

        $jugador->update(['equipo_id' => null]);

        return response()->json([
            'message' => 'Jugador removido del equipo exitosamente',
            'equipo' => $equipo->load('jugadores')
        ]);
    }

    // Obtener equipos por torneo
    public function equiposPorTorneo($torneoId)
    {
        $equipos = Equipo::with(['jugadores', 'capitan'])
                         ->where('torneo_id', $torneoId)
                         ->get();

        return response()->json($equipos);
    }

    // Cambiar capitán del equipo
    public function cambiarCapitan(Request $request, $equipoId)
    {
        $request->validate([
            'nuevo_capitan_id' => 'required|exists:jugadors,id'
        ]);

        $equipo = Equipo::findOrFail($equipoId);
        
        // Verificar que el usuario sea el capitán actual
        if ($equipo->capitan_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Solo el capitán actual puede transferir la capitanía'
            ], 403);
        }

        $nuevoCapitan = \App\Models\Jugador::findOrFail($request->nuevo_capitan_id);
        
        // Verificar que el nuevo capitán pertenezca al equipo
        if ($nuevoCapitan->equipo_id !== $equipo->id) {
            return response()->json([
                'message' => 'El nuevo capitán debe ser miembro del equipo'
            ], 400);
        }

        $equipo->update(['capitan_id' => $request->nuevo_capitan_id]);

        return response()->json([
            'message' => 'Capitán cambiado exitosamente',
            'equipo' => $equipo->load(['jugadores', 'capitan'])
        ]);
    }
}