<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegisteredProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RegisteredProduct::create([
            'product_id' => 'GH-908712',
            'status' => 'unused',
        ]);

        \App\Models\RegisteredProduct::create([
            'product_id' => 'GH-908713',
            'status' => 'used',
        ]);

        \App\Models\RegisteredProduct::create([
            'product_id' => 'GH-TEST-123',
            'status' => 'unused',
        ]);
    }
}
