<?php

namespace App\Actions\Fortify;

use App\Models\Address;
use App\Models\Greenhouse;
use App\Models\GreenhouseSetting;
use App\Models\RegisteredProduct;
use App\Models\Sensor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'product_id' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:20'],
            'greenhouse_name' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($input) {
            $product = RegisteredProduct::where('product_id', $input['product_id'])->first();

            if (!$product) {
                $validator->errors()->add('product_id', 'Invalid product_id. Please check it.');
            } elseif ($product->status === 'used') {
                $validator->errors()->add('product_id', 'Product already registered.');
            }
        });

        $validator->validate();

        return DB::transaction(function () use ($input) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            // Update product status
            RegisteredProduct::where('product_id', $input['product_id'])
                ->update(['status' => 'used']);

            // Create Greenhouse unit
            $greenhouse = Greenhouse::create([
                'user_id' => $user->id,
                'product_id' => $input['product_id'],
                'name' => $input['greenhouse_name'] ?? 'My Greenhouse',
                'location' => $input['city'] ?? null,
            ]);

            // Store address
            Address::create([
                'user_id' => $user->id,
                'address' => $input['address'],
                'city' => $input['city'] ?? null,
                'state' => $input['state'] ?? null,
                'pincode' => $input['pincode'],
            ]);

            // Initialize default sensors for this greenhouse
            $sensors = [
                ['sensor_type' => 'temperature', 'unit' => '°C'],
                ['sensor_type' => 'humidity', 'unit' => '%'],
                ['sensor_type' => 'soil_moisture', 'unit' => '%'],
            ];

            foreach ($sensors as $sensorData) {
                Sensor::create([
                    'greenhouse_id' => $greenhouse->id,
                    'sensor_type' => $sensorData['sensor_type'],
                    'unit' => $sensorData['unit'],
                ]);
            }

            // Fill default unit settings
            GreenhouseSetting::create([
                'greenhouse_id' => $greenhouse->id,
                'temperature_limit' => 30,
                'humidity_limit' => 70,
            ]);

            return $user;
        });
    }
}
