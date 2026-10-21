<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Usuarios de prueba para la demo de la sala de chat
        User::create([
            'name' => 'Lia Alumno',
            'email' => 'lia@lia.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Compañero Demo',
            'email' => 'demo@lia.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
