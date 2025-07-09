<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goles_jugadores', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->foreignId('encuentro_id')->constrained('encuentros')->onDelete('cascade');

            // Número de goles anotados (mínimo 1, sin negativos)
            $table->unsignedInteger('cantidad')->default(1);

            $table->timestamps();

            // ✅ Solo un registro por jugador en cada encuentro
            $table->unique(['jugador_id', 'encuentro_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goles_jugadores');
    }
};
