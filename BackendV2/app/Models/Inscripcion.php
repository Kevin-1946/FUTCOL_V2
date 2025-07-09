<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $fillable = [
        'equipo_id',
        'torneo_id',
        'fecha_de_inscripcion',
        'forma_pago',
        
    ];

    // Relaciones

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }
}
