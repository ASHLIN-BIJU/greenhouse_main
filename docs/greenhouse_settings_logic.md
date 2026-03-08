# Greenhouse Settings Logic

This document explains how the environment threshold settings (Temperature & Humidity limits) are managed in the system.

## 1. Initial Setup (Registration)
When a user registers their greenhouse using a `product_id`, the system automatically creates a set of **default thresholds**.

- **Default Temperature Limit:** 30.0 °C
- **Default Humidity Limit:** 70.0 %
- **Default Soil Moisture Limit:** 40.0 %

This is handled in the `CreateNewUser` action during the registration process.

## 2. Viewing Settings
The app (or dashboard) retrieves these settings when the user logs in. This allows the user to see the "Current Limits" on their dashboard next to the live sensor data.

## 3. Customizing Settings (User Action)
If the user wants to change these limits (e.g., they are growing tropical plants that need higher humidity), they can use the **Settings Update API**.

- **Workflow:**
    1. User adjusts a slider or input in the app.
    2. The app sends a `PUT` request to `/api/greenhouse/settings`.
    3. The `GreenhouseSettingController` authenticates the user, finds their greenhouse, and updates the `greenhouse_settings` table.

## 4. How the Settings are Used
The system uses these thresholds to perform automated actions:

- **Comparison:** Whenever new sensor data arrives (via `SensorDataController`), the system compares the live value against the user's custom limits.
- **Trigger:**
    - If `Temperature > Temperature Limit` -> System sends a signal to turn on the **Cooling Fan**.
    - If `Humidity > Humidity Limit` -> System sends a signal to turn on the **Exhaust**.
    - If `Soil Moisture < Soil Moisture Limit` -> System sends a signal to turn on the **Water Pump**.

> [!NOTE]
> By allowing users to change these settings, the greenhouse becomes "intelligent" to their specific needs rather than just following a hard-coded default.
