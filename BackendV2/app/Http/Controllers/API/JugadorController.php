<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JugadorController extends Controller
{
    // Mostrar lista de jugadores
    public function index()
    {
        $jugadores = Jugador::with('equipo', 'equiposCapitaneados')->get();
        return response()->json($jugadores);
    }

    // Crear un nuevo jugador
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'n_documento' => 'required|string|unique:jugadors,n_documento',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|email|unique:jugadors,email',
            'password' => 'required|string|min:6',
            'equipo_id' => 'nullable|exists:equipos,id',
        ]);

        $jugador = Jugador::create([
            'nombre' => $request->nombre,
            'n_documento' => $request->n_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'equipo_id' => $request->equipo_id,
        ]);

        return response()->json($jugador, 201);
    }

    // Mostrar un jugador específico
    public function show($id)
    {
        $jugador = Jugador::with('equipo', 'equiposCapitaneados')->findOrFail($id);
        return response()->json($jugador);
    }

    // Actualizar un jugador
    public function update(Request $request, $id)
    {
        $jugador = Jugador::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'n_documento' => 'sometimes|required|string|unique:jugadors,n_documento,' . $jugador->id,
            'fecha_nacimiento' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:jugadors,email,' . $jugador->id,
            'password' => 'nullable|string|min:6',
            'equipo_id' => 'nullable|exists:equipos,id',
        ]);

        $jugador->nombre = $request->nombre ?? $jugador->nombre;
        $jugador->n_documento = $request->n_documento ?? $jugador->n_documento;
        $jugador->fecha_nacimiento = $request->fecha_nacimiento ?? $jugador->fecha_nacimiento;
        $jugador->email = $request->email ?? $jugador->email;
        if ($request->filled('password')) {
            $jugador->password = Hash::make($request->password);
        }
        $jugador->equipo_id = $request->equipo_id ?? $jugador->equipo_id;

        $jugador->save();

        return response()->json($jugador);
    }

    // Eliminar un jugador
    public function destroy($id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->delete();

        return response()->json(null, 204);
    }

    // ===== MÉTODOS ADICIONALES =====

    // Obtener perfil del jugador autenticado
    public function miPerfil(Request $request)
    {
        $user = $request->user();
        $jugador = Jugador::with(['equipo.torneo', 'equiposCapitaneados.torneo'])
                          ->findOrFail($user->id);

        return response()->json($jugador);
    }

    // Obtener estadísticas del jugador autenticado
    public function misEstadisticas(Request $request)
    {
        $user = $request->user();
        $jugador = Jugador::findOrFail($user->id);

        // Aquí puedes agregar la lógica para calcular estadísticas
        // Esto dependerá de tu modelo de datos para partidos, goles, etc.
        $estadisticas = [
            'partidos_jugados' => 0, // Implementar según tu lógica
            'goles' => 0,            // Implementar según tu lógica
            'asistencias' => 0,      // Implementar según tu lógica
            'tarjetas_amarillas' => 0, // Implementar según tu lógica
            'tarjetas_rojas' => 0,   // Implementar según tu lógica
            'mvp' => 0,              // Implementar según tu lógica
        ];

        return response()->json([
            'jugador' => $jugador->load('equipo'),
            'estadisticas' => $estadisticas
        ]);
    }

    // Actualizar perfil del jugador autenticado
    public function actualizarMiPerfil(Request $request)
    {
        $user = $request->user();
        $jugador = Jugador::findOrFail($user->id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'n_documento' => 'sometimes|required|string|unique:jugadors,n_documento,' . $jugador->id,
            'fecha_nacimiento' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:jugadors,email,' . $jugador->id,
            'password' => 'nullable|string|min:6',
        ]);

        $jugador->nombre = $request->nombre ?? $jugador->nombre;
        $jugador->n_documento = $request->n_documento ?? $jugador->n_documento;
        $jugador->fecha_nacimiento = $request->fecha_nacimiento ?? $jugador->fecha_nacimiento;
        $jugador->email = $request->email ?? $jugador->email;
        
        if ($request->filled('password')) {
            $jugador->password = Hash::make($request->password);
        }

        $jugador->save();

        return response()->json([
            'message' => 'Perfil actualizado exitosamente',
            'jugador' => $jugador
        ]);
    }

    // Salir del equipo actual
    public function salirDelEquipo(Request $request)
    {
        $user = $request->user();
        $jugador = Jugador::findOrFail($user->id);

        if (!$jugador->equipo_id) {
            return response()->json([
                'message' => 'No perteneces a ningún equipo'
            ], 400);
        }

        // Verificar si es capitán
        $equipo = $jugador->equipo;
        if ($equipo && $equipo->capitan_id === $jugador->id) {
            return response()->json([
                'message' => 'No puedes salir del equipo siendo capitán. Transfiere la capitanía primero.'
            ], 400);
        }

        $jugador->update(['equipo_id' => null]);

        return response()->json([
            'message' => 'Has salido del equipo exitosamente'
        ]);
    }

    // Obtener jugadores sin equipo
    public function jugadoresSinEquipo()
    {
        $jugadores = Jugador::whereNull('equipo_id')->get();
        return response()->json($jugadores);
    }

    // Buscar jugadores por nombre o documento
    public function buscarJugadores(Request $request)
    {
        $request->validate([
            'buscar' => 'required|string|min:3'
        ]);

        $termino = $request->buscar;
        
        $jugadores = Jugador::with('equipo')
                           ->where('nombre', 'LIKE', "%{$termino}%")
                           ->orWhere('n_documento', 'LIKE', "%{$termino}%")
                           ->limit(10)
                           ->get();

        return response()->json($jugadores);
    }

    // Obtener historial de equipos del jugador
    public function historialEquipos(Request $request)
    {
        $user = $request->user();
        
        // Este método requeriría una tabla de historial para ser completamente funcional
        // Por ahora retornamos el equipo actual y los equipos capitaneados
        $jugador = Jugador::with(['equipo', 'equiposCapitaneados'])
                          ->findOrFail($user->id);

        $historial = [
            'equipo_actual' => $jugador->equipo,
            'equipos_capitaneados' => $jugador->equiposCapitaneados
        ];

        return response()->json($historial);
    }
}