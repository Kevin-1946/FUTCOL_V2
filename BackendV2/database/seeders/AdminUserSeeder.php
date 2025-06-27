<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // ✅ CORREGIDO: Buscar 'admin' en lugar de 'administrador'
        $adminRole = Role::where('nombre', 'Administrador')->first();
        $capitanRole = Role::where('nombre', 'Capitan')->first();

        // ✅ Solo crear si NO existe
        if (!User::where('email', 'admin@torneo.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@torneo.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]);
            $this->command->info('✅ Admin creado: admin@torneo.com / admin123');
        }

        if (!User::where('email', 'capitan@torneo.com')->exists()) {
            User::create([
                'name' => 'Capitán Prueba',
                'email' => 'capitan@torneo.com',
                'password' => Hash::make('capitan123'),
                'role_id' => $capitanRole->id,
                'email_verified_at' => now(),
            ]);
            $this->command->info('✅ Capitán creado: capitan@torneo.com / capitan123');
        }
    }
}