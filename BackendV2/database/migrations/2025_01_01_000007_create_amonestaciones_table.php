<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amonestaciones', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('jugador_id')->constrained('jugadores')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos')->onDelete('cascade');
            $table->foreignId('encuentro_id')->constrained('encuentros')->onDelete('cascade');

            // Detalles
            $table->unsignedTinyInteger('numero_camiseta')->nullable(); // opcional si ya está en jugador
            $table->boolean('tarjeta_roja')->default(false);
            $table->boolean('tarjeta_amarilla')->default(false);
            $table->boolean('tarjeta_azul')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amonestaciones');
    }
};
