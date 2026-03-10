# Postman Testing Guide

This guide explains how to test the authentication endpoints for your Greenhouse application.

## 1. Register a New User
**Method:** `POST`  
**URL:** `http://localhost:8000/api/register`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**Body (Raw JSON):**
```json
{
    "name": "Alice Wonderland",
    "email": "alice@example.com",
    "password": "Password123!",
    "password_confirmation": "Password123!",
    "product_id": "GH-112233",
    "address": "456 Wonderland Ave",
    "city": "Dream City",
    "state": "Fantasy",
    "pincode": "54321",
    "greenhouse_name": "Alice's Secret Garden"
}
```
> [!NOTE]
> `product_id` must be an unused ID from the `registered_products` table. I've already added `GH-908712` and `GH-112233` for you.

---

## 2. Login
**Method:** `POST`  
**URL:** `http://localhost:8000/api/login`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**Body (Raw JSON):**
```json
{
    "email": "alice@example.com",
    "password": "Password123!"
}
```
> [!TIP]
> After a successful login, you will receive a token (if using Sanctum tokens) or a session cookie. For this API, ensure you capture the token for subsequent requests.

---

## 3. Logout
**Method:** `POST`  
**URL:** `http://localhost:8000/api/logout`  
**Headers:**
- `Accept`: `application/json`
- `Authorization`: `Bearer <YOUR_TOKEN_HERE>`

---

## 4. Test Sensor Data (WebSocket Broadcast)
To test if your WebSocket is working, you can simulate a sensor device sending data.

**Method:** `POST`  
**URL:** `http://localhost:8000/api/sensor-data`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**Body (Raw JSON):**
```json
{
    "device_id": "GH-112233",
    "temperature": 28.5,
    "humidity": 70.2,
    "soil_moisture": 45.0
}
```

> [!IMPORTANT]
> 1. Ensure `php artisan reverb:start` is running in a terminal.
> 2. Open Alice's Dashboard (or your WebSocket client) to see the live update when you click **Send** in Postman.
> 3. The API response will now include `area_temperature`, which is the average temperature for all devices in that city/location.

---

---

## 5. Update Greenhouse Settings
Allows an authenticated user to update their greenhouse threshold settings.

**Method:** `PUT`  
**URL:** `http://localhost:8000/api/greenhouse/settings`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`
- `Authorization`: `Bearer <YOUR_TOKEN_HERE>`

**Body (Raw JSON):**
```json
{
    "temperature_limit": 32.5,
    "humidity_limit": 65.0,
    "soil_moisture_limit": 45.0,
    "control_mode": "auto"
}
```
> [!NOTE]
> `control_mode` can be `auto` or `manual`. In `auto` mode, the system automatically triggers the pump and exhaust based on thresholds.

---

## 6. Manual Device Control
Allows an authenticated user to manually override device states. 

**Method:** `POST`  
**URL:** `http://localhost:8000/api/device-control`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`
- `Authorization`: `Bearer <YOUR_TOKEN_HERE>`

**Body (Raw JSON):**
```json
{
    "device_id": "GH-112233",
    "pump_mode": true,
    "exhaust_mode": false
}
```
> [!IMPORTANT]
> Sending a manual control command will **automatically switch the greenhouse to `manual` mode**. To resume automation, you must update the settings back to `control_mode: auto`.

---

## 7. Synchronize Automation (No-Payload Trigger)
Allows you to trigger automation for a specific greenhouse using only the `product_id`. It will automatically fetch the latest sensor data from the database and run threshold checks.

**Method:** `POST`  
**URL:** `http://localhost:8000/api/greenhouse/sync`  
**Headers:**
- `Accept`: `application/json`
- `Content-Type`: `application/json`

**Body (Raw JSON):**
```json
{
    "product_id": "GH-112233",
    "mode": "auto"
}
```
> [!TIP]
> Use this endpoint when you want current database values to trigger the pump/exhaust without sending a full sensor payload. You can also use it to force a switch back to `auto` mode.

---

## 8. Detailed Testing Workflows

## 9. Disease Review API
Fetch and view plant diseases fetched from the external API.

**List All Diseases:**
- **Method:** `GET`
- **URL:** `http://localhost:8000/api/disease`

**View Specific Disease:**
- **Method:** `GET`
- **URL:** `http://localhost:8000/api/disease/{id}`

> [!NOTE]
> Data is automatically fetched every 4 hours via the `fetch:disease-data` command. You can trigger it manually using `php artisan fetch:disease-data`.

---

## Troubleshooting Tips
- **CSRF Issues:** If you get a `419 Page Expired`, ensure you are calling `GET http://localhost:8000/sanctum/csrf-cookie` first in Postman to initialize the session.
- **Validation Errors:** If you get a `422 Unprocessable Entity`, check the response body for specific field errors.
- **Server Running:** Ensure `php artisan serve` is active.
