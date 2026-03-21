# Luckfox Board: WebSocket Integration Guide

This document is for the embedded developer building the Luckfox board firmware. It details how to connect to the Greenhouse WebSocket server and exchange data.

## 1. Connection Details
The server uses **Laravel Reverb** (Pusher-compatible protocol).

- **Host:** `[SERVER_IP_OR_HOSTNAME]` (e.g., `127.0.0.1` for local testing)
- **Port:** `8080` (Standard HTTP, no TLS/SSL for local testing)
- **Path:** `/app/nywcgjbzz5yhljss7pbt`
- **Full URL Example:**
  `ws://127.0.0.1:8080/app/nywcgjbzz5yhljss7pbt?protocol=7&client=js&version=8.4.0-rc2&flash=false`

> [!IMPORTANT]
> **Handshake Requirement:** The client MUST include the `protocol=7` parameter in the URL. As a client, you MUST mask all outgoing frames (standard WebSocket requirement).

---

## 2. Communication Flow

### Step A: Connect & Wait
1.  Establish WebSocket connection.
2.  Server will send a `pusher:connection_established` message:
    ```json
    {"event":"pusher:connection_established","data":"{\"socket_id\":\"...\"}"}
    ```

### Step B: Subscribe to Control Channel
You must subscribe to the `control.[DEVICE_ID]` channel to receive feedback.
- **Message to Send:**
    ```json
    {
        "event": "pusher:subscribe",
        "data": { "channel": "control.GH-112233" }
    }
    ```

### Step C: Send Sensor Data (Whisper)
Send your readings as a "client event" (whisper).
- **Event Name:** `client-sensor-reading`
- **Channel:** `control.[DEVICE_ID]`
- **Message to Send:**
    ```json
    {
        "event": "client-sensor-reading",
        "data": {
            "device_id": "GH-112233",
            "temperature": 28.5,
            "humidity": 65,
            "soil_moisture": 45
        },
        "channel": "control.GH-112233"
    }
    ```

### Step D: Listen for Control Feedback
The server will broadcast a control message whenever thresholds are breached.
- **Event Name:** `App\Events\ControlUpdated`
- **Message to Receive:**
    ```json
    {
        "event": "App\\Events\\ControlUpdated",
        "data": "{\"deviceId\":\"GH-112233\",\"pumpMode\":true,\"exhaustMode\":false,\"acMode\":true}",
        "channel": "control.GH-112233"
    }
    ```
- **Action:** Parse the `data` field (which is a JSON string) and toggle your GPIO pins for the Pump, Exhaust, and AC accordingly.

---

## 3. Keep-Alive (Ping/Pong)
The server will send a ping every ~30-60 seconds.
- **Message Received:** `{"event":"pusher:ping"}`
- **Action:** The Luckfox board MUST respond immediately with: `{"event":"pusher:pong"}` to prevent the connection from being closed.

---

## 4. Test Credentials
Use these IDs for initial firmware testing:
- **Device ID 1:** `GH-908712`
- **Device ID 2:** `GH-112233` (Recommended)
- **Device ID 3:** `GH-999999`

---
