<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'torneo_id',
    ];

    // Relación: una sede pertenece a un torneo
    public function torneo()
    {
        return $this->belongsTo(Torneo::class);
    }

    // Relación: una sede tiene muchos encuentros
    public function encuentros()
    {
        return $this->hasMany(Encuentro::class);
    }
}
