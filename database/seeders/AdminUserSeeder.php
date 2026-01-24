<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@quality.com')],
            [
                'name' => env('ADMIN_NAME', 'Administrador'),
                'email' => env('ADMIN_EMAIL', 'admin@quality.com'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'phone' => env('ADMIN_PHONE', '+593987654321'),
                'address' => env('ADMIN_ADDRESS', 'Av. Principal 123'),
                'province' => env('ADMIN_PROVINCE', 'Guayas'),
                'city' => env('ADMIN_CITY', 'Guayaquil'),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
                'oauth_provider' => 'local',
            ]
        );

        // Crear usuario dueño
        User::firstOrCreate(
            ['email' => 'owner@quality.com'],
            [
                'name' => 'Dueño de Calidad',
                'email' => 'owner@quality.com',
                'password' => Hash::make('owner123'),
                'phone' => '+593987654322',
                'address' => 'Av. Secundaria 456',
                'province' => 'Pichincha',
                'city' => 'Quito',
                'role' => User::ROLE_OWNER,
                'email_verified_at' => now(),
                'oauth_provider' => 'local',
            ]
        );

        // Crear usuario cliente
        User::firstOrCreate(
            ['email' => 'client@quality.com'],
            [
                'name' => 'Cliente de Calidad',
                'email' => 'client@quality.com',
                'password' => Hash::make('client123'),
                'phone' => '+593987654324',
                'address' => 'Av. Cuarta 101',
                'province' => 'Manabí',
                'city' => 'Manta',
                'role' => User::ROLE_CLIENT,
                'email_verified_at' => now(),
                'oauth_provider' => 'local',
            ]
        );
    }
}
