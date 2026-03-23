<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user = auth('sanctum')->user();

        // Load the address relationship
        $user->load('address');

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function getProfileDetails()
    {
        $user = auth('sanctum')->user();

        // Load the address relationship
        $user->load('address');
        $addressInfo = $user->address;
        $fullAddress = $addressInfo ? "{$addressInfo->address}, {$addressInfo->city}, {$addressInfo->state}, {$addressInfo->pincode}" : 'No registered address';

        $greenhouse = \App\Models\Greenhouse::where('user_id', $user->id)->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user->name,
                'email' => $user->email,
                'greenhouse_address' => $fullAddress
            ]
        ]);

        
    }
}

