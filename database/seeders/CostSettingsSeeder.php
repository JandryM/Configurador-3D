<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CostSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Global cost settings
        DB::table('global_cost_settings')->insert([
            'indirect_cost_percentage' => 12.50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Product cost settings (ejemplo para 3 productos)
        $products = DB::table('products')->take(3)->pluck('id');
        foreach ($products as $i => $productId) {
            DB::table('product_cost_settings')->insert([
                'product_id' => $productId,
                'direct_cost_percentage' => 20.00 + $i * 5,
                'notes' => 'Configuración de ejemplo para producto ' . $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
