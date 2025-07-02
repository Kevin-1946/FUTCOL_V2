<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    // Mostrar lista de equipos
    public function index()
    {
        $equipos = Equipo::with(['torneo', 'jugadores', 'capitan'])->get();
        return response()->json($equipos);
    }

    // Crear un nuevo equipo
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
    // 👇 Agrega esto al final del controlador EquipoController.php
public function registrarEquipoCompleto(Request $request)
{
    $request->validate([
        'nombre_equipo' => 'required|string|max:100|unique:equipos,nombre',
        'torneo_id' => 'required|exists:torneos,id',
        'capitan' => 'required|array',
        'capitan.nombre' => 'required|string|max:100',
        'capitan.correo' => 'required|email|unique:users,email',
        'capitan.documento' => 'required|string|max:20',
        'capitan.password' => 'required|string|min:6',
        'jugadores' => 'required|array|min:6|max:8',
        'jugadores.*.nombre' => 'required|string|max:100',
        'jugadores.*.correo' => 'required|email',
        'jugadores.*.documento' => 'required|string|max:20',
    ]);

    // 1. Crear el equipo
    $equipo = \App\Models\Equipo::create([
        'nombre' => $request->nombre_equipo,
        'torneo_id' => $request->torneo_id,
    ]);

    // 2. Crear usuario para el capitán
    $user = \App\Models\User::create([
        'name' => $request->capitan['nombre'],
        'email' => $request->capitan['correo'],
        'password' => bcrypt($request->capitan['password']),
        'equipo_id' => $equipo->id,
    ]);
    $user->roles()->attach(\App\Models\Role::where('nombre', 'capitan')->first()->id);

    // 3. Crear capitán como jugador
    $capitan = \App\Models\Jugador::create([
        'nombre' => $request->capitan['nombre'],
        'correo' => $request->capitan['correo'],
        'documento' => $request->capitan['documento'],
        'equipo_id' => $equipo->id,
        'rol' => 'capitan',
    ]);

    // 4. Actualizar el ID del capitán en el equipo
    $equipo->update(['capitan_id' => $capitan->id]);

    // 5. Crear jugadores adicionales
    foreach ($request->jugadores as $jugador) {
        \App\Models\Jugador::create([
            'nombre' => $jugador['nombre'],
            'correo' => $jugador['correo'],
            'documento' => $jugador['documento'],
            'equipo_id' => $equipo->id,
            'rol' => 'jugador',
        ]);
    }

    // 6. Crear suscripción
    \App\Models\Suscripcion::create([
        'equipo_id' => $equipo->id,
        'torneo_id' => $request->torneo_id,
        'fecha_suscripcion' => now(),
        'estado' => 'pendiente',
    ]);

    return response()->json([
        'message' => 'Equipo, capitán y jugadores registrados correctamente. Pendiente de pago.',
        'equipo_id' => $equipo->id,
    ], 201);
}

}