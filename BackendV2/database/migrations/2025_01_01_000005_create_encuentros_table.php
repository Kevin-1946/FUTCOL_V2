<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('encuentros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');
            $table->foreignId('equipo_local_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('equipo_visitante_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('juez_id')->nullable()->constrained('jueces')->onDelete('set null');
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->onDelete('set null');

            $table->date('fecha');
            $table->time('hora');

            $table->unsignedTinyInteger('goles_local')->nullable();
            $table->unsignedTinyInteger('goles_visitante')->nullable();

            $table->enum('estado', ['programado', 'jugado', 'suspendido'])->default('programado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuentros');
    }
};