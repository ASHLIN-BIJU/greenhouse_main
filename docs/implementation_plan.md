# Automated Temperature & Environment Notifications

The goal is to automatically generate notifications (alerts) when sensor readings exceed the limits set by the user, and provide an API for the "Notification Page" to display them.

## Proposed Changes

### [API Layer]

#### [MODIFY] [SensorDataController.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Http/Controllers/Api/SensorDataController.php)
- Update the `store` method.
- Fetch the user's `GreenhouseSetting` for the device's greenhouse.
- Compare incoming `temperature`, `humidity`, and `soil_moisture` with the limits.
- If a limit is exceeded, create a new record in the `alerts` table:
    - Example: `High Temperature Alert: 35°C (Limit: 30°C)`
    - Level: `warning` or `critical` based on the deviation.

#### [NEW] [NotificationController.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Http/Controllers/Api/NotificationController.php)
- Create a controller with an `index` method to return the latest alerts for the authenticated user.
- Add a `markAsRead` (optional) or `destroy` method if needed.

#### [MODIFY] [api.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/routes/api.php)
- Add `GET /api/notifications` route protected by `auth:sanctum`.

---

## Verification Plan

### Automated Tests
- **Test Case 1: Threshold Breach**
    - Send sensor data exceeding the `temperature_limit`.
    - Verify a new record exists in the `alerts` table.
- **Test Case 2: Notification API**
    - Call `GET /api/notifications` as the user.
    - Verify the list contains the newly created alert.

### Manual Verification
- Trigger an alert via Postman and check the database/API response.
