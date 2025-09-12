<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="FUTCOL API",
 *     version="1.0.0",
 *     description="API para el sistema de gestión de torneos de fútbol FUTCOL. Esta API permite gestionar torneos, equipos, jugadores, amonestaciones y estadísticas.",
 *     @OA\Contact(
 *         email="admin@futcol.com",
 *         name="FUTCOL Support Team"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="FUTCOL API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingresa tu token JWT en el formato: Bearer {token}"
 * )
 *
 * @OA\Tag(
 *     name="Amonestaciones",
 *     description="Operaciones relacionadas con amonestaciones de jugadores"
 * )
 * 
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Operaciones de login y registro"
 * )
 * 
 * @OA\Tag(
 *     name="Encuentros",
 *     description="Gestión de encuentros/partidos"
 * )
 * 
 * @OA\Tag(
 *     name="Equipos",
 *     description="Gestión de equipos"
 * )
 * 
 * @OA\Tag(
 *     name="Estadísticas",
 *     description="Estadísticas de equipos y jugadores"
 * )
 * 
 * @OA\Tag(
 *     name="Goleadores",
 *     description="Información sobre goleadores"
 * )
 * 
 * @OA\Tag(
 *     name="Inscripciones",
 *     description="Gestión de inscripciones"
 * )
 * 
 * @OA\Tag(
 *     name="Jueces",
 *     description="Gestión de jueces/árbitros"
 * )
 * 
 * @OA\Tag(
 *     name="Jugadores",
 *     description="Gestión de jugadores"
 * )
 * 
 * @OA\Tag(
 *     name="Recibo de Pago",
 *     description="Gestión de pagos"
 * )
 * 
 * @OA\Tag(
 *     name="Sedes",
 *     description="Gestión de sedes"
 * )
 * 
 * @OA\Tag(
 *     name="Torneos",
 *     description="Gestión de torneos"
 * )
 */
class ApiDocumentationController extends Controller
{
    // Este controlador solo existe para contener la documentación base de la API
    // No necesita métodos, solo las anotaciones OpenAPI
}