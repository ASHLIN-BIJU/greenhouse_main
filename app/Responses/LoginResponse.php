<?php

namespace App\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'token' => $request->user()->createToken('greenhouse-token')->plainTextToken,
            'user' => $request->user()
        ]);
    }
}