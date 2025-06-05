<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecuta el seeder de roles
        $this->call([
            RolesTableSeeder::class,
        ]);

        // Crea un usuario de prueba con rol de admin (role_id = 1)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), //Contraseña Password hasheado
            'role_id' => 1, // Admin
        ]);
    }
}