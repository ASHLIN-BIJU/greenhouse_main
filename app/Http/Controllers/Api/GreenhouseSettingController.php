<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Greenhouse;
use App\Models\GreenhouseSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GreenhouseSettingController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'temperature_limit' => 'nullable|numeric',
            'humidity_limit' => 'nullable|numeric',
            'soil_moisture_limit' => 'nullable|numeric',
            'control_mode' => 'nullable|in:auto,manual',
        ]);

        $user = Auth::user();

        // Find the user's greenhouse
        $greenhouse = Greenhouse::where('user_id', $user->id)->first();

        if (!$greenhouse) {
            return response()->json(['message' => 'Greenhouse not found for this user'], 404);
        }

        // Update settings
        $settings = GreenhouseSetting::updateOrCreate(
            ['greenhouse_id' => $greenhouse->id],
            $validated
        );

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => $settings
        ]);
    }
}
