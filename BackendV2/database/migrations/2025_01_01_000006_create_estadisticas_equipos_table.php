<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estadisticas_equipos', function (Blueprint $table) {
            $table->id();

            // Relaciones corregidas
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');

            // Estadísticas
            $table->integer('partidos_jugados')->default(0);
            $table->integer('partidos_ganados')->default(0);
            $table->integer('partidos_empatados')->default(0);
            $table->integer('partidos_perdidos')->default(0);

            $table->integer('goles_a_favor')->default(0);
            $table->integer('goles_en_contra')->default(0);
            $table->integer('diferencia_de_goles')->default(0);
            $table->integer('puntos')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estadisticas_equipos');
    }
};
