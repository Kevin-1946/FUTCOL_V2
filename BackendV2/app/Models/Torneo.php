<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

<<<<<<< HEAD
=======
    // ⚠️ Quitamos 'sedes' de fillable porque NO es columna de la tabla 'torneos'
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'organizador',
<<<<<<< HEAD
        'precio', 
        'sedes'
    ];

    // Un torneo tiene muchos equipos
=======
        'precio',
    ];

>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

<<<<<<< HEAD
    // Un torneo tiene muchas sedes
=======
    // Relación: un torneo tiene muchas sedes (Sede.torneo_id)
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

<<<<<<< HEAD
    // Un torneo tiene muchas suscripciones
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

<<<<<<< HEAD
    // Un torneo tiene muchos recibos de pago
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function recibosDePago()
    {
        return $this->hasMany(ReciboDePago::class);
    }

<<<<<<< HEAD
    // Si los encuentros están ligados directamente al torneo
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function encuentros()
    {
        return $this->hasMany(Encuentro::class);
    }
}
