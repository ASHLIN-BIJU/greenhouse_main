# Conditional Sensor Data Storage

The goal is to modify the `SensorDataController` so that it only stores a new `SensorReading` if the incoming data is different from the most recent reading for that `device_id`. Real-time broadcasts via WebSockets must continue for every request regardless of storage.

## Proposed Changes

### [API Layer]

#### [MODIFY] [SensorDataController.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Http/Controllers/Api/SensorDataController.php)
- Update the `store` method.
- Fetch the latest `SensorReading` for the given `device_id`.
- Compare `temperature`, `humidity`, and `soil_moisture` of the new request with the latest reading.
- Only call `SensorReading::create($validated)` if at least one value has changed.
- Always dispatch the `SensorDataUpdated` event to ensure the dashboard reflects the current status.
- Return a JSON response indicating whether the data was stored or only broadcasted.

---

## Verification Plan

### Automated Tests
- **Test Case 1: Initial Data Storage**
  - Send a POST request to `/api/sensor-data` for a new `device_id`.
  - Verify the response indicates data was stored (status code 200).
- **Test Case 2: Redundant Data Prevention**
  - Send the *exact same* data again for the same `device_id`.
  - Verify the response indicates "Data unchanged; broadcasted only".
  - Check the `sensor_readings` table to ensure no new row was added.
- **Test Case 3: Changed Data Storage**
  - Change one value (e.g., temperature) and send the request.
  - Verify the response indicates data was stored.
  - Check the `sensor_readings` table to confirm the new row is present.

### Manual Verification
- Observe the WebSocket dashboard while sending identical data from Postman to ensure real-time updates are still firing even when database storage is skipped.
