<?php

namespace App\Services;

use App\Models\SensorReading;
use App\Models\Greenhouse;
use App\Models\Alert;
use App\Events\SensorDataUpdated;
use App\Events\ControlUpdated;

class SensorDataService
{
    use \App\Traits\HandlesAutomation;
 
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
        if ($hasChanged) {
            SensorReading::create($data);
            $stored = true;
        }

        // 3. Run Automation (calculates area temp, alerts, and broadcasts)
        $greenhouse = Greenhouse::where('product_id', $data['device_id'])->first();
        $automationResult = [];
        if ($greenhouse) {
            $automationResult = $this->runAutomation($greenhouse,$data);
        }

        // 4. Always broadcast for real-time dashboard updates
        SensorDataUpdated::dispatch(
            $data['device_id'],
            (float) $data['temperature'],
            (float) $data['humidity'],
            (float) $data['soil_moisture']
        );

        return [
            'stored' => $stored,
            'automation' => $automationResult,
            'data' => $data
        ];
    }
}
