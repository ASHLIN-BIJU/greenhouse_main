<?php

namespace App\Services;

use App\Models\SensorReading;
use App\Models\Greenhouse;
use App\Models\Alert;
use App\Events\SensorDataUpdated;
use App\Events\ControlUpdated;

class SensorDataService
{
    /**
     * Process incoming sensor data.
     * 
     * @param array $data ['device_id', 'temperature', 'humidity', 'soil_moisture']
     * @return array
     */
    public function process(array $data)
    {
        // 1. Fetch the latest reading for this device
        $latest = SensorReading::where('device_id', $data['device_id'])
            ->latest()
            ->first();

        // 2. Check if data has changed
        $hasChanged = !$latest ||
            round((float) $latest->temperature, 2) !== round((float) $data['temperature'], 2) ||
            round((float) $latest->humidity, 2) !== round((float) $data['humidity'], 2) ||
            round((float) $latest->soil_moisture, 2) !== round((float) $data['soil_moisture'], 2);

        $stored = false;
        $alertIds = [];

        if ($hasChanged) {
            SensorReading::create($data);
            $stored = true;

            $greenhouse = Greenhouse::where('product_id', $data['device_id'])->first();
            if ($greenhouse && $greenhouse->settings) {
                $settings = $greenhouse->settings;
                $alerts = [];

                if ($data['temperature'] > $settings->temperature_limit) {
                    $alerts[] = ['message' => "High Temperature Alert: {$data['temperature']}°C", 'level' => 'warning'];
                }

                if ($data['humidity'] > $settings->humidity_limit) {
                    $alerts[] = ['message' => "High Humidity Alert: {$data['humidity']}%", 'level' => 'warning'];
                }

                if ($data['soil_moisture'] < $settings->soil_moisture_limit) {
                    $alerts[] = ['message' => "Low Soil Moisture Alert: {$data['soil_moisture']}%", 'level' => 'warning'];
                }

                foreach ($alerts as $alertData) {
                    $newAlert = Alert::create([
                        'greenhouse_id' => $greenhouse->id,
                        'message' => $alertData['message'],
                        'level' => $alertData['level'],
                    ]);
                    $alertIds[] = $newAlert->id;
                }

                // Auto Control Logic
                if ($settings->control_mode === 'auto') {
                    $pumpMode = $data['soil_moisture'] < $settings->soil_moisture_limit;
                    $acMode = $data['temperature'] > $settings->temperature_limit;
                    $exhaustMode = $data['temperature'] > $settings->temperature_limit ||
                        $data['humidity'] > $settings->humidity_limit;

                    ControlUpdated::dispatch(
                        $data['device_id'],
                        $pumpMode,
                        $exhaustMode,
                        $acMode
                    );
                }
            }
        }

        // Always broadcast for real-time dashboard updates
        SensorDataUpdated::dispatch(
            $data['device_id'],
            (float) $data['temperature'],
            (float) $data['humidity'],
            (float) $data['soil_moisture']
        );

        return [
            'stored' => $stored,
            'alert_ids' => $alertIds,
            'data' => $data
        ];
    }
}
