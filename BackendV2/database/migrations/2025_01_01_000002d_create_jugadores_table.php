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
            $table->string('genero');
            $table->integer('edad');
            
            // Relación opcional con la tabla 'users'
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // NO incluir equipo_id aquí - se agregará después
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};