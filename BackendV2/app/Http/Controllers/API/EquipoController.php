<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $equipo = Equipo::create($request->only(['nombre', 'torneo_id', 'capitan_id']));    

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
        $equipo->update($request->only(['nombre', 'torneo_id', 'capitan_id']));

        return response()->json([
        'message' => 'Equipo actualizado correctamente',
        'equipo' => $equipo
        ]);
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
// Registrar equipo completo - MÉTODO CORREGIDO
public function registrarEquipoCompleto(Request $request)
{
    // Validación corregida
    $request->validate([
        'nombre_equipo' => 'required|string|max:100|unique:equipos,nombre',
        'torneo_id' => 'required|exists:torneos,id',
        'forma_pago' => 'required|string|max:100',
        'estado_pago' => 'required|string|max:100',
        'total_pagado' => 'required|numeric|min:0',
        
        // Validación del capitán
        'capitan' => 'required|array',
        'capitan.nombre' => 'required|string|max:100',
        'capitan.email' => 'required|email|unique:users,email',
        'capitan.documento' => 'required|string|max:20|unique:jugadores,n_documento',
        'capitan.password' => 'required|string|min:6',
        'capitan.genero' => 'required|string',
        'capitan.edad' => 'required|integer|min:16|max:50',
        'capitan.fecha_nacimiento' => 'required|date',
        
        // Validación de jugadores
        'jugadores' => 'required|array|min:6|max:8',
        'jugadores.*.nombre' => 'required|string|max:100',
        'jugadores.*.email' => 'required|email',
        'jugadores.*.documento' => 'required|string|max:20',
        'jugadores.*.genero' => 'required|string',
        'jugadores.*.edad' => 'required|integer|min:16|max:50',
        'jugadores.*.fecha_nacimiento' => 'required|date',
    ]);

    // Verificar duplicidad de correos y documentos DENTRO del equipo
    $correos = [$request->capitan['email']]; // ✅ Corregido: era 'correo'
    $documentos = [$request->capitan['documento']];

    foreach ($request->jugadores as $jugador) {
        // Verificar duplicados dentro del mismo equipo
        if (in_array($jugador['email'], $correos)) {
            return response()->json(['message' => "Correo duplicado en el equipo: {$jugador['email']}"], 422);
        }
        if (in_array($jugador['documento'], $documentos)) {
            return response()->json(['message' => "Documento duplicado en el equipo: {$jugador['documento']}"], 422);
        }
        $correos[] = $jugador['email'];
        $documentos[] = $jugador['documento'];
    }

    // Verificar que los correos no existan en la base de datos
    foreach ($correos as $correo) {
        if (\App\Models\User::where('email', $correo)->exists()) {
            return response()->json(['message' => "El correo {$correo} ya está registrado en el sistema."], 422);
        }
    }

    // Verificar que los documentos no existan en la base de datos
    foreach ($documentos as $doc) {
        if (\App\Models\Jugador::where('n_documento', $doc)->exists()) {
            return response()->json(['message' => "El documento {$doc} ya está registrado en el sistema."], 422);
        }
    }

    // ✅ USAR TRANSACCIÓN PARA GARANTIZAR CONSISTENCIA
    \DB::beginTransaction();
    
    try {
        // 1. Crear el equipo
        $equipo = \App\Models\Equipo::create([
            'nombre' => $request->nombre_equipo,
            'torneo_id' => $request->torneo_id,
        ]);

        // 2. Obtener el rol de capitán
        $roleCapitan = \App\Models\Role::where('nombre', 'capitan')->first();
        $roleJugador = \App\Models\Role::where('nombre', 'participante')->first();

        // 3. Crear usuario del capitán
        $userCapitan = \App\Models\User::create([
            'name' => $request->capitan['nombre'],
            'email' => $request->capitan['email'], // ✅ Corregido: era 'correo'
            'password' => bcrypt($request->capitan['password']),
            'equipo_id' => $equipo->id,
            'role_id' => $roleCapitan->id,
        ]);

        // 4. Crear jugador capitán
        $capitan = \App\Models\Jugador::create([
            'nombre' => $request->capitan['nombre'],
            'email' => $request->capitan['email'], // ✅ Corregido: era 'correo'
            'n_documento' => $request->capitan['documento'],
            'equipo_id' => $equipo->id,
            'user_id' => $userCapitan->id,
            'genero' => $request->capitan['genero'],
            'edad' => $request->capitan['edad'],
            'fecha_nacimiento' => $request->capitan['fecha_nacimiento'],
            'password' => bcrypt($request->capitan['password']), // Agregar si es necesario
        ]);

        // 5. Actualizar el equipo con el ID del capitán
        $equipo->update(['capitan_id' => $capitan->id]);

        // 6. Crear los jugadores del equipo
        foreach ($request->jugadores as $jugadorData) {
            // Crear usuario para cada jugador
            $userJugador = \App\Models\User::create([
                'name' => $jugadorData['nombre'],
                'email' => $jugadorData['email'], // ✅ Corregido: era 'correo'
                'password' => bcrypt($request->capitan['password']), // Misma contraseña del equipo
                'equipo_id' => $equipo->id,
                'role_id' => $roleJugador->id,
            ]);

            // Crear jugador
            \App\Models\Jugador::create([
                'nombre' => $jugadorData['nombre'],
                'email' => $jugadorData['email'], // ✅ Corregido: era 'correo'
                'n_documento' => $jugadorData['documento'],
                'equipo_id' => $equipo->id,
                'user_id' => $userJugador->id,
                'genero' => $jugadorData['genero'],
                'edad' => $jugadorData['edad'],
                'fecha_nacimiento' => $jugadorData['fecha_nacimiento'],
                'password' => bcrypt($request->capitan['password']), // Misma contraseña del equipo
            ]);
        }

        // 7. Crear la inscripción
        \App\Models\Inscripcion::create([
            'equipo_id' => $equipo->id,
            'torneo_id' => $request->torneo_id,
            'fecha_de_inscripcion' => now(),
            'forma_pago' => $request->forma_pago,
            'estado_pago' => $request->estado_pago,
            'correo_confirmado' => false,
            'total_pagado' => $request->total_pagado,
        ]);

        // ✅ CONFIRMAR TRANSACCIÓN
        \DB::commit();

        return response()->json([
            'message' => 'Equipo registrado exitosamente',
            'equipo_id' => $equipo->id,
            'equipo' => $equipo->load(['jugadores', 'capitan', 'torneo', 'inscripcion']),
        ], 201);

    } catch (\Exception $e) {
        // ✅ ROLLBACK EN CASO DE ERROR
        \DB::rollBack();
        
        return response()->json([
            'message' => 'Error al registrar el equipo',
            'error' => $e->getMessage()
        ], 500);
        }
    }
}