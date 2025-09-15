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
<<<<<<< HEAD
=======
        'modalidad',          // <-- añadido si lo usas en el form
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
        'fecha',
        'hora',
        'equipo_local_id',
        'equipo_visitante_id',
        'goles_local',
        'goles_visitante',
    ];

<<<<<<< HEAD
    // Relación: Un encuentro pertenece a un torneo
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

<<<<<<< HEAD
    // Relación: Un encuentro se juega en una sede
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

<<<<<<< HEAD
    // Relación: Equipo local
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function equipoLocal()
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

<<<<<<< HEAD
    // Relación: Equipo visitante
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function equipoVisitante()
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }
}