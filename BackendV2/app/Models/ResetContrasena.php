<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetContrasena extends Model
{
    public $timestamps = false; // Porque solo usas `created_at` y no `updated_at`

    protected $table = 'reset_contrasena'; // Especificamos el nombre personalizado de la tabla

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}