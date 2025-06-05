<?php

namespace App\Http\Controllers;

use App\Models\GolJugador;
use Illuminate\Http\Request;

class GolJugadorController extends Controller
{
    // Listar todos los registros de goles
    public function index()
    {
        $goles = GolJugador::with(['jugador', 'encuentro'])->get();
        return response()->json($goles);
    }

    // Crear un nuevo registro de gol
    public function store(Request $request)
    {
        $request->validate([
            'jugador_id' => 'required|exists:jugadors,id',
            'encuentro_id' => 'required|exists:encuentros,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $gol = GolJugador::create($request->all());

        return response()->json($gol, 201);
    }

    // Mostrar un registro de gol específico
    public function show($id)
    {
        $gol = GolJugador::with(['jugador', 'encuentro'])->findOrFail($id);
        return response()->json($gol);
    }

    // Actualizar un registro de gol
    public function update(Request $request, $id)
    {
        $gol = GolJugador::findOrFail($id);

        $request->validate([
            'jugador_id' => 'sometimes|required|exists:jugadors,id',
            'encuentro_id' => 'sometimes|required|exists:encuentros,id',
            'cantidad' => 'sometimes|required|integer|min:1',
        ]);

        $gol->update($request->all());

        return response()->json($gol);
    }

    // Eliminar un registro de gol
    public function destroy($id)
    {
        $gol = GolJugador::findOrFail($id);
        $gol->delete();

        return response()->json(null, 204);
    }
}