<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Jugador extends Authenticatable
{
    use HasFactory;

    protected $table = 'jugadores';

    protected $fillable = [
        'nombre',
        'n_documento',
        'fecha_nacimiento',
        'genero',
        'edad',
        'email',
        'password',
        'equipo_id',
        'user_id',
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

    // ✅ Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}