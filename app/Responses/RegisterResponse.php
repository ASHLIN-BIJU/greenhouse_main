<?php

namespace App\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'token' => $request->user()->createToken('greenhouse-token')->plainTextToken,
            'user' => $request->user()
        ], 201);
    }
}
