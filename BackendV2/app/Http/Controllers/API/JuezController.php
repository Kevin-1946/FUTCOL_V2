<?php

namespace App\Http\Controllers;

use App\Models\Juez;
use Illuminate\Http\Request;

class JuezController extends Controller
{
    // Mostrar lista de jueces
    public function index()
    {
        $jueces = Juez::all();
        return response()->json($jueces);
    }

    // Crear un nuevo juez
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_de_contacto' => 'required|string|max:20',
            'correo' => 'required|email|unique:jueces,correo',
            'sede_asignada' => 'required|string|max:255',
        ]);

        $juez = Juez::create($request->all());

        return response()->json($juez, 201);
    }

    // Mostrar un juez específico
    public function show($id)
    {
        $juez = Juez::findOrFail($id);
        return response()->json($juez);
    }

    // Actualizar un juez
    public function update(Request $request, $id)
    {
        $juez = Juez::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'numero_de_contacto' => 'sometimes|required|string|max:20',
            'correo' => 'sometimes|required|email|unique:jueces,correo,' . $juez->id,
            'sede_asignada' => 'sometimes|required|string|max:255',
        ]);

        $juez->update($request->all());

        return response()->json($juez);
    }

    // Eliminar un juez
    public function destroy($id)
    {
        $juez = Juez::findOrFail($id);
        $juez->delete();

        return response()->json(null, 204);
    }
}
