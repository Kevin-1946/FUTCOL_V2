<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Usar nombres con mayúscula inicial
        Role::firstOrCreate(['nombre' => 'Administrador']);
        Role::firstOrCreate(['nombre' => 'Capitan']);
        Role::firstOrCreate(['nombre' => 'Participante']);
    }
}   