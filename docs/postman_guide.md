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
    "soil_moisture_limit": 45.0
}
```

### Notifications (Alerts)
Manage environmental alerts triggered by threshold breaches.

**Get Notifications**
- **Method:** `GET`
- **URL:** `http://localhost:8000/api/notifications`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <TOKEN>`

**Delete Notification**
- **Method:** `DELETE`
- **URL:** `http://localhost:8000/api/notifications/{id}`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <TOKEN>`

---

## Troubleshooting Tips
- **CSRF Issues:** If you get a `419 Page Expired`, ensure you are calling `GET http://localhost:8000/sanctum/csrf-cookie` first in Postman to initialize the session.
- **Validation Errors:** If you get a `422 Unprocessable Entity`, check the response body for specific field errors.
- **Server Running:** Ensure `php artisan serve` is active.
