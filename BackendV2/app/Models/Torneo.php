<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'organizador',
        'precio', 
        'sedes'
    ];

    // Un torneo tiene muchos equipos
    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    // Un torneo tiene muchas sedes
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    // Un torneo tiene muchas suscripciones
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    // Un torneo tiene muchos recibos de pago
    public function recibosDePago()
    {
        return $this->hasMany(ReciboDePago::class);
    }

    // Si los encuentros están ligados directamente al torneo
    public function encuentros()
    {
        return $this->hasMany(Encuentro::class);
    }
}
