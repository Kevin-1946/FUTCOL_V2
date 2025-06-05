<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');

            $table->date('fecha_de_inscripcion');
            $table->string('forma_pago')->nullable(); // efectivo, transferencia, etc.
            $table->enum('estado_pago', ['pendiente', 'pagado'])->default('pendiente');
            $table->boolean('correo_confirmado')->default(false);
            $table->decimal('total_pagado', 8, 2)->nullable();

            $table->timestamps();

            $table->unique(['equipo_id', 'torneo_id']); // no se puede inscribir dos veces
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};