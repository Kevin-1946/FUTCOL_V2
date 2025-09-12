@extends('layouts.app')

@section('title', 'Dashboard - FUTCOL')

@section('content')
<div class="container">
    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-2">
                                <i class="fas fa-tachometer-alt text-primary me-3"></i>
                                Bienvenido, {{ $user->name }}!
                            </h1>
                            <p class="text-muted mb-0">
                                Rol: <span class="badge bg-primary">{{ $user->role->nombre }}</span>
                                | Email: {{ $user->email }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-user-circle fa-4x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido según el Rol -->
    @if($user->role->nombre === 'administrador')
        <!-- Panel de Administrador -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5>Gestionar Usuarios</h5>
                        <p class="text-muted">Administrar usuarios del sistema</p>
                        <a href="#" class="btn btn-outline-primary">Ver Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-trophy fa-3x text-success mb-3"></i>
                        <h5>Torneos</h5>
                        <p class="text-muted">Gestionar torneos activos</p>
                        <a href="#" class="btn btn-outline-success">Ver Torneos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                        <h5>Equipos</h5>
                        <p class="text-muted">Administrar equipos</p>
                        <a href="#" class="btn btn-outline-warning">Ver Equipos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                        <h5>Reportes</h5>
                        <p class="text-muted">Estadísticas del sistema</p>
                        <a href="#" class="btn btn-outline-info">Ver Reportes</a>
                    </div>
                </div>
            </div>
        </div>

    @elseif($user->role->nombre === 'capitan')
        <!-- Panel de Capitán -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-users-cog fa-3x text-primary mb-3"></i>
                        <h5>Mi Equipo</h5>
                        <p class="text-muted">Gestionar jugadores de tu equipo</p>
                        <a href="#" class="btn btn-outline-primary">Ver Equipo</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                        <h5>Mis Partidos</h5>
                        <p class="text-muted">Ver calendario de partidos</p>
                        <a href="#" class="btn btn-outline-success">Ver Partidos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-medal fa-3x text-warning mb-3"></i>
                        <h5>Estadísticas</h5>
                        <p class="text-muted">Rendimiento del equipo</p>
                        <a href="#" class="btn btn-outline-warning">Ver Stats</a>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Panel de Participante -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                        <h5>Ver Torneos</h5>
                        <p class="text-muted">Explora los torneos disponibles</p>
                        <a href="#" class="btn btn-outline-primary">Ver Torneos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card text-center border-0 h-100">
                    <div class="card-body">
                        <i class="fas fa-calendar fa-3x text-success mb-3"></i>
                        <h5>Próximos Partidos</h5>
                        <p class="text-muted">Calendario de partidos</p>
                        <a href="#" class="btn btn-outline-success">Ver Calendario</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Panel de Información -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Estado del Sistema</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h3 class="text-primary">5</h3>
                            <p class="text-muted">Torneos Activos</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-success">24</h3>
                            <p class="text-muted">Equipos Registrados</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">156</h3>
                            <p class="text-muted">Jugadores</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-info">42</h3>
                            <p class="text-muted">Partidos Jugados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection