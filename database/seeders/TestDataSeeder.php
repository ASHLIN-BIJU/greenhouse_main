<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Greenhouse;
use App\Models\GreenhouseSetting;
use App\Models\SensorReading;
use App\Models\RegisteredProduct;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Registered Products exist
        $products = [
            ['product_id' => 'GH-908712', 'status' => 'unused'],
            ['product_id' => 'GH-112233', 'status' => 'unused'],
            ['product_id' => 'GH-999999', 'status' => 'unused'],
        ];

        foreach ($products as $p) {
            RegisteredProduct::firstOrCreate(['product_id' => $p['product_id']], ['status' => $p['status']]);
        }

        // 2. Define Users
        $users = [
            [
                'name' => 'Ashlin Biju',
                'email' => 'ashlin@example.com',
                'password' => Hash::make('Password123!'),
                'greenhouse' => [
                    'name' => "Ashlin's Smart Greenhouse",
                    'product_id' => 'GH-908712',
                    'location' => 'Kottayam',
                    'temp' => 28.5, 'hum' => 65, 'soil' => 50
                ]
            ],
            [
                'name' => 'Bob Miller',
                'email' => 'bob@example.com',
                'password' => Hash::make('Password123!'),
                'greenhouse' => [
                    'name' => "Bob's Mini Garden",
                    'product_id' => 'GH-112233',
                    'location' => 'Kochi',
                    'temp' => 32.0, 'hum' => 75, 'soil' => 40
                ]
            ],
            [
                'name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'password' => Hash::make('Password123!'),
                'greenhouse' => [
                    'name' => "Charlie's Urban Farm",
                    'product_id' => 'GH-999999',
                    'location' => 'Kochi',
                    'temp' => 25.0, 'hum' => 60, 'soil' => 55
                ]
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                ['name' => $userData['name'], 'password' => $userData['password']]
            );

            // Create Greenhouse if doesn't exist
            $ghData = $userData['greenhouse'];
            $greenhouse = Greenhouse::firstOrCreate(
                ['product_id' => $ghData['product_id']],
                [
                    'user_id' => $user->id,
                    'name' => $ghData['name'],
                    'location' => $ghData['location']
                ]
            );

            // Mark product as used
            RegisteredProduct::where('product_id', $ghData['product_id'])->update(['status' => 'used']);

            // Create Settings
            GreenhouseSetting::firstOrCreate(
                ['greenhouse_id' => $greenhouse->id],
                [
                    'temperature_limit' => 30.0,
                    'humidity_limit' => 70.0,
                    'soil_moisture_limit' => 30.0,
                    'control_mode' => 'auto'
                ]
            );

            // Create Initial Reading
            SensorReading::create([
                'device_id' => $ghData['product_id'],
                'temperature' => $ghData['temp'],
                'humidity' => $ghData['hum'],
                'soil_moisture' => $ghData['soil'],
            ]);
        }
    }
}
