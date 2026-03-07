<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Events\SensorDataUpdated;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
        ]);

        // Store data in database for telemetry history
        SensorReading::create($validated);

        SensorDataUpdated::dispatch(
            $validated['device_id'],
            (float) $validated['temperature'],
            (float) $validated['humidity'],
            (float) $validated['soil_moisture']
        );

        return response()->json(['message' => 'Sensor data stored and broadcasted successfully']);
    }
}
