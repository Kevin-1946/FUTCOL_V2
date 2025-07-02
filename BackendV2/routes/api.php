<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores (asegúrate que todos estén bajo App\Http\Controllers\API)
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TorneoController;
use App\Http\Controllers\API\EquipoController;
use App\Http\Controllers\API\JugadorController;
use App\Http\Controllers\API\EncuentroController;
use App\Http\Controllers\API\SedeController;
use App\Http\Controllers\API\SuscripcionController;

// --------------------
// RUTAS PÚBLICAS
// --------------------
Route::post('/registro-equipo', [EquipoController::class, 'registrarEquipoCompleto']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/torneos', [TorneoController::class, 'index']);
Route::get('/torneos/{torneo}', [TorneoController::class, 'show']);
Route::get('/torneos/{torneo}/equipos', [EquipoController::class, 'equiposPorTorneo']);

Route::get('/equipos', [EquipoController::class, 'index']);
Route::get('/equipos/{equipo}', [EquipoController::class, 'show']);

Route::get('/jugadores', [JugadorController::class, 'index']);
Route::get('/jugadores/{jugador}', [JugadorController::class, 'show']);
Route::get('/jugadores-sin-equipo', [JugadorController::class, 'jugadoresSinEquipo']);
Route::get('/buscar-jugadores', [JugadorController::class, 'buscarJugadores']);

Route::get('/sedes', [SedeController::class, 'index']);
Route::get('/suscripciones', [SuscripcionController::class, 'index']);

// --------------------
// RUTAS AUTENTICADAS
// --------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('/mi-perfil', [JugadorController::class, 'miPerfil']);
    Route::put('/mi-perfil', [JugadorController::class, 'actualizarMiPerfil']);
    Route::get('/mis-estadisticas', [JugadorController::class, 'misEstadisticas']);
    Route::get('/mi-historial-equipos', [JugadorController::class, 'historialEquipos']);
    Route::post('/salir-del-equipo', [JugadorController::class, 'salirDelEquipo']);
});

// --------------------
// RUTAS SOLO ADMINISTRADOR
// --------------------
Route::middleware(['auth:sanctum', 'check.administrador'])->group(function () {
    Route::apiResource('torneos', TorneoController::class)->except(['index', 'show']);
    Route::apiResource('equipos', EquipoController::class)->except(['index', 'show']);
    Route::apiResource('jugadores', JugadorController::class)->except(['index', 'show']);
    Route::apiResource('partidos', EncuentroController::class);
    Route::apiResource('sedes', SedeController::class)->except(['index', 'show']);
    Route::apiResource('suscripciones', SuscripcionController::class)->except(['index', 'show']);
});

// --------------------
// RUTAS SOLO CAPITÁN
// --------------------
Route::middleware(['auth:sanctum', 'check.capitan'])->group(function () {
    Route::get('/mi-equipo', [EquipoController::class, 'miEquipo']);
    Route::post('/equipos/{equipo}/agregar-jugador', [EquipoController::class, 'agregarJugador']);
    Route::delete('/equipos/{equipo}/jugadores/{jugador}', [EquipoController::class, 'removerJugador']);
    Route::put('/equipos/{equipo}/cambiar-capitan', [EquipoController::class, 'cambiarCapitan']);

    Route::get('/equipos/{equipo}/jugadores', function ($equipoId) {
        $equipo = \App\Models\Equipo::with('jugadores')->findOrFail($equipoId);
        return response()->json($equipo->jugadores);
    });
});

// --------------------
// RUTA POR DEFECTO (404)
// --------------------
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
        'error_code' => 'ROUTE_NOT_FOUND'
    ], 404);
});
