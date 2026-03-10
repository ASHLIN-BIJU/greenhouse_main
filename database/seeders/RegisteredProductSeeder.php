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
        $products = [
            ['product_id' => 'GH-908712', 'status' => 'unused'],
            ['product_id' => 'GH-908713', 'status' => 'used'],
            ['product_id' => 'GH-TEST-123', 'status' => 'unused'],
            ['product_id' => 'GH-112233', 'status' => 'unused'],
            ['product_id' => 'GH-999999', 'status' => 'unused'],
        ];

        foreach ($products as $product) {
            \App\Models\RegisteredProduct::firstOrCreate(
                ['product_id' => $product['product_id']],
                ['status' => $product['status']]
            );
        }
    }
}
