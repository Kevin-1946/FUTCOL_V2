<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recibos_de_pago', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('inscripcion_id')->constrained('inscripciones')->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained('torneos')->onDelete('cascade');

            // Información del pago
            $table->decimal('monto', 10, 2);
            $table->date('fecha_emision');
            $table->boolean('confirmado')->default(false);
            $table->string('metodo_pago')->nullable();
            $table->string('numero_comprobante')->nullable();

            $table->timestamps();

            // Índice único con nombre corto para evitar error
            $table->unique(['inscripcion_id', 'torneo_id', 'numero_comprobante'], 'recibo_pago_unique_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibos_de_pago');
    }
};
