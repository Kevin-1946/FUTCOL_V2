<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboDePago extends Model
{
    use HasFactory;

    protected $table = 'recibos_de_pago';

    protected $fillable = [
        'inscripcion_id',
        'torneo_id',
        'monto',
        'fecha_emision',
        'confirmado',
        'metodo_pago',
        'numero_comprobante',
    ];

    // Relación: El recibo pertenece a una suscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    // Relación: El recibo pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
