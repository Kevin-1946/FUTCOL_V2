<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('goles_jugadores', function (Blueprint $table) {
            $table->id();

            // Relaciones corregidas
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->foreignId('encuentro_id')->constrained('encuentros')->onDelete('cascade');

            // Estadística
            $table->unsignedInteger('cantidad')->default(1); // Número de goles marcados

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goles_jugadores');
    }
};