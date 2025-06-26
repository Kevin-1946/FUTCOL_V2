<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TorneoController;
use App\Http\Controllers\API\EquipoController;
use App\Http\Controllers\API\JugadorController;
use App\Http\Controllers\API\PartidoController;
use App\Http\Controllers\API\SedeController;
use App\Http\Controllers\API\SuscripcionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aquí se registran todas las rutas expuestas por la API
| con los respectivos middlewares según cada rol.
*/

// --------------------
// RUTAS PÚBLICAS
// --------------------
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
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Perfil del jugador
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
    // Torneos
    Route::post('/torneos', [TorneoController::class, 'store']);
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update']);
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy']);

    // Equipos
    Route::post('/equipos', [EquipoController::class, 'store']);
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update']);
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy']);

    // Jugadores
    Route::post('/jugadores', [JugadorController::class, 'store']);
    Route::put('/jugadores/{jugador}', [JugadorController::class, 'update']);
    Route::delete('/jugadores/{jugador}', [JugadorController::class, 'destroy']);

    // Partidos
    Route::apiResource('partidos', PartidoController::class);

    // Sedes
    Route::apiResource('sedes', SedeController::class)->except(['index', 'show']);

    // Suscripciones
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

    // (Opcional) Ver jugadores de su equipo
    Route::get('/equipos/{equipo}/jugadores', function($equipoId) {
        $equipo = \App\Models\Equipo::with('jugadores')->findOrFail($equipoId);
        return response()->json($equipo->jugadores);
    });
});

// --------------------
// RUTAS SOLO PARTICIPANTES
// --------------------
Route::middleware(['auth:sanctum', 'check.participante'])->group(function () {
    // (Actualmente ya están cubiertas por rutas públicas y autenticadas)
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