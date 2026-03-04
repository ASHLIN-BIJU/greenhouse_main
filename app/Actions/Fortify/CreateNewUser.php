<?php

namespace App\Actions\Fortify;

use App\Models\Address;
use App\Models\GreenhouseSetting;
use App\Models\RegisteredProduct;
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

            // Store address
            Address::create([
                'user_id' => $user->id,
                'address' => $input['address'],
            ]);

            // Fill default tables/settings
            GreenhouseSetting::create([
                'user_id' => $user->id,
                'temperature_limit' => 30,
                'humidity_limit' => 70,
            ]);

            return $user;
        });
    }
}
