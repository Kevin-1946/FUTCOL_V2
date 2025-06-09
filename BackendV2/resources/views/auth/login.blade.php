@extends('layouts.app')

@section('title', 'Iniciar Sesión - FUTCOL')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0">
                <div class="card-body p-5">
                    <!-- Logo/Título -->
                    <div class="text-center mb-4">
                        <i class="fas fa-futbol fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold text-dark">FUTCOL</h2>
                        <p class="text-muted">Sistema de Gestión de Torneos</p>
                    </div>

                    <!-- Formulario de Login -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Correo Electrónico
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="admin@torneo.com">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Botón de Login -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <!-- Credenciales de Prueba -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold text-muted">Credenciales de Prueba:</h6>
                        <small class="text-muted">
                            <strong>Admin:</strong> admin@torneo.com / admin123<br>
                            <strong>Capitán:</strong> capitan@torneo.com / capitan123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection