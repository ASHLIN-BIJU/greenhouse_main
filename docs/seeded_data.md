# Seeded Test Data Summary

This document lists the test users and greenhouses that have been added to the database for testing purposes.

## 1. User: Ashlin Biju
- **Email:** `ashlin@example.com`
- **Password:** `Password123!`
- **Greenhouse Name:** `Ashlin's Smart Greenhouse`
- **Product ID:** `GH-908712`
- **Location:** `Kottayam`
- **Thresholds:**
    - Temp Limit: `30.0°C`
    - Humidity Limit: `70.0%`
    - Soil Limit: `30.0%`
    - Mode: `auto`

## 2. User: Bob Miller
- **Email:** `bob@example.com`
- **Password:** `Password123!`
- **Greenhouse Name:** `Bob's Mini Garden`
- **Product ID:** `GH-112233`
- **Location:** `Kochi`
- **Thresholds:** Same as above

## 3. User: Charlie Davis
- **Email:** `charlie@example.com`
- **Password:** `Password123!`
- **Greenhouse Name:** `Charlie's Urban Farm`
- **Product ID:** `GH-999999`
- **Location:** `Kochi`
- **Thresholds:** Same as above

---

## Testing Instructions
1.  **Login:** Use the credentials above in Postman (Section 2 of the Guide).
2.  **WebSocket:** Use one of the Product IDs (`GH-908712`, `GH-112233`, or `GH-999999`) to subscribe and send whispers.
3.  **Sync:** You can trigger the `/api/greenhouse/sync` endpoint using any of these Product IDs.

---
> [!NOTE]
> All passwords are set to `Password123!` for ease of testing. All products are marked as `used` in the `registered_products` table.
