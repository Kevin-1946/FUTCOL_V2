<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('n_documento')->unique();
            $table->date('fecha_nacimiento');
            $table->string('email')->unique();
            $table->string('password');

            // 👇 Rol como cadena con mayúscula inicial
            $table->enum('role', ['Administrador', 'Capitan', 'Participante'])->default('Participante');
            
            // Relación opcional con User
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Relación opcional con Equipo
            //$table->foreignId('equipo_id')->nullable()->constrained('equipos')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};