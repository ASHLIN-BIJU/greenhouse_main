<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorReading;
use App\Models\Greenhouse;
use App\Models\Alert;
use App\Events\SensorDataUpdated;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    public function store(Request $request, \App\Services\SensorDataService $sensorDataService)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'soil_moisture' => 'required|numeric',
        ]);

        $result = $sensorDataService->process($validated);

        return response()->json([
            'message' => $result['stored'] ? 'Sensor data stored' : 'Data unchanged',
            'stored' => $result['stored'],
            'area_temperature' => $result['automation']['area_temperature'] ?? null,
            'data' => $result['data']
        ]);
    }
}
