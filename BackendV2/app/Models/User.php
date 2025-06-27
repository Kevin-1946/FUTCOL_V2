<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'equipo_id', // ⚠️ Asegúrate de que esta columna exista en la tabla users
    ];

    /**
     * Los atributos que deben ocultarse para arrays o respuestas JSON.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación con el rol del usuario.
     * Cada usuario pertenece a un solo rol.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación con el equipo.
     * Cada usuario puede pertenecer a un solo equipo.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}