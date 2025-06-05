<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'torneo_id',
        'capitan_id',
    ];

    // Un equipo pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    // Un equipo tiene muchos jugadores
    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }

    // Capitán del equipo (opcional)
    public function capitan()
    {
        return $this->belongsTo(Jugador::class, 'capitan_id');
    }

    // Encuentros donde el equipo es local
    public function encuentrosLocal()
    {
        return $this->hasMany(Encuentro::class, 'equipo_local_id');
    }

    // Encuentros donde el equipo es visitante
    public function encuentrosVisitante()
    {
        return $this->hasMany(Encuentro::class, 'equipo_visitante_id');
    }
}