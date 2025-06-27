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
            $table->foreignId('torneo_id')->nullable()->constrained('torneos')->onDelete('cascade');
            $table->foreignId('capitan_id')->nullable()->constrained('jugadores')->onDelete('set null');
            $table->timestamps();

            $table->unique(['nombre', 'torneo_id']); // un mismo nombre no puede repetirse en un torneo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};