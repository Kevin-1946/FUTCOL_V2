<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jueces', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('numero_de_contacto');
            $table->string('correo')->nullable();
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jueces');
    }
};