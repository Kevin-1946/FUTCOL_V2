<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Encuentro;
use Illuminate\Http\Request;

class EncuentroController extends Controller
{
    public function index()
    {
        $encuentros = Encuentro::with(['torneo','sede','equipoLocal','equipoVisitante'])->get();
        return response()->json($encuentros);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'torneo_id'           => 'required|integer|exists:torneos,id',
                'sede_id'             => 'required|integer|exists:sedes,id',
                'fecha'               => 'required|date',          // YYYY-MM-DD
                'hora'                => 'required|date_format:H:i', // HH:MM (lo que manda <input type="time">
                'equipo_local_id'     => 'required|integer|exists:equipos,id|different:equipo_visitante_id',
                'equipo_visitante_id' => 'required|integer|exists:equipos,id|different:equipo_local_id',
                'goles_local'         => 'nullable|integer|min:0',
                'goles_visitante'     => 'nullable|integer|min:0',
            ]);

            // Si tu DB no permite NULL en goles, normaliza a 0
            $validated['goles_local']     = $validated['goles_local']     ?? 0;
            $validated['goles_visitante'] = $validated['goles_visitante'] ?? 0;

            $encuentro = Encuentro::create($validated);

            return response()->json(
                $encuentro->load(['torneo','sede','equipoLocal','equipoVisitante']),
                201
            );
        } catch (\Throwable $e) {
            \Log::error('Error creando encuentro', [
                'msg' => $e->getMessage(),
                'file'=> $e->getFile(),
                'line'=> $e->getLine(),
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => 'Error interno al crear el encuentro'], 500);
        }
    }

    public function show($id)
    {
        $encuentro = Encuentro::with(['torneo','sede','equipoLocal','equipoVisitante'])->findOrFail($id);
        return response()->json($encuentro);
    }

    public function update(Request $request, $id)
    {
        try {
            $encuentro = Encuentro::findOrFail($id);

            $validated = $request->validate([
                'torneo_id'           => 'sometimes|required|integer|exists:torneos,id',
                'sede_id'             => 'sometimes|required|integer|exists:sedes,id',
                'fecha'               => 'sometimes|required|date',
                'hora'                => 'sometimes|required|date_format:H:i',
                'equipo_local_id'     => 'sometimes|required|integer|exists:equipos,id|different:equipo_visitante_id',
                'equipo_visitante_id' => 'sometimes|required|integer|exists:equipos,id|different:equipo_local_id',
                'goles_local'         => 'sometimes|nullable|integer|min:0',
                'goles_visitante'     => 'sometimes|nullable|integer|min:0',
            ]);

            if (array_key_exists('goles_local', $validated) && $validated['goles_local'] === null) {
                $validated['goles_local'] = 0;
            }
            if (array_key_exists('goles_visitante', $validated) && $validated['goles_visitante'] === null) {
                $validated['goles_visitante'] = 0;
            }

            $encuentro->update($validated);

            return response()->json(
                $encuentro->load(['torneo','sede','equipoLocal','equipoVisitante'])
            );
        } catch (\Throwable $e) {
            \Log::error('Error actualizando encuentro', [
                'msg' => $e->getMessage(),
                'file'=> $e->getFile(),
                'line'=> $e->getLine(),
                'payload' => $request->all(),
            ]);
            return response()->json(['message' => 'Error interno al actualizar el encuentro'], 500);
        }
    }

    public function destroy($id)
    {
        $encuentro = Encuentro::findOrFail($id);
        $encuentro->delete();
        return response()->json(null, 204);
    }
}