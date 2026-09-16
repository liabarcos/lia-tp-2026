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
        User::create([
            'name' => 'Admin Docente',
            'email' => 'admin@lia.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Lia Alumno',
            'email' => 'lia@lia.com',
            'password' => bcrypt('password'),
            'phone' => '1155551234',
            'professional_url' => 'https://linkedin.com/in/lia',
            'email_verified_at' => now(),
        ]);
    }
}
