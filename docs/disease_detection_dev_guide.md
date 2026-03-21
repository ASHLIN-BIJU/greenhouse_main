# Developer Documentation: Plant Disease Detection Feature

## Overview
This feature allows the greenhouse system to capture plant images (via Luckfox board), detect diseases using an external API, and store the results for retrieval by the Flutter app.

## Project Structure

### 1. API Logic
- **Controller**: `app/Http/Controllers/Api/DiseaseController.php`
  - Handles image upload validation, storage, and database orchestration.
- **Service**: `app/Services/DiseaseDetectionService.php`
  - Encapsulates the Guzzle HTTP logic for external API communication.
  - **Mock Mode**: Returns a predefined response if `DISEASE_API_URL` is empty in `.env`.
- **Model**: `app/Models/Disease.php`
  - Includes an `image_url` accessor that returns the full asset path for the frontend.

### 2. Database
- **Table**: `diseases`
- **Migrations**:
  - `create_diseases_table`: Initial table structure.
  - `add_columns_to_diseases_table`: Syncs schema if table already existed.
  - `remove_old_columns_from_diseases_table`: Cleans up legacy conflicting fields.

## API Endpoints

### [POST] `/api/disease/detect`
- **Purpose**: Upload and detect disease.
- **Payload**: `multipart/form-data` with an `image` file.
- **Response**: JSON object with disease details and `image_url`.

### [GET] `/api/disease`
- **Purpose**: List all detection history.
- **Response**: Array of disease records.

### [GET] `/api/disease/{id}`
- **Purpose**: Get specific detection details.

## Setup Instructions for Developers

1. **Environment Variables**:
   Add these to your `.env`:
   ```env
   DISEASE_API_URL=
   DISEASE_API_KEY=
   ```

2. **Database Migration**:
   ```bash
   php artisan migrate
   ```

3. **Storage Setup**:
   ```bash
   php artisan storage:link
   ```

## Testing (Postman Guide)

### 1. Detect Disease (POST)
- **URL**: `{{APP_URL}}/api/disease/detect`
- **Method**: `POST`
- **Body**: `form-data`
- **Key**: `image` (Change type to **File**)
- **Value**: Upload a plant leaf image.
- **Response**: Returns a 201 Created with the disease data and `image_url`.

### 2. Get All History (GET)
- **URL**: `{{APP_URL}}/api/disease`
- **Method**: `GET`
- **Response**: 200 OK with a list of all records.

### 3. Get Single Record (GET)
- **URL**: `{{APP_URL}}/api/disease/{id}`
- **Method**: `GET`
- **Response**: 200 OK with specific record data.

## Switching to Live API
When the real external API is available:
1. Update `DISEASE_API_URL` and `DISEASE_API_KEY` in `.env`.
2. Run `php artisan optimize:clear`.
3. The `DiseaseDetectionService` will automatically switch from mock data to real API calls using Guzzle.

## Testing
A Postman guide is available in the project walkthrough. The endpoints are public (no Sanctum auth required as per requirements).
