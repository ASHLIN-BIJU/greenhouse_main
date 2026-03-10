# Greenhouse Monitoring System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A real-time IoT monitoring system for green-houses, built with Laravel, Reverb, and MySQL.

## 🌟 Key Features
- **Real-time Telemetry:** Live monitoring of temperature, humidity, and soil moisture.
- **Instant Alerts:** Automated notifications when environmental thresholds are exceeded.
- **Device Management:** Secure registration and association of IoT hardware.
- **WebSocket Integration:** Smooth, live updates using Laravel Reverb.
- **API First:** Ready for integration with Android, iOS, or Web dashboards.

## 🚀 Quick Start

### 1. Installation
```bash
composer install
npm install
```

### 2. Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database & Seeding
```bash
php artisan migrate --seed
```

### 4. Running the Project
You will need three terminal windows:
1. **API Server:** `php artisan serve`
2. **WebSocket Server:** `php artisan reverb:start`
3. **Frontend Assets:** `npm run dev`

## 📚 Documentation
For detailed technical information, please refer to the `docs/` directory:
- [**Complete Project Documentation**](docs/project_documentation.md)
- [Postman Testing Guide](docs/postman_guide.md)
- [API Authentication Details](docs/api_auth_documentation.md)
- [WebSocket & Live Data Flow](docs/websocket_documentation.md)

## 🛠 Tech Stack
- **Backend:** Laravel 11 (Fortify, Sanctum, Reverb)
- **Database:** MySQL
- **Frontend:** Vanilla JS / Tailwind CSS (Integration ready)
- **IoT Protocol:** HTTP JSON

---
Developed as part of the Greenhouse Main project.
