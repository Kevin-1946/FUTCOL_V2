<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('categoria'); // no unique
            $table->enum('modalidad', ['liga', 'relampago', 'mixto', 'eliminacion_directa']);
            $table->string('organizador'); // no unique
            $table->decimal('precio', 8, 2)->default(0);
            $table->string('sedes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('torneos');
    }
};