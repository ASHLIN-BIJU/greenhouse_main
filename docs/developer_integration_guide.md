# Developer Integration Guide

This guide provides the technical details needed for developers to integrate with the Greenhouse Monitoring System.

## 1. Database Storage (Where is the data?)

The system uses a MySQL database (XAMPP/MariaDB). Below are the primary tables where your data is stored:

| Table | Purpose | Key Columns |
| :--- | :--- | :--- |
| `greenhouses` | Core greenhouse units | `id`, `user_id`, `product_id`, `name`, `location` |
| `sensor_readings` | Historical telemetry data | `id`, `device_id`, `temperature`, `humidity`, `soil_moisture`, `created_at` |
| `greenhouse_settings` | User-defined thresholds | `id`, `greenhouse_id`, `temperature_limit`, `humidity_limit`, `soil_moisture_limit` |
| `sensors` | Metadata about installed sensors | `id`, `greenhouse_id`, `sensor_type`, `unit` |
| `users` | Authenticated user accounts | `id`, `name`, `email`, `password` |
| `addresses` | User contact information | `id`, `user_id`, `address`, `city`, `state`, `pincode` |

---

## 2. API Endpoints (How to interact?)

### Authentication (Fortify/Sanctum)
- **POST** `/api/register`: Create a new account & greenhouse.
- **POST** `/api/login`: Get an API token (returns `token`).
- **POST** `/api/logout`: Revoke the current token.

### Greenhouse Settings
- **PUT** `/api/greenhouse/settings`: Update thresholds for temperature, humidity, and soil moisture.
  - *Requires:* Authorization Bearer Token.
  - *Payload Example:* `{"temperature_limit": 30.0, "humidity_limit": 70.0, "soil_moisture_limit": 40.0}`

### Sensor Data (Device Interaction)
- **POST** `/api/sensor-data`: Devices send telemetry here.
  - *Payload:* `{"device_id": "GH-123", "temperature": 25, "humidity": 60, "soil_moisture": 35}`
  - *Note:* Data is only stored in `sensor_readings` if it differs from the last reading.

---

## 3. WebSockets (How to get live updates?)

Real-time data is broadcasted using **Laravel Reverb**.

- **Channel:** `public-greenhouse.{deviceId}`
- **Event:** `SensorDataUpdated`
- **Data Format:**
```json
{
    "deviceId": "GH-123",
    "temperature": 25.5,
    "humidity": 60.2,
    "soilMoisture": 35.8
}
```

---

## 4. Integration Steps for App Developers
1. **Initialize Session:** Call `GET /sanctum/csrf-cookie`.
2. **Register/Login:** Use the Auth endpoints to get a Bearer token.
3. **Listen for Data:** Connect to the Reverb WebSocket channel using the User's `device_id`.
4. **Update Settings:** Use the settings API to let the user customize their environment.

> [!TIP]
> Use the [Postman Guide](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/postman_guide.md) to test these endpoints before writing any frontend code.
