<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            
            // Relación con torneo
            $table->foreignId('torneo_id')->nullable()->constrained('torneos')->onDelete('cascade');
            
            // NO incluir capitan_id aquí - se agregará después
            
            $table->timestamps();
            
            // Restricción: No se puede repetir nombre dentro del mismo torneo
            $table->unique(['nombre', 'torneo_id'], 'nombre_torneo_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};