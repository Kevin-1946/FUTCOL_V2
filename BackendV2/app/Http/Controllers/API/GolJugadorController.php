<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GolJugador;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Goles de Jugadores",
 *     description="Operaciones para registrar y gestionar goles anotados por jugadores"
 * )
 */
class GolJugadorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/goles-jugadores",
     *     summary="Listar todos los registros de goles",
     *     tags={"Goles de Jugadores"},
     *     @OA\Response(response=200, description="Lista de goles registrados")
     * )
     */
    public function index()
    {
        // 👈 Eager load del equipo del jugador
        $goles = GolJugador::with(['jugador.equipo', 'encuentro'])->get();
        return response()->json($goles);
    }

    /**
     * @OA\Post(
     *     path="/api/goles-jugadores",
     *     summary="Registrar un nuevo gol de jugador",
     *     tags={"Goles de Jugadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"jugador_id", "encuentro_id", "cantidad"},
     *             @OA\Property(property="jugador_id", type="integer", example=1),
     *             @OA\Property(property="encuentro_id", type="integer", example=2),
     *             @OA\Property(property="cantidad", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Gol registrado correctamente"),
     *     @OA\Response(response=422, description="Errores de validación")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jugador_id'   => 'required|exists:jugadores,id',
            'encuentro_id' => 'required|exists:encuentros,id',
            'cantidad'     => 'required|integer|min:1',
        ]);

        $gol = GolJugador::create($validated);

        // 👈 Devolvemos el recurso con relaciones
        $gol->load(['jugador.equipo', 'encuentro']);

        return response()->json($gol, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/goles-jugadores/{id}",
     *     summary="Mostrar un gol registrado",
     *     tags={"Goles de Jugadores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del registro de gol"
     *     ),
     *     @OA\Response(response=200, description="Detalle del gol registrado"),
     *     @OA\Response(response=404, description="Gol no encontrado")
     * )
     */
    public function show($id)
    {
        $gol = GolJugador::with(['jugador.equipo', 'encuentro'])->findOrFail($id);
        return response()->json($gol);
    }

    /**
     * @OA\Put(
     *     path="/api/goles-jugadores/{id}",
     *     summary="Actualizar un registro de gol",
     *     tags={"Goles de Jugadores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del gol a actualizar"
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="jugador_id", type="integer", example=1),
     *             @OA\Property(property="encuentro_id", type="integer", example=2),
     *             @OA\Property(property="cantidad", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Gol actualizado correctamente"),
     *     @OA\Response(response=404, description="Gol no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $gol = GolJugador::findOrFail($id);

        $validated = $request->validate([
            'jugador_id'   => 'sometimes|required|exists:jugadores,id',
            'encuentro_id' => 'sometimes|required|exists:encuentros,id',
            'cantidad'     => 'sometimes|required|integer|min:1',
        ]);

        $gol->update($validated);

        // 👈 Devolvemos actualizado con relaciones
        $gol->load(['jugador.equipo', 'encuentro']);

        return response()->json($gol);
    }

    /**
     * @OA\Delete(
     *     path="/api/goles-jugadores/{id}",
     *     summary="Eliminar un registro de gol",
     *     tags={"Goles de Jugadores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID del gol a eliminar"
     *     ),
     *     @OA\Response(response=204, description="Gol eliminado correctamente"),
     *     @OA\Response(response=404, description="Gol no encontrado")
     * )
     */
    public function destroy($id)
    {
        $gol = GolJugador::findOrFail($id);
        $gol->delete();

        return response()->json(null, 204);
    }
}
