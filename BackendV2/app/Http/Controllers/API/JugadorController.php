<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class JugadorController extends Controller
{
    // =========================
    // LISTADO
    // =========================
    public function index(Request $request)
    {
        // Por defecto: devolver TODOS los jugadores con relaciones (como en Sedes/Torneos)
        $query = Jugador::with('equipo', 'equiposCapitaneados');

        // Si explícitamente piden el alcance de capitán (?scope=capitan), entonces sí filtramos
        if ($request->query('scope') === 'capitan') {
            $user = Auth::user();
            if ($user && $user->role && $user->role->nombre === 'Capitan') {
                $equipoCapitaneado = $user->equiposCapitaneados()->first();
                if (!$equipoCapitaneado) {
                    return response()->json([]); // capitán sin equipo
                }
                $query->where('equipo_id', $equipoCapitaneado->id);
            } else {
                // Si no es capitán y pidió scope=capitan, no mostramos nada
                return response()->json([]);
            }
        }

        return response()->json($query->get());
    }

    // =========================
    // CREAR
    // =========================
    public function store(Request $request)
    {
        // Validación (422 si falla)
        try {
            $validated = $request->validate([
                'nombre'            => ['required','string','max:255'],
                'n_documento'       => ['required','string','max:50','unique:jugadores,n_documento'],
                'fecha_nacimiento'  => ['required','date'],
                'email'             => ['required','email','max:255','unique:jugadores,email'],
                'password'          => ['required','string','min:6'],
                'equipo_id'         => ['nullable','integer','exists:equipos,id'],
                'genero'            => ['sometimes','nullable','string','max:30'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();

            // Si es Capitán, fuerza su equipo
            if ($user && $user->role && $user->role->nombre === 'Capitan') {
                $equipoCapitaneado = $user->equiposCapitaneados()->first();
                if (!$equipoCapitaneado) {
                    return response()->json(['error' => 'No tienes un equipo asignado'], 403);
                }
                $validated['equipo_id'] = $equipoCapitaneado->id;
            }

            // Construir el array solo con columnas existentes
            $data = [
                'nombre'            => $validated['nombre'],
                'n_documento'       => $validated['n_documento'],
                'fecha_nacimiento'  => $validated['fecha_nacimiento'],
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
            ];

            if (Schema::hasColumn('jugadores', 'equipo_id')) {
                $data['equipo_id'] = !empty($validated['equipo_id']) ? $validated['equipo_id'] : null;
            }
            if (Schema::hasColumn('jugadores', 'genero')) {
                $data['genero'] = $request->input('genero', 'No especificado');
            }
            if (Schema::hasColumn('jugadores', 'edad')) {
                $data['edad'] = Carbon::parse($validated['fecha_nacimiento'])->age;
            }
            if (Schema::hasColumn('jugadores', 'user_id')) {
                $data['user_id'] = $user?->id;
            }

            $jugador = Jugador::create($data);

            return response()->json($jugador->load('equipo'), 201);
        } catch (\Throwable $e) {
            \Log::error('Error creando jugador', [
                'msg' => $e->getMessage(),
                'file'=> $e->getFile(),
                'line'=> $e->getLine(),
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => 'Error interno al crear jugador'], 500);
        }
    }

    // =========================
    // MOSTRAR
    // =========================
    public function show($id)
    {
        $user = Auth::user();
        $jugador = Jugador::with('equipo', 'equiposCapitaneados')->findOrFail($id);

        if ($user && $user->role && $user->role->nombre === 'Capitan') {
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }

        return response()->json($jugador);
    }

    // =========================
    // ACTUALIZAR
    // =========================
    public function update(Request $request, $id)
    {
        $jugador = Jugador::findOrFail($id);
        $user = Auth::user();

        if ($user && $user->role && $user->role->nombre === 'Capitan') {
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            $request->offsetUnset('equipo_id'); // capitán no puede cambiar equipo
        }

        // Validación (422 si falla)
        try {
            $validated = $request->validate([
                'nombre'            => ['sometimes','required','string','max:255'],
                'n_documento'       => ['sometimes','required','string','max:50', Rule::unique('jugadores','n_documento')->ignore($jugador->id)],
                'fecha_nacimiento'  => ['sometimes','required','date'],
                'email'             => ['sometimes','required','email','max:255', Rule::unique('jugadores','email')->ignore($jugador->id)],
                'password'          => ['sometimes','nullable','string','min:6'],
                'equipo_id'         => ['sometimes','nullable','integer','exists:equipos,id'],
                'genero'            => ['sometimes','nullable','string','max:30'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors'  => $e->errors(),
            ], 422);
        }

        try {
            $data = [];

            foreach (['nombre','n_documento','fecha_nacimiento','email'] as $f) {
                if (array_key_exists($f, $validated)) {
                    $data[$f] = $validated[$f];
                }
            }

            if (array_key_exists('password', $validated)) {
                if ($validated['password'] !== null && $validated['password'] !== '') {
                    $data['password'] = Hash::make($validated['password']);
                }
            }

            if (Schema::hasColumn('jugadores','equipo_id') && array_key_exists('equipo_id', $validated)) {
                $data['equipo_id'] = ($validated['equipo_id'] === '' ? null : $validated['equipo_id']);
            }

            if (Schema::hasColumn('jugadores','genero') && array_key_exists('genero', $validated)) {
                $data['genero'] = $validated['genero'] ?? 'No especificado';
            }

            if (Schema::hasColumn('jugadores','edad') && array_key_exists('fecha_nacimiento', $validated)) {
                $data['edad'] = Carbon::parse($validated['fecha_nacimiento'])->age;
            }

            if (Schema::hasColumn('jugadores','user_id') && $jugador->user_id === null && $user?->id) {
                $data['user_id'] = $user->id;
            }

            $jugador->update($data);

            return response()->json($jugador->load('equipo'));
        } catch (\Throwable $e) {
            \Log::error('Error actualizando jugador', [
                'msg' => $e->getMessage(),
                'file'=> $e->getFile(),
                'line'=> $e->getLine(),
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => 'Error interno al actualizar jugador'], 500);
        }
    }

    // =========================
    // ELIMINAR
    // =========================
    public function destroy($id)
    {
        $user = Auth::user();
        $jugador = Jugador::findOrFail($id);

        if ($user && $user->role && $user->role->nombre === 'Capitan') {
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }

        $jugador->delete();
        return response()->json(null, 204);
    }
}
