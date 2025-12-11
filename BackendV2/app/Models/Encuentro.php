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
        'modalidad',          // <-- añadido si lo usas en el form
        'fecha',
        'hora',
        'equipo_local_id',
        'equipo_visitante_id',
        'goles_local',
        'goles_visitante',
    ];

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
}