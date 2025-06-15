<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TorneoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\PartidoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas de autenticación (públicas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas (sin autenticación)
Route::get('/torneos', [TorneoController::class, 'index']);
Route::get('/torneos/{torneo}', [TorneoController::class, 'show']);
Route::get('/torneos/{torneo}/equipos', [EquipoController::class, 'equiposPorTorneo']);
Route::get('/equipos', [EquipoController::class, 'index']);
Route::get('/equipos/{equipo}', [EquipoController::class, 'show']);
Route::get('/jugadores', [JugadorController::class, 'index']);
Route::get('/jugadores/{jugador}', [JugadorController::class, 'show']);
Route::get('/jugadores-sin-equipo', [JugadorController::class, 'jugadoresSinEquipo']);
Route::get('/buscar-jugadores', [JugadorController::class, 'buscarJugadores']);

// Rutas protegidas por autenticación (cualquier usuario autenticado)
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de perfil personal (cualquier jugador autenticado)
    Route::get('/mi-perfil', [JugadorController::class, 'miPerfil']);
    Route::put('/mi-perfil', [JugadorController::class, 'actualizarMiPerfil']);
    Route::get('/mis-estadisticas', [JugadorController::class, 'misEstadisticas']);
    Route::get('/mi-historial-equipos', [JugadorController::class, 'historialEquipos']);
    Route::post('/salir-del-equipo', [JugadorController::class, 'salirDelEquipo']);
});

// Rutas solo para ADMINISTRADORES
Route::middleware(['auth:sanctum', 'check.administrador'])->group(function () {
    // Gestión completa de torneos
    Route::post('/torneos', [TorneoController::class, 'store']);
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update']);
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy']);
    
    // Gestión completa de jugadores
    Route::post('/jugadores', [JugadorController::class, 'store']);
    Route::put('/jugadores/{jugador}', [JugadorController::class, 'update']);
    Route::delete('/jugadores/{jugador}', [JugadorController::class, 'destroy']);
    
    // Gestión completa de equipos
    Route::post('/equipos', [EquipoController::class, 'store']);
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update']);
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy']);
    
    // Gestión de partidos (si tienes PartidoController)
    Route::apiResource('partidos', PartidoController::class);
});

// Rutas solo para CAPITANES
Route::middleware(['auth:sanctum', 'check.capitan'])->group(function () {
    // Gestión del equipo propio
    Route::get('/mi-equipo', [EquipoController::class, 'miEquipo']);
    Route::post('/equipos/{equipo}/agregar-jugador', [EquipoController::class, 'agregarJugador']);
    Route::delete('/equipos/{equipo}/jugadores/{jugador}', [EquipoController::class, 'removerJugador']);
    Route::put('/equipos/{equipo}/cambiar-capitan', [EquipoController::class, 'cambiarCapitan']);
});

// Rutas solo para PARTICIPANTES
Route::middleware(['auth:sanctum', 'check.participante'])->group(function () {
    // Los participantes pueden ver información básica
    // (ya están cubiertas por las rutas públicas y de perfil personal)
});

// Rutas para CAPITANES y ADMINISTRADORES (usuarios con permisos elevados)
Route::middleware(['auth:sanctum', 'check.capitan'])->group(function () {  // ← Usa tu middleware existente
    Route::get('/equipos/{equipo}/jugadores', function($equipoId) {
        $equipo = \App\Models\Equipo::with('jugadores')->findOrFail($equipoId);
        return response()->json($equipo->jugadores);
    }); // ← Sin middleware adicional
});

// Rutas de fallback para manejo de errores
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
        'error_code' => 'ROUTE_NOT_FOUND'
    ], 404);
});