<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('jugadores', function (Blueprint $table) {
        $table->string('genero')->nullable();
        $table->integer('edad')->nullable();
    });
}

public function down()
{
    Schema::table('jugadores', function (Blueprint $table) {
        $table->dropColumn('genero');
        $table->dropColumn('edad');
    });
}
};
