<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();

            // Datos personales
            $table->string('tipo_documento');
            $table->string('numero_documento')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->integer('edad')->nullable();
            $table->string('genero')->nullable();
            $table->string('email')->unique();
            $table->string('password'); // recuerda cifrar la contraseña en el modelo/controlador

            // Detalles suscripción
            $table->string('tipo_torneo')->nullable(); // Podrías normalizar esto en tabla torneos si quieres
            $table->string('forma_pago')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};