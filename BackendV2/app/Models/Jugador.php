<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Jugador extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'n_documento',
        'fecha_nacimiento',
        'email',
        'password',
        'equipo_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relación: Un jugador pertenece a un equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    // Relación inversa: Un jugador puede ser capitán de uno o más equipos
    public function equiposCapitaneados()
    {
        return $this->hasMany(Equipo::class, 'capitan_id');
    }
}
