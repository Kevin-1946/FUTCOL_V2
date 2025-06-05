<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class LoginUsuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'login_usuarios';

    protected $fillable = [
        'jugador_id',
        'ip',
        'user_agent',
        'exitoso',
        'fecha_login',
    ];


    // Relación: este login pertenece a un jugador
    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }
}
