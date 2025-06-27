<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inscripcion;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    // Listar todas las inscripciones
    public function index()
    {
        $inscripciones = Inscripcion::with(['equipo', 'torneo'])->get();
        return response()->json($inscripciones);
    }

    // Crear una nueva inscripción
    public function store(Request $request)
    {
        $request->validate([
            'equipo_id' => 'required|exists:equipos,id',
            'torneo_id' => 'required|exists:torneos,id',
            'fecha_de_inscripcion' => 'required|date',
            'forma_pago' => 'required|string|max:100',
            'estado_pago' => 'required|string|max:100',
            'correo_confirmado' => 'required|boolean',
            'total_pagado' => 'required|numeric|min:0',
        ]);

        $inscripcion = Inscripcion::create($request->all());

        return response()->json($inscripcion, 201);
    }

    // Mostrar una inscripción específica
    public function show($id)
    {
        $inscripcion = Inscripcion::with(['equipo', 'torneo'])->findOrFail($id);
        return response()->json($inscripcion);
    }

    // Actualizar una inscripción
    public function update(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        $request->validate([
            'equipo_id' => 'sometimes|required|exists:equipos,id',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'fecha_de_inscripcion' => 'sometimes|required|date',
            'forma_pago' => 'sometimes|required|string|max:100',
            'estado_pago' => 'sometimes|required|string|max:100',
            'correo_confirmado' => 'sometimes|required|boolean',
            'total_pagado' => 'sometimes|required|numeric|min:0',
        ]);

        $inscripcion->update($request->all());

        return response()->json($inscripcion);
    }

    // Eliminar una inscripción
    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $inscripcion->delete();

        return response()->json(null, 204);
    }
}