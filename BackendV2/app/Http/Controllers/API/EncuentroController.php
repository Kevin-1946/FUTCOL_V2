<?php

namespace App\Http\Controllers;

use App\Models\Encuentro;
use Illuminate\Http\Request;

class EncuentroController extends Controller
{
    // Listar todos los encuentros
    public function index()
    {
        $encuentros = Encuentro::with([
            'torneo',
            'sede',
            'equipoLocal',
            'equipoVisitante'
        ])->get();

        return response()->json($encuentros);
    }

    // Crear un nuevo encuentro
    public function store(Request $request)
    {
        $request->validate([
            'torneo_id' => 'required|exists:torneos,id',
            'sede_id' => 'required|exists:sedes,id',
            'fecha' => 'required|date',
            'equipo_local_id' => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'goles_equipo_local' => 'nullable|integer|min:0',
            'goles_equipo_visitante' => 'nullable|integer|min:0',
        ]);

        $encuentro = Encuentro::create($request->all());

        return response()->json($encuentro, 201);
    }

    // Mostrar un encuentro específico
    public function show($id)
    {
        $encuentro = Encuentro::with([
            'torneo',
            'sede',
            'equipoLocal',
            'equipoVisitante'
        ])->findOrFail($id);

        return response()->json($encuentro);
    }

    // Actualizar un encuentro
    public function update(Request $request, $id)
    {
        $encuentro = Encuentro::findOrFail($id);

        $request->validate([
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'sede_id' => 'sometimes|required|exists:sedes,id',
            'fecha' => 'sometimes|required|date',
            'equipo_local_id' => 'sometimes|required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'sometimes|required|exists:equipos,id',
            'goles_equipo_local' => 'nullable|integer|min:0',
            'goles_equipo_visitante' => 'nullable|integer|min:0',
        ]);

        $encuentro->update($request->all());

        return response()->json($encuentro);
    }

    // Eliminar un encuentro
    public function destroy($id)
    {
        $encuentro = Encuentro::findOrFail($id);
        $encuentro->delete();

        return response()->json(null, 204);
    }
}
