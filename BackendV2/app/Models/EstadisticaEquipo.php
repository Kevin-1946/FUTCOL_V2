<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadisticaEquipo extends Model
{
    protected $table = 'estadisticas_equipos';

    protected $fillable = [
        'equipo_id',
        'torneo_id',
        'partidos_jugados',
        'partidos_ganados',
        'partidos_empatados',
        'partidos_perdidos',
        'goles_a_favor',
        'goles_en_contra',
        'diferencia_de_goles',
        'puntos',
    ];

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class);
    }

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Torneo::class);
    }
}