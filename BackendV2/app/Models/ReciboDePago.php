<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboDePago extends Model
{
    use HasFactory;

    protected $fillable = [
        'suscripcion_id',
        'torneo_id',
        'monto',
        'fecha_emision',
        'confirmado',
        'metodo_pago',
        'numero_comprobante',
    ];

    // Relación: El recibo pertenece a una suscripción
    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class);
    }

    // Relación: El recibo pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
