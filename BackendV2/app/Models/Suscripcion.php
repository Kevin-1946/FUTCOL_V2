<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    use HasFactory;

    protected $fillable = [
        'torneo_id',
        'equipo_id',
        'fecha_suscripcion',
        'estado',
    ];

    // Relación: La suscripción pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    // Relación: La suscripción pertenece a un equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    // Relación: Una suscripción puede tener muchos recibos de pago
    public function recibos()
    {
        return $this->hasMany(ReciboDePago::class);
    }
}
