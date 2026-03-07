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

## Troubleshooting Tips
- **CSRF Issues:** If you get a `419 Page Expired`, ensure you are calling `GET http://localhost:8000/sanctum/csrf-cookie` first in Postman to initialize the session.
- **Validation Errors:** If you get a `422 Unprocessable Entity`, check the response body for specific field errors.
- **Server Running:** Ensure `php artisan serve` is active.
