<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jugadores', function (Blueprint $table) {
            // ✅ Asegura que la columna exista (por si no fue creada antes)
            if (!Schema::hasColumn('jugadores', 'equipo_id')) {
                $table->unsignedBigInteger('equipo_id')->nullable()->after('user_id');
            }

            // ✅ Luego agrega la foreign key
            $table->foreign('equipo_id')
                  ->references('id')
                  ->on('equipos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jugadores', function (Blueprint $table) {
            $table->dropForeign(['equipo_id']);
            // ❓ Si la columna fue agregada aquí, también se puede eliminar:
            // $table->dropColumn('equipo_id');
        });
    }
};