<?php

// ========================================
// IMPORTAR TODOS LOS CONTROLADORES
// ========================================
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\JugadorController;
use App\Http\Controllers\API\EquipoController;
use App\Http\Controllers\API\TorneoController;
use App\Http\Controllers\API\InscripcionController;
use App\Http\Controllers\API\SuscripcionController;
use App\Http\Controllers\API\ReciboDePagoController;
use App\Http\Controllers\API\EncuentroController;
use App\Http\Controllers\API\EstadisticaEquipoController;
use App\Http\Controllers\API\GolJugadorController;
use App\Http\Controllers\API\AmonestacionController;
use App\Http\Controllers\API\SedeController;
use App\Http\Controllers\API\JuezController;
use App\Http\Controllers\API\ResetContrasenaController;

// ========================================
// RUTAS PÚBLICAS DE AUTENTICACIÓN
// ========================================
// Login general (para capitanes y participantes)
Route::post('login', [AuthController::class, 'login']);

// Login específico para administradores
Route::post('login-admin', [AuthController::class, 'loginAdmin']);

// Registro público de capitanes (pueden auto-registrarse)
Route::post('register', [AuthController::class, 'register']);

// Rutas de recuperación de contraseña
Route::post('forgot-password', [ResetContrasenaController::class, 'enviarLinkReset']);
Route::post('validar-token', [ResetContrasenaController::class, 'validarToken'])->name('password.token');
Route::post('reset-password', [ResetContrasenaController::class, 'reset']);

// ========================================
// RUTAS PÚBLICAS DE CONSULTA
// ========================================
// Cualquier persona (incluso sin login) puede ver estas consultas
Route::apiResource('jugadores', JugadorController::class)->only(['index', 'show']);
Route::apiResource('equipos', EquipoController::class)->only(['index', 'show']);
Route::apiResource('torneos', TorneoController::class)->only(['index', 'show']);
Route::apiResource('encuentros', EncuentroController::class)->only(['index', 'show']);
Route::apiResource('estadisticas-equipos', EstadisticaEquipoController::class)->only(['index', 'show']);
Route::apiResource('goles-jugadores', GolJugadorController::class)->only(['index', 'show']);
Route::apiResource('amonestaciones', AmonestacionController::class)->only(['index', 'show']);
Route::apiResource('sedes', SedeController::class)->only(['index', 'show']);
Route::apiResource('jueces', JuezController::class)->only(['index', 'show']);

// Rutas de filtros públicas
Route::get('torneos/{torneo}/estadisticas', [EstadisticaEquipoController::class, 'porTorneo']);
Route::get('jugadores/{jugador}/goles', [GolJugadorController::class, 'porJugador']);
Route::get('jugadores/{jugador}/amonestaciones', [AmonestacionController::class, 'porJugador']);

// ========================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
// ========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // ========================================
    // RUTAS BÁSICAS PARA CUALQUIER USUARIO AUTENTICADO
    // ========================================
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('perfil', [AuthController::class, 'perfil']);
    Route::get('refresh-token', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    
    // ========================================
    // RUTAS SOLO PARA ADMINISTRADORES
    // ========================================
    Route::middleware('admin')->group(function () {
        
        // Registro de usuarios (solo admin puede crear cuentas de otros)
        Route::post('register-capitan', [AuthController::class, 'registerCapitan']);
        Route::post('register-participante', [AuthController::class, 'registerParticipante']);
        
        // Gestión completa de jugadores (solo admin puede crear/editar/eliminar)
        Route::apiResource('jugadores', JugadorController::class)->except(['index', 'show']);
        
        // Gestión completa de equipos (solo admin puede crear/editar/eliminar)
        Route::apiResource('equipos', EquipoController::class)->except(['index', 'show']);
        
        // Gestión completa de torneos (solo admin puede crear/editar/eliminar)
        Route::apiResource('torneos', TorneoController::class)->except(['index', 'show']);
        
        // Gestión de encuentros (solo admin actualiza resultados después de cada fecha)
        Route::apiResource('encuentros', EncuentroController::class)->except(['index', 'show']);
        
        // Actualización de estadísticas (solo admin después de cada fecha)
        Route::apiResource('estadisticas-equipos', EstadisticaEquipoController::class)->except(['index', 'show']);
        
        // Registro de goles (solo admin después de cada encuentro)
        Route::apiResource('goles-jugadores', GolJugadorController::class)->except(['index', 'show']);
        
        // Gestión de amonestaciones (solo admin puede registrar)
        Route::apiResource('amonestaciones', AmonestacionController::class)->except(['index', 'show']);
        
        // Gestión de sedes y jueces (solo admin)
        Route::apiResource('sedes', SedeController::class)->except(['index', 'show']);
        Route::apiResource('jueces', JuezController::class)->except(['index', 'show']);
        
    });
    
    // ========================================
    // RUTAS SOLO PARA CAPITANES
    // ========================================
    Route::middleware('capitan')->group(function () {
        
        // Inscripciones de equipos (solo capitanes pueden inscribir sus equipos)
        Route::apiResource('inscripciones', InscripcionController::class);
        
        // Suscripciones (solo capitanes pueden suscribir sus equipos)
        Route::apiResource('suscripciones', SuscripcionController::class);
        
        // Recibos de pago (solo capitanes pueden hacer pagos de sus equipos)
        Route::apiResource('recibos-de-pago', ReciboDePagoController::class);
        
    });
    
    // ========================================
    // RUTAS PARA PARTICIPANTES
    // ========================================
    // Los participantes solo pueden VER datos, no modificar
    // Ya tienen acceso a todas las rutas públicas de consulta
    // No necesitan rutas especiales porque no pueden editar nada
    
});