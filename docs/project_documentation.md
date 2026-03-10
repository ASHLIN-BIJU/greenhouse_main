# Greenhouse Monitoring System - Complete Documentation

## 1. Project Overview
The **Greenhouse Monitoring System** is a real-time IoT solution designed to monitor and manage environmental conditions in a greenhouse. It provides a secure backend for device data ingestion, real-time status updates via WebSockets, and user-defined automation thresholds.

### Key Features
- **User Authentication:** Secure registration and login using Laravel Fortify & Sanctum.
- **Device Association:** Users link a physical product to their account during registration using a unique `product_id`.
- **Real-time Monitoring:** Live telemetry (Temperature, Humidity, Soil Moisture) broadcasted via WebSockets.
- **Intelligent Thresholds:** Customizable limits for environment factors with automated alert/device control triggers.
- **Auto/Manual Control:** Toggle between automated threshold-based control and manual remote override.
- **Historical Data:** Permanent storage of sensor readings for trend analysis.
- **Notification System:** In-app alerts when environmental limits are exceeded.

---

## 2. Technical Architecture

### Backend Stack
- **Framework:** Laravel 11.x
- **Authentication:** Laravel Fortify (Session/Token logic) & Sanctum (API tokens).
- **WebSockets:** Laravel Reverb (Real-time broadcasting).
- **Database:** MySQL (Relational storage for users, readings, and settings).

### IoT Integration
- **Protocol:** HTTP POST (JSON)
- **Data Flow:** Sensors -> API Gateway (`/api/sensor-data`) -> Database -> WebSocket Broadcast -> User Dashboard.

---

## 3. Database Schema
Major tables in the system:

| Table | Description |
| :--- | :--- |
| `users` | User accounts and credentials. |
| `greenhouses` | Links users to their physical devices (`product_id`). |
| `sensor_readings` | Historical log of all telemetry data. |
| `greenhouse_settings` | User-defined thresholds for automation. |
| `alerts` | Log of notifications triggered by threshold breaches. |
| `registered_products` | Inventory of valid hardware IDs for registration. |

---

## 4. API Endpoints

### Authentication
- `POST /api/register`: Create account & register device.
- `POST /api/login`: Authenticate and receive session/token.
- `POST /api/logout`: Terminate session.

### Sensor & Settings
- `POST /api/sensor-data`: Ingest data from IoT devices.
- `PUT /api/greenhouse/settings`: Update thresholds (Temp, Humidity, Soil).
- `GET /api/notifications`: Retrieve user alerts.
- `POST /api/device-control`: Manually override and control devices (Pump, Exhaust).

---

## 5. Real-time Updates (WebSockets)
The system uses **Laravel Reverb** to push data to dashboards without page refreshes.
- **Channel:** `public-greenhouse.{deviceId}`
- **Event:** `SensorDataUpdated`
- **Frontend Hook:** Use Laravel Echo to listen for updates on the device-specific channel.

---

## 6. Development & Deployment
### Prerequisites
- PHP 8.2+
- MySQL
- Node.js & NPM

### Setup Instructions
1. **Clone & Install:**
   ```bash
   composer install
   npm install
   ```
2. **Environment Configuration:**
   Copy `.env.example` to `.env` and configure database/websocket settings.
   ```bash
   php artisan key:generate
   ```
3. **Database Migration:**
   ```bash
   php artisan migrate --seed
   ```
4. **Start Servers:**
   - Web Server: `php artisan serve`
   - WebSocket Server: `php artisan reverb:start`
   - Frontend Build: `npm run dev`

---

## 7. Documentation Index
For more specific details, refer to the following documents in the `docs/` directory:
- [Postman Testing Guide](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/postman_guide.md)
- [API Auth Documentation](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/api_auth_documentation.md)
- [WebSocket Documentation](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/websocket_documentation.md)
- [Developer Integration Guide](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/developer_integration_guide.md)
- [Greenhouse Settings Logic](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/docs/greenhouse_settings_logic.md)
