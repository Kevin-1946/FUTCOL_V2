<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD

class JugadorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jugadores",
     *     summary="Listar jugadores (filtrado por rol)",
     *     tags={"Jugadores"},
     *     @OA\Response(response=200, description="Lista de jugadores")
     * )
     */
    public function index()
    {
        $user = Auth::user();
        
        // Si es Administrador, ve todos los jugadores
        if ($user->role->nombre === 'Administrador') {
            $jugadores = Jugador::with('equipo', 'equiposCapitaneados')->get();
        } 
        // Si es Capitán, solo ve los jugadores de su equipo
        elseif ($user->role->nombre === 'Capitan') {
            // Obtener el equipo que capitanea este usuario
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            
            if ($equipoCapitaneado) {
                $jugadores = Jugador::with('equipo', 'equiposCapitaneados')
                    ->where('equipo_id', $equipoCapitaneado->id)
                    ->get();
            } else {
                // Si el capitán no tiene equipo asignado
                $jugadores = collect();
            }
        }
        // Si es Participante, no ve jugadores (o solo se ve a sí mismo)
        else {
            $jugadores = collect();
        }
        
        return response()->json($jugadores);
    }

    /**
     * @OA\Post(
     *     path="/api/jugadores",
     *     summary="Crear un nuevo jugador",
     *     tags={"Jugadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "n_documento", "fecha_nacimiento", "email", "password"},
     *             @OA\Property(property="nombre", type="string", example="Juan Pérez"),
     *             @OA\Property(property="n_documento", type="string", example="1234567890"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date", example="2000-01-01"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", example="secret"),
     *             @OA\Property(property="equipo_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Jugador creado exitosamente"),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'n_documento' => 'required|string|unique:jugadores,n_documento',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|email|unique:jugadores,email',
            'password' => 'required|string|min:6',
            'equipo_id' => 'nullable|exists:equipos,id',
        ]);

        // Si es Capitán, solo puede crear jugadores para su equipo
        if ($user->role->nombre === 'Capitan') {
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado) {
                return response()->json(['error' => 'No tienes un equipo asignado'], 403);
            }
            $request->merge(['equipo_id' => $equipoCapitaneado->id]);
        }

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

    /**
     * @OA\Get(
     *     path="/api/jugadores/{id}",
     *     summary="Mostrar un jugador específico",
     *     tags={"Jugadores"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Información del jugador"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
=======
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
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function show($id)
    {
        $user = Auth::user();
        $jugador = Jugador::with('equipo', 'equiposCapitaneados')->findOrFail($id);
<<<<<<< HEAD
        
        // Si es Capitán, solo puede ver jugadores de su equipo
        if ($user->role->nombre === 'Capitan') {
=======

        if ($user && $user->role && $user->role->nombre === 'Capitan') {
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }
<<<<<<< HEAD
        
        return response()->json($jugador);
    }

    /**
     * @OA\Put(
     *     path="/api/jugadores/{id}",
     *     summary="Actualizar un jugador",
     *     tags={"Jugadores"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="nombre", type="string"),
     *             @OA\Property(property="n_documento", type="string"),
     *             @OA\Property(property="fecha_nacimiento", type="string", format="date"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="equipo_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Jugador actualizado exitosamente"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $jugador = Jugador::findOrFail($id);

        // Si es Capitán, solo puede actualizar jugadores de su equipo
        if ($user->role->nombre === 'Capitan') {
=======

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
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
<<<<<<< HEAD
            // Los capitanes no pueden cambiar el equipo del jugador
            $request->offsetUnset('equipo_id');
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'n_documento' => 'sometimes|required|string|unique:jugadores,n_documento,' . $jugador->id,
            'fecha_nacimiento' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:jugadores,email,' . $jugador->id,
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
        
        // Solo los administradores pueden cambiar el equipo
        if ($user->role->nombre === 'Administrador') {
            $jugador->equipo_id = $request->equipo_id ?? $jugador->equipo_id;
        }

        $jugador->save();

        return response()->json($jugador);
    }

    /**
     * @OA\Delete(
     *     path="/api/jugadores/{id}",
     *     summary="Eliminar un jugador",
     *     tags={"Jugadores"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Jugador eliminado exitosamente"),
     *     @OA\Response(response=404, description="Jugador no encontrado")
     * )
     */
=======
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
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function destroy($id)
    {
        $user = Auth::user();
        $jugador = Jugador::findOrFail($id);
<<<<<<< HEAD
        
        // Si es Capitán, solo puede eliminar jugadores de su equipo
        if ($user->role->nombre === 'Capitan') {
=======

        if ($user && $user->role && $user->role->nombre === 'Capitan') {
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
            $equipoCapitaneado = $user->equiposCapitaneados()->first();
            if (!$equipoCapitaneado || $jugador->equipo_id !== $equipoCapitaneado->id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
        }
<<<<<<< HEAD
        
        $jugador->delete();

        return response()->json(null, 204);
    } 
}
=======

        $jugador->delete();
        return response()->json(null, 204);
    }
}
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
