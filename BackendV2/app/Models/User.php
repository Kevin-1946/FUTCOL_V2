<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'equipo_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con el rol del usuario.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación con el equipo al que pertenece (como jugador).
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    /**
     * Relación con los equipos que capitanea.
     * Un usuario (capitán) puede capitanear uno o más equipos.
     */
    public function equiposCapitaneados()
    {
        return $this->hasMany(Equipo::class, 'capitan_id');
    }
}