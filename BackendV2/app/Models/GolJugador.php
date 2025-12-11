<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GolJugador extends Model
{
    protected $table = 'goles_jugadores';

    protected $fillable = [
        'jugador_id',
        'encuentro_id',
        'cantidad',
    ];

    // Asegura tipos correctos en la salida JSON
    protected $casts = [
        'jugador_id'   => 'int',
        'encuentro_id' => 'int',
        'cantidad'     => 'int',
    ];

    // 👇 Eager-load automático: jugador->equipo y encuentro
    protected $with = ['jugador.equipo', 'encuentro'];

    public function jugador(): BelongsTo
    {
        // clave explícita por claridad
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    public function encuentro(): BelongsTo
    {
        return $this->belongsTo(Encuentro::class, 'encuentro_id');
    }
}
