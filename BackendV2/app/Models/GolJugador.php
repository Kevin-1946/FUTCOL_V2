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

    public function jugador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class);
    }

    public function encuentro(): BelongsTo
    {
        return $this->belongsTo(Encuentro::class);
    }
}
