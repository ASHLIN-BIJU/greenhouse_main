# API Authentication Documentation

This document provides the necessary details for integrating the Greenhouse App with the backend authentication system.

## 1. Authentication Overview
The backend uses **Laravel Fortify** for authentication logic and **Laravel Sanctum** for secure session/token management. 

### Base URL
`http://localhost:8000/api`

### Required Headers for all POST/PUT/DELETE requests:
- `Accept`: `application/json`
- `Content-Type`: `application/json`
- `X-Requested-With`: `XMLHttpRequest`

---

## 2. Security: CSRF Protection
Before any authentication attempt (Login/Register), the mobile app or web client **must** initialize the CSRF protection.

- **Endpoint:** `GET /sanctum/csrf-cookie`
- **Purpose:** Sets the CSRF token in a cookie. Subsequent requests will automatically include this token if your HTTP client supports cookies.

---

## 3. Endpoints

### A. User Registration
Registers a new user and associates them with a physical Greenhouse device.

- **Endpoint:** `POST /register`
- **Payload:**
```json
{
    "name": "Full Name",
    "email": "user@example.com",
    "password": "SecretPassword123",
    "password_confirmation": "SecretPassword123",
    "product_id": "GH-XXXXXX",
    "address": "Street Address",
    "city": "City Name",
    "state": "State Name",
    "pincode": "123456",
    "greenhouse_name": "Optional Name"
}
```
- **Validation Rules:**
    - `product_id`: Must exist in `registered_products` table and be `unused`.
    - `password`: Minimum 8 characters.

---

### B. User Login
Authenticates the user and starts a session.

- **Endpoint:** `POST /login`
- **Payload:**
```json
{
    "email": "user@example.com",
    "password": "SecretPassword123"
}
```
- **Response (Success):** Returns a `204 No Content` or `200 OK` with user data, depending on the client. Cookies are set automatically.

---

### C. User Logout
Ends the current session.

- **Endpoint:** `POST /logout`
- **Header:** `Authorization: Bearer <token>` (if using tokens) or standard session cookies.
- **Response:** `200 OK` or `204 No Content`.

---

## 4. Error Handling
The API returns standard HTTP status codes:
- `200/201/204`: Success.
- `422 Unprocessable Entity`: Validation failed. The response body will contain a `message` and an `errors` object.
- `401 Unauthorized`: Invalid credentials or session expired.

**Example Validation Error (422):**
```json
{
    "message": "The product id field is required.",
    "errors": {
        "product_id": ["Invalid product_id. Please check it."]
    }
}
```
