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
use App\Http\Controllers\API\InscripcionController;
use App\Http\Controllers\API\AmonestacionController;
use App\Http\Controllers\API\EstadisticaEquipoController;
use App\Http\Controllers\API\GolJugadorController;
use App\Http\Controllers\API\JuezController;
use App\Http\Controllers\API\ReciboDePagoController;
use App\Http\Middleware\Login;

// --------------------
// RUTAS PÚBLICAS
// --------------------
Route::post('/registro-equipo', [EquipoController::class, 'registrarEquipoCompleto']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/jueces', [JuezController::class, 'index']);
Route::get('/jueces/{id}', [JuezController::class, 'show']);

// Solo GET públicos para torneos
Route::get('/torneos', [TorneoController::class, 'index']);
Route::get('/torneos/{torneo}', [TorneoController::class, 'show']);
Route::get('/torneos/{torneo}/equipos', [EquipoController::class, 'equiposPorTorneo']);

Route::get('/equipos', [EquipoController::class, 'index']);
Route::get('/equipos/{equipo}', [EquipoController::class, 'show']);

Route::get('/Jugadores', [JugadorController::class, 'index']);
Route::get('/jugadores/{jugador}', [JugadorController::class, 'show']);
Route::get('/jugadores-sin-equipo', [JugadorController::class, 'jugadoresSinEquipo']);
Route::get('/buscar-jugadores', [JugadorController::class, 'buscarJugadores']);

Route::get('/sedes', [SedeController::class, 'index']);
Route::get('/inscripciones', [InscripcionController::class, 'index']);

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
    
    // ✅ NUEVAS RUTAS PARA CONSULTA (CAPITÁN Y PARTICIPANTES)
    Route::get('/encuentros', [EncuentroController::class, 'index']);
    Route::get('/encuentros/{encuentro}', [EncuentroController::class, 'show']);
    Route::get('/amonestaciones', [AmonestacionController::class, 'index']);
    Route::get('/amonestaciones/{amonestacion}', [AmonestacionController::class, 'show']);
    Route::get('/estadisticas-equipos', [EstadisticaEquipoController::class, 'index']);
    Route::get('/estadisticas-equipos/{estadistica}', [EstadisticaEquipoController::class, 'show']);
    
    // 🔧 CORREGIDO: Rutas para goles de jugadores
    Route::get('/goles-jugadores', [GolJugadorController::class, 'index']);
    Route::get('/goles-jugadores/{gol}', [GolJugadorController::class, 'show']);
    
    Route::get('/recibos', [ReciboDePagoController::class, 'index']);
    Route::get('/recibos/{recibo}', [ReciboDePagoController::class, 'show']);
});

// --------------------
// RUTAS SOLO ADMINISTRADOR
// --------------------
Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckAdministrador::class])->group(function () {
    // CRUD COMPLETO SOLO PARA ADMINISTRADOR
    Route::apiResource('torneos', TorneoController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('equipos', EquipoController::class)->except(['index', 'show']);
    Route::apiResource('jugadores', JugadorController::class)->except(['index', 'show']);
    Route::apiResource('encuentros', EncuentroController::class)->except(['index', 'show']);
    Route::apiResource('sedes', SedeController::class)->except(['index', 'show']);
    Route::apiResource('inscripciones', InscripcionController::class)->except(['index']);
    Route::apiResource('jueces', JuezController::class)->except(['index', 'show']);
    Route::apiResource('estadisticas-equipos', EstadisticaEquipoController::class)->except(['index', 'show']);
    
    // 🔧 CORREGIDO: ApiResource para goles de jugadores
    Route::apiResource('goles-jugadores', GolJugadorController::class)->except(['index', 'show']);
    
    Route::apiResource('amonestaciones', AmonestacionController::class)->except(['index', 'show']);
    Route::apiResource('recibos', ReciboDePagoController::class)->except(['index', 'show']);
});

// --------------------
// RUTAS SOLO CAPITÁN
// --------------------
Route::middleware(['auth:sanctum', 'check.capitan'])->group(function () {
    // VER SU EQUIPO
    Route::get('/mi-equipo', [EquipoController::class, 'miEquipo']);
    
    // GESTIONAR SU EQUIPO
    Route::post('/equipos/{equipo}/agregar-jugador', [EquipoController::class, 'agregarJugador']);
    Route::delete('/equipos/{equipo}/jugadores/{jugador}', [EquipoController::class, 'removerJugador']);
    Route::put('/equipos/{equipo}/cambiar-capitan', [EquipoController::class, 'cambiarCapitan']);

    // ✅ EDITAR SOLO SUS JUGADORES
    Route::put('/jugadores/{jugador}', [JugadorController::class, 'update'])->middleware('check.jugador.equipo');
    
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