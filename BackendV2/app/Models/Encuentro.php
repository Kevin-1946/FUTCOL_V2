<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuentro extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'sede_id',
        'fecha',
        'hora',
        'equipo_local_id',
        'equipo_visitante_id',
        'goles_local',
        'goles_visitante',
    ];

    // Relación: Un encuentro pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    // Relación: Un encuentro se juega en una sede
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    // Relación: Equipo local
    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    // Relación: Equipo visitante
    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
}