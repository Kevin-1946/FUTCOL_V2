<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // NO usar truncate() - usar firstOrCreate para evitar duplicados
        Role::firstOrCreate(['nombre' => 'admin']);
        Role::firstOrCreate(['nombre' => 'capitan']);
        Role::firstOrCreate(['nombre' => 'participante']);
    }
}