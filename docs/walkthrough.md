# Walkthrough: Laravel Reverb Installation

I have successfully installed and configured Laravel Reverb to resolve the missing `reverb:start` command error.

## Changes Made

### Installation
- Installed `laravel/reverb` package via Composer.
- Ran `php artisan reverb:install` to set up the configuration.

### Configuration
- Automatically updated `.env` with the necessary Reverb variables:
    - `REVERB_APP_ID`
    - `REVERB_APP_KEY`
    - `REVERB_APP_SECRET`
    - `REVERB_HOST`
    - `REVERB_PORT`
    - `REVERB_SCHEME`
- Set `BROADCAST_CONNECTION=reverb`.

## Verification Results

### Command Availability
Running `php artisan reverb:start --help` confirms the command is now available:
```bash
Usage:
  reverb:start [options]
```

### Environment Variables
Checked `.env` and confirmed the presence of:
```bash
REVERB_APP_ID=405588
REVERB_APP_KEY=nywcgjbzz5yhljss7pbt
...
```

You can now start the Reverb server by running:
```bash
php artisan reverb:start
```

## Product Registration
- Added product ID `GH-908712` to the `registered_products` table.
- Verified that the product is marked as `unused` and ready for registration.

## Address Updates
- Added `pincode` field to the `addresses` table.
- Updated `CreateNewUser` logic to require and save `pincode` during registration.
- Updated `postman_guide.md` with the updated registration payload.

## Conditional Sensor Storage
- Modified `SensorDataController` to only store a reading if the data has changed from the previous entry for the same device.
- This prevents redundant data from cluttering the database while maintaining real-time broadcasts for every signal.
- Verified with multiple POST requests, confirming that identical data returns a "Data unchanged" message and skips storage.

## Customizable Greenhouse Settings
- Added support for Temperature, Humidity, and **Soil Moisture** threshold limits.
- Users can now customize these settings via the `PUT /api/greenhouse/settings` endpoint.
- Added documentation explaining the logic and automated trigger flows.
