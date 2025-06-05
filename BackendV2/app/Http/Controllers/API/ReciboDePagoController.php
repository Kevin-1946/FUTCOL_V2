<?php

namespace App\Http\Controllers;

use App\Models\ReciboDePago;
use Illuminate\Http\Request;

class ReciboDePagoController extends Controller
{
    // Listar todos los recibos
    public function index()
    {
        $recibos = ReciboDePago::with(['suscripcion', 'torneo'])->get();
        return response()->json($recibos);
    }

    // Crear un nuevo recibo
    public function store(Request $request)
    {
        $request->validate([
            'suscripcion_id' => 'required|exists:suscripcions,id',
            'torneo_id' => 'required|exists:torneos,id',
            'monto' => 'required|numeric|min:0',
            'fecha_emision' => 'required|date',
            'confirmado' => 'required|boolean',
            'metodo_pago' => 'required|string|max:100',
            'numero_comprobante' => 'required|string|max:100',
        ]);

        $recibo = ReciboDePago::create($request->all());

        return response()->json($recibo, 201);
    }

    // Mostrar un recibo específico
    public function show($id)
    {
        $recibo = ReciboDePago::with(['suscripcion', 'torneo'])->findOrFail($id);
        return response()->json($recibo);
    }

    // Actualizar un recibo
    public function update(Request $request, $id)
    {
        $recibo = ReciboDePago::findOrFail($id);

        $request->validate([
            'suscripcion_id' => 'sometimes|required|exists:suscripcions,id',
            'torneo_id' => 'sometimes|required|exists:torneos,id',
            'monto' => 'sometimes|required|numeric|min:0',
            'fecha_emision' => 'sometimes|required|date',
            'confirmado' => 'sometimes|required|boolean',
            'metodo_pago' => 'sometimes|required|string|max:100',
            'numero_comprobante' => 'sometimes|required|string|max:100',
        ]);

        $recibo->update($request->all());

        return response()->json($recibo);
    }

    // Eliminar un recibo
    public function destroy($id)
    {
        $recibo = ReciboDePago::findOrFail($id);
        $recibo->delete();

        return response()->json(null, 204);
    }
}
