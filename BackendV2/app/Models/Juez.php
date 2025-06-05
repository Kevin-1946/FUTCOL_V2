<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juez extends Model
{
    use HasFactory;

    protected $table = 'jueces';

    protected $fillable = [
        'nombre',
        'numero_de_contacto',
        'correo',
        'sede_asignada',
    ];

    // Si quieres, aquí podrías definir relaciones si la tabla 'sede_asignada' es una clave foránea
    // Por ejemplo, si 'sede_asignada' es el id de una sede, lo ideal sería cambiarlo a foreignId y relacionar:
    /*
    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_asignada');
    }
    */
}
