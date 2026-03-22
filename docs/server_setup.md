# Server Setup Guide for WebSockets

To enable real-time sensor data processing and control via WebSockets (Reverb), the following services must be running on your server.

## 1. Prerequisites
- Ensure `.env` has the correct `REVERB_*` credentials.
- The server must allow traffic on the Reverb port (default `8080`).

## 2. Required Services

### A. Reverb Server
This handles the WebSocket connections.
```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

### B. Sensor Data Listener
This custom command bridges WebSocket "whispers" from devices to the Laravel application logic.
```bash
php artisan app:listen-sensor-data
```

## 3. Running in Production (Recommended)
In a production environment, you should use a process manager like **Supervisor** to ensure these services stay running.

### Sample Supervisor Configuration (`/etc/supervisor/conf.d/greenhouse-ws.conf`)
```ini
[program:reverb]
command=php /path/to/your/project/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/reverb.log

[program:sensor-listener]
command=php /path/to/your/project/artisan app:listen-sensor-data
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/sensor-listener.log
```

After creating the config, run:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start reverb
sudo supervisorctl start sensor-listener
```
