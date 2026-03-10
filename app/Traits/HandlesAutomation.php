<?php

namespace App\Traits;

use App\Models\Greenhouse;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Events\ControlUpdated;
use Illuminate\Support\Facades\DB;

trait HandlesAutomation
{
    /**
     * Run the automation logic for a given greenhouse.
     *
     * @param Greenhouse $greenhouse
     * @param string|null $modeOverride Optionally set the control mode before running automation
     * @return array
     */
    public function runAutomation(Greenhouse $greenhouse, ?string $modeOverride = null): array
    {
        if ($modeOverride) {
            $greenhouse->settings()->update(['control_mode' => $modeOverride]);
            $greenhouse->settings->refresh();
        }

        $settings = $greenhouse->settings;
        $latestReading = SensorReading::where('device_id', $greenhouse->product_id)
            ->latest()
            ->first();

        $areaTemperature = $this->calculateAreaTemperature($greenhouse);

        if (!$settings || !$latestReading) {
            return [
                'success' => false,
                'message' => 'Missing settings or sensor readings',
                'area_temperature' => $areaTemperature
            ];
        }

        $alerts = [];
        if ($latestReading->temperature > $settings->temperature_limit) {
            $alerts[] = ['message' => "High Temp: {$latestReading->temperature}°C", 'level' => 'warning'];
        }
        if ($latestReading->humidity > $settings->humidity_limit) {
            $alerts[] = ['message' => "High Humidity: {$latestReading->humidity}%", 'level' => 'warning'];
        }
        if ($latestReading->soil_moisture < $settings->soil_moisture_limit) {
            $alerts[] = ['message' => "Low Soil: {$latestReading->soil_moisture}%", 'level' => 'warning'];
        }

        foreach ($alerts as $alertData) {
            Alert::create([
                'greenhouse_id' => $greenhouse->id,
                'message' => $alertData['message'],
                'level' => $alertData['level'],
            ]);
        }

        $controlTriggered = false;
        $deviceStatus = [
            'pump' => 'off',
            'ac' => 'off',
            'exhaust' => 'off'
        ];

        if ($settings->control_mode === 'auto') {
            $pump = $latestReading->soil_moisture < $settings->soil_moisture_limit;
            $ac = $latestReading->temperature > $settings->temperature_limit;
            $exhaust = $latestReading->humidity > $settings->humidity_limit;

            \App\Events\ControlUpdated::dispatch($greenhouse->product_id, $pump, $ac, $exhaust);
            $controlTriggered = true;

            $deviceStatus = [
                'pump' => $pump ? 'on' : 'off',
                'ac' => $ac ? 'on' : 'off',
                'exhaust' => $exhaust ? 'on' : 'off'
            ];
        }

        return [
            'success' => true,
            'control_triggered' => $controlTriggered,
            'control_mode' => $settings->control_mode,
            'device_status' => $deviceStatus,
            'area_temperature' => $areaTemperature,
            'latest_reading' => $latestReading
        ];
    }

    /**
     * Calculate average temperature for the greenhouse area.
     */
    protected function calculateAreaTemperature(Greenhouse $greenhouse): ?float
    {
        $avg = SensorReading::whereIn('device_id', function ($query) use ($greenhouse) {
            $query->select('product_id')
                ->from('greenhouses')
                ->where('location', $greenhouse->location);
        })
            ->where('created_at', '>=', now()->subHours(1))
            ->avg('temperature');

        return $avg ? round((float) $avg, 2) : null;
    }
}
