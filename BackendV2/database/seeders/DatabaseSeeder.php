<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Orden correcto: primero roles, luego usuarios
        $this->call([
            RolesTableSeeder::class,
            AdminUserSeeder::class,
        ]);
        
        $this->command->info('🎯 Seeders ejecutados correctamente');
    }
}