<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            MaterialSeeder::class,
            ColorSeeder::class,
            ColorMaterialSeeder::class,
            CategoryMaterialColorSeeder::class,
            ProductSeeder::class,
            CostSettingsSeeder::class,
        ]);

        // Crear usuarios de prueba adicionales si es necesario
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
