<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c

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
<<<<<<< HEAD
        'password',
        'equipo_id',
        'user_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relación: Un jugador pertenece a un equipo
=======
        'password',   // se guarda hasheado
        'equipo_id',  // nullable
        'user_id',
    ];

    protected $hidden = ['password'];

>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

<<<<<<< HEAD
    // Relación inversa: Un jugador puede ser capitán de uno o más equipos
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function equiposCapitaneados()
    {
        return $this->hasMany(Equipo::class, 'capitan_id');
    }

<<<<<<< HEAD
    // ✅ Relación con el modelo User
=======
>>>>>>> 0811bf220f286354eedfbe5dcd950a8cd31dba1c
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}