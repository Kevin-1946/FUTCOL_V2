<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    // ⚠️ Quitamos 'sedes' de fillable porque NO es columna de la tabla 'torneos'
    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'organizador',
        'precio',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    // Relación: un torneo tiene muchas sedes (Sede.torneo_id)
    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function recibosDePago()
    {
        return $this->hasMany(ReciboDePago::class);
    }

    public function encuentros()
    {
        return $this->hasMany(Encuentro::class);
    }
}
