<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sedes', function (Blueprint $table) {
<<<<<<< HEAD
        $table->id();
        $table->string('nombre');
        $table->string('direccion');
        $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
        $table->timestamps();
});
=======
            $table->id();
            $table->string('nombre');
            $table->string('direccion');

            // torneo_id opcional y si se borra el torneo, queda en NULL
            $table->foreignId('torneo_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->timestamps();
        });
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    }

    public function down(): void
    {
        Schema::dropIfExists('sedes');
    }
};
