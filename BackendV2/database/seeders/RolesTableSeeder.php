<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['id' => 1, 'nombre' => 'admin']);
        Role::create(['id' => 2, 'nombre' => 'jugador']);
    }
}