<?php

use App\Http\Controllers\API\AuthController;

Route::post('register', [AuthController::class, 'register']); // Registro de capitán
Route::post('login', [AuthController::class, 'login']);       // Login

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);     // Logout
    Route::get('perfil', [AuthController::class, 'perfil']);      // Ver perfil del usuario autenticado
    Route::get('refresh-token', [AuthController::class, 'refresh']); // Validar sesión activa
});


/*Permite listar y ver jugadores sin autenticación.*/
/*Requiere login para crear, actualizar o borrar jugadores.*/
use App\Http\Controllers\API\JugadorController;

Route::apiResource('jugadores', JugadorController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('jugadores', JugadorController::class)->except(['index', 'show']);
});

/*Acceso de lectura libre*/
/*Protege las operaciones de escritura y eliminación*/
use App\Http\Controllers\API\EquipoController;

Route::apiResource('equipos', EquipoController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('equipos', EquipoController::class)->except(['index', 'show']);
});

/*Cualquier usuario puede consultar los torneos, pero su gestión está limitada a usuarios autenticados.*/
use App\Http\Controllers\API\TorneoController;

Route::apiResource('torneos', TorneoController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('torneos', TorneoController::class)->except(['index', 'show']);
});

/*Protegida solo para administradores y capitanes*/
use App\Http\Controllers\API\InscripcionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('inscripciones', InscripcionController::class);
});

/*Esta ruta deja administradores y capitanes de torneos ver sus suscripciones*/
use App\Http\Controllers\API\SuscripcionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('suscripciones', SuscripcionController::class);
});

/*Solo visible para administradores y capitanes */
use App\Http\Controllers\API\ReciboDePagoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('recibos-de-pago', ReciboDePagoController::class);
});

use App\Http\Controllers\API\EncuentroController;

// Rutas públicas (consultas)
Route::apiResource('encuentros', EncuentroController::class)->only(['index', 'show']);

// Rutas protegidas (admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('encuentros', EncuentroController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\EstadisticaEquipoController;

// Rutas públicas
Route::apiResource('estadisticas-equipos', EstadisticaEquipoController::class)->only(['index', 'show']);

// Filtradas por torneo
Route::get('torneos/{torneo}/estadisticas', [EstadisticaEquipoController::class, 'porTorneo']);

// Rutas protegidas para administradores
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('estadisticas-equipos', EstadisticaEquipoController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\GolJugadorController;

// Rutas públicas
Route::apiResource('goles-jugadores', GolJugadorController::class)->only(['index', 'show']);

// Filtra por jugador
Route::get('jugadores/{jugador}/goles', [GolJugadorController::class, 'porJugador']);

// Rutas protegidas para admin
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('goles-jugadores', GolJugadorController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\AmonestacionController;

// Rutas públicas (ejemplo: solo index y show)
Route::apiResource('amonestaciones', AmonestacionController::class)->only(['index', 'show']);

// Filtra amonestaciones por jugador (si aplica)
Route::get('jugadores/{jugador}/amonestaciones', [AmonestacionController::class, 'porJugador']);

// Rutas protegidas para admin y capitanes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('amonestaciones', AmonestacionController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\SedeController;

// Rutas públicas (index y show)
Route::apiResource('sedes', SedeController::class)->only(['index', 'show']);

// Rutas protegidas para admin (y capitanes si aplica)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('sedes', SedeController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\JuezController;

// Rutas públicas (index y show)
Route::apiResource('jueces', JuezController::class)->only(['index', 'show']);

// Rutas protegidas para admin (y otros roles si aplica)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('jueces', JuezController::class)->only(['store', 'update', 'destroy']);
});

use App\Http\Controllers\API\LoginUsuarioController;

// Ruta pública para iniciar sesión
Route::post('login', [LoginUsuarioController::class, 'login']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Cerrar sesión
    Route::post('logout', [LoginUsuarioController::class, 'logout']);

    // Ver perfil del usuario autenticado (puedes incluir aquí su equipo, rol, etc.)
    Route::get('perfil', [LoginUsuarioController::class, 'perfil']);

    // Refrescar token o validar sesión activa (opcional)
    Route::get('refresh-token', [LoginUsuarioController::class, 'refresh']);
});

use App\Http\Controllers\API\ResetContrasenaController;

// Solicitar enlace de restablecimiento
Route::post('forgot-password', [ResetContrasenaController::class, 'enviarLinkReset']);

// Validar token de reseteo (si usas un paso intermedio)
Route::post('validar-token', [ResetContrasenaController::class, 'validarToken'])->name('password.token');

// Restablecer la contraseña con nuevo password
Route::post('reset-password', [ResetContrasenaController::class, 'reset']);
