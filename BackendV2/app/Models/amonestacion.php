<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amonestacion extends Model
{
    use HasFactory;

    protected $table = 'amonestaciones';

    protected $fillable = [
        'jugador_id',
        'equipo_id',
        'encuentro_id',
        'numero_camiseta',
        'tarjeta_roja',
        'tarjeta_amarilla',
        'tarjeta_azul',
    ];

    // Relaciones

    public function jugador()
    {
        return $this->belongsTo(Jugador::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function encuentro()
    {
        return $this->belongsTo(Encuentro::class);
    }
}
