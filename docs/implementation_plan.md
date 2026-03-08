# User-Adjustable Greenhouse Settings

This feature allows authenticated users to update the threshold settings for their greenhouse, such as temperature and humidity limits.

## Proposed Changes

### [API Layer]

#### [NEW] [GreenhouseSettingController.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/app/Http/Controllers/Api/GreenhouseSettingController.php)
- Create a new controller with an `update` method.
- The method will:
    - Validate the input (`temperature_limit`, `humidity_limit`).
    - Retrieve the authenticated user's greenhouse.
    - Update the associated `GreenhouseSetting` record.
    - Return the updated settings as JSON.

#### [MODIFY] [api.php](file:///home/ashlin/Desktop/greenhouse_main/greenhouse_main/routes/api.php)
- Add a new `PUT/PATCH` route for `/greenhouse/settings` protected by `auth:sanctum`.

### [Documentation]

#### [MODIFY] [postman_guide.md](file:///home/ashlin/.gemini/antigravity/brain/a7ac01e9-2720-4773-94b4-576ba51c2749/postman_guide.md)
- Add a section on how to update greenhouse settings.

## Verification Plan

### Automated Tests
- **Test Case 1: Update Settings (Authenticated)**
    - Log in as a user and get a Sanctum token.
    - Send a `PUT` request to `/api/greenhouse/settings` with new limits.
    - Verify the database record in `greenhouse_settings` is updated.
- **Test Case 2: Update Settings (Unauthenticated)**
    - Send the same request without a token.
    - Verify it returns a `401 Unauthorized`.
- **Test Case 3: Validation Check**
    - Send invalid data (e.g., non-numeric limits).
    - Verify it returns a `422 Unprocessable Entity`.
