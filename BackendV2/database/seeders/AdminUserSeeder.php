<?php

namespace Database\Seeders;  // ❌ FALTA este namespace

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;  // ❌ FALTA importar Role
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // ❌ ERROR: Usas 'role' pero tu tabla tiene 'role_id'
        // ✅ CORRECTO: Buscar el ID del rol
        $adminRole = Role::where('nombre', 'administrador')->first();
        $capitanRole = Role::where('nombre', 'capitan')->first();

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@torneo.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,  // ✅ Usar role_id
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Capitán Prueba',
            'email' => 'capitan@torneo.com', 
            'password' => Hash::make('capitan123'),
            'role_id' => $capitanRole->id,  // ✅ Usar role_id
            'email_verified_at' => now(),
        ]);
    }
}