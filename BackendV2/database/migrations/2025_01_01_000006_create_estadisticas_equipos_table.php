<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estadisticas_equipos', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');

            // Estadísticas (valores no negativos)
            $table->unsignedInteger('partidos_jugados')->default(0);
            $table->unsignedInteger('partidos_ganados')->default(0);
            $table->unsignedInteger('partidos_empatados')->default(0);
            $table->unsignedInteger('partidos_perdidos')->default(0);

            $table->unsignedInteger('goles_a_favor')->default(0);
            $table->unsignedInteger('goles_en_contra')->default(0);
            $table->integer('diferencia_de_goles')->default(0); // este sí puede ser negativo
            $table->unsignedInteger('puntos')->default(0);

            $table->timestamps();

            // Evitar duplicados por equipo en el mismo torneo
            $table->unique(['equipo_id', 'torneo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_equipos');
    }
};