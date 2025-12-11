<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'password',   // se guarda hasheado
        'equipo_id',  // nullable
        'user_id',
    ];

    protected $hidden = ['password'];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function equiposCapitaneados()
    {
        return $this->hasMany(Equipo::class, 'capitan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}