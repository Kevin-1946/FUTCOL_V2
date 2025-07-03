<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JugadorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jugadores",
     *     summary="Listar todos los jugadores",
     *     tags={"Jugadores"},
     *     @OA\Response(response=200, description="Lista de jugadores")
     * )
     */
    public function index()
    {
        $jugadores = Jugador::with('equipo', 'equiposCapitaneados')->get();
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
    public function show($id)
    {
        $jugador = Jugador::with('equipo', 'equiposCapitaneados')->findOrFail($id);
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
    public function destroy($id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->delete();

        return response()->json(null, 204);
    } 
}

