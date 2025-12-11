<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controladores
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

// =====================================
// RUTAS PÚBLICAS (Sin autenticación)
// =====================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/registro-equipo', [EquipoController::class, 'registrarEquipoCompleto']);

// CONSULTAS PÚBLICAS (Solo lectura)
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
Route::get('/sedes/{sede}', [SedeController::class, 'show']);

Route::get('/jueces', [JuezController::class, 'index']);
Route::get('/jueces/{juez}', [JuezController::class, 'show']);

Route::get('/encuentros', [EncuentroController::class, 'index']);
Route::get('/encuentros/{encuentro}', [EncuentroController::class, 'show']);

Route::get('/inscripciones', [InscripcionController::class, 'index']);
Route::get('/inscripciones/{inscripcion}', [InscripcionController::class, 'show']);

// =====================================
// RUTAS AUTENTICADAS (Todos los usuarios)
// =====================================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn(Request $request) => $request->user());

    // PERFIL PERSONAL (Todos los usuarios autenticados)
    Route::get('/mi-perfil', [JugadorController::class, 'miPerfil']);
    Route::put('/mi-perfil', [JugadorController::class, 'actualizarMiPerfil']);
    Route::get('/mis-estadisticas', [JugadorController::class, 'misEstadisticas']);
    Route::get('/mi-historial-equipos', [JugadorController::class, 'historialEquipos']);
    Route::post('/salir-del-equipo', [JugadorController::class, 'salirDelEquipo']);
    
    // CONSULTAS ADICIONALES (Con más detalle para usuarios autenticados)
    Route::get('/amonestaciones', [AmonestacionController::class, 'index']);
    Route::get('/amonestaciones/{amonestacion}', [AmonestacionController::class, 'show']);
    Route::get('/estadisticas-equipos', [EstadisticaEquipoController::class, 'index']);
    Route::get('/estadisticas-equipos/{estadistica}', [EstadisticaEquipoController::class, 'show']);
    Route::get('/goles-jugadores', [GolJugadorController::class, 'index']);
    Route::get('/goles-jugadores/{gol}', [GolJugadorController::class, 'show']);
    Route::get('/recibos', [ReciboDePagoController::class, 'index']);
    Route::get('/recibos/{recibo}', [ReciboDePagoController::class, 'show']);
});

// =====================================
// RUTAS SOLO CAPITÁN
// =====================================
// NOTA: Se quita el update de jugadores aquí para evitar middleware inexistente
// y porque el Capitán no debe cambiar equipo por seguridad.
Route::middleware(['auth:sanctum', 'check.capitan'])->group(function () {
    // GESTIONAR SU EQUIPO
    Route::get('/mi-equipo', [EquipoController::class, 'miEquipo']);
    Route::get('/equipos/{equipo}/jugadores', [EquipoController::class, 'jugadoresDelEquipo']);
    
    // MODIFICAR SU EQUIPO
    Route::post('/equipos/{equipo}/agregar-jugador', [EquipoController::class, 'agregarJugador']);
    Route::delete('/equipos/{equipo}/jugadores/{jugador}', [EquipoController::class, 'removerJugador']);
    Route::put('/equipos/{equipo}/cambiar-capitan', [EquipoController::class, 'cambiarCapitan']);

    // Si en el futuro registras los middlewares, podrías permitir edición limitada:
    // Route::put('/jugadores/{jugador}', [JugadorController::class, 'update'])
    //     ->middleware('check.jugador.equipo');
});

// =====================================
// RUTAS SOLO ADMINISTRADOR
// =====================================
Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckAdministrador::class])->group(function () {

    // CRUD COMPLETO DE TORNEOS (Solo Admin)
    Route::post('/torneos', [TorneoController::class, 'store']);
    Route::put('/torneos/{torneo}', [TorneoController::class, 'update']);
    Route::delete('/torneos/{torneo}', [TorneoController::class, 'destroy']);
    
    // CRUD COMPLETO DE EQUIPOS (Solo Admin)
    Route::post('/equipos', [EquipoController::class, 'store']);
    Route::put('/equipos/{equipo}', [EquipoController::class, 'update']);
    Route::delete('/equipos/{equipo}', [EquipoController::class, 'destroy']);
    
    // CRUD COMPLETO DE JUGADORES (Solo Admin)
    Route::post('/jugadores', [JugadorController::class, 'store']);
    Route::put('/jugadores/{jugador}', [JugadorController::class, 'update']); // <-- MOVIDO AQUÍ
    Route::delete('/jugadores/{jugador}', [JugadorController::class, 'destroy']);
    
    // CRUD COMPLETO DE ENCUENTROS (Solo Admin)
    Route::post('/encuentros', [EncuentroController::class, 'store']);
    Route::put('/encuentros/{encuentro}', [EncuentroController::class, 'update']);
    Route::delete('/encuentros/{encuentro}', [EncuentroController::class, 'destroy']);
    
    // CRUD COMPLETO DE SEDES (Solo Admin)
    Route::post('/sedes', [SedeController::class, 'store']);
    Route::put('/sedes/{sede}', [SedeController::class, 'update']);
    Route::delete('/sedes/{sede}', [SedeController::class, 'destroy']);
    
    // CRUD COMPLETO DE INSCRIPCIONES (Solo Admin)
    Route::post('/inscripciones', [InscripcionController::class, 'store']);
    Route::put('/inscripciones/{inscripcion}', [InscripcionController::class, 'update']);
    Route::delete('/inscripciones/{inscripcion}', [InscripcionController::class, 'destroy']);
    
    // CRUD COMPLETO DE JUECES (Solo Admin)
    Route::post('/jueces', [JuezController::class, 'store']);
    Route::put('/jueces/{juez}', [JuezController::class, 'update']);
    Route::delete('/jueces/{juez}', [JuezController::class, 'destroy']);
    
    // CRUD COMPLETO DE AMONESTACIONES (Solo Admin)
    Route::post('/amonestaciones', [AmonestacionController::class, 'store']);
    Route::put('/amonestaciones/{amonestacion}', [AmonestacionController::class, 'update']);
    Route::delete('/amonestaciones/{amonestacion}', [AmonestacionController::class, 'destroy']);
    
    // CRUD COMPLETO DE ESTADÍSTICAS (Solo Admin)
    Route::post('/estadisticas-equipos', [EstadisticaEquipoController::class, 'store']);
    Route::put('/estadisticas-equipos/{estadistica}', [EstadisticaEquipoController::class, 'update']);
    Route::delete('/estadisticas-equipos/{estadistica}', [EstadisticaEquipoController::class, 'destroy']);
    
    // CRUD COMPLETO DE GOLES (Solo Admin)
    Route::post('/goles-jugadores', [GolJugadorController::class, 'store']);
    Route::put('/goles-jugadores/{gol}', [GolJugadorController::class, 'update']);
    Route::delete('/goles-jugadores/{gol}', [GolJugadorController::class, 'destroy']);
    
    // CRUD COMPLETO DE RECIBOS (Solo Admin)
    Route::post('/recibos', [ReciboDePagoController::class, 'store']);
    Route::put('/recibos/{recibo}', [ReciboDePagoController::class, 'update']);
    Route::delete('/recibos/{recibo}', [ReciboDePagoController::class, 'destroy']);
});

// =====================================
// RUTA 404 PERSONALIZADA
// =====================================
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
        'error_code' => 'ROUTE_NOT_FOUND'
    ], 404);
});
