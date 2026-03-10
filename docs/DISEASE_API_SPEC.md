# Technical Specification: Greenhouse Disease Intelligence API
**Target Audience**: External API Developers / Data Providers

---

## 📋 Objective
To provide a standardized JSON interface for plant disease data that will be ingested by the Greenhouse Monitoring System every 4 hours.

## 🌐 Endpoint Requirements
The partner API should expose at least one endpoint for bulk data retrieval.

- **Endpoint**: `GET /api/v1/diseases` (Example)
- **Format**: `application/json`
- **Authentication**: (e.g., Header: `X-API-KEY: your_key_here`)

---

## 🏗️ Expected JSON Schema
Our system expects an array of disease objects. Each object **MUST** contain the following fields:

| Field | Type | Description |
| :--- | :--- | :--- |
| `id` | `string` | **Unique** identifier for the disease (e.g., "DIS-001") |
| `name` | `string` | Common name of the disease (Max 255 chars) |
| `description` | `text` | General overview of the disease and its impact |
| `symptoms` | `text` | Visual or physical indicators on the plant |
| `treatment` | `text` | Recommended pharmacological or cultural fixes |

### Sample Response Output
```json
[
  {
    "id": "BT-7721",
    "name": "Bacterial Blight",
    "description": "A destructive disease caused by Xanthomonas campestris.",
    "symptoms": "Water-soaked spots on leaves that eventually turn brown and dry.",
    "treatment": "Copper-based bactericides and avoiding overhead irrigation."
  }
]
```

---

## 🛠️ Integration Checklist
1. **Uniqueness**: The `id` field must remain constant for the same disease to avoid duplicates in our database.
2. **Availability**: The API must maintain high availability as our cron job triggers every 4 hours.
3. **Data Length**: Text fields (`description`, `symptoms`, `treatment`) should be comprehensive but plain-text (no HTML tags).
4. **Pagination**: If the dataset exceeds 100 diseases, please implement standard `page` and `limit` parameters.

---

## 🔗 Internal Mapping Reference (Laravel)
For your reference, our internal storage maps your data as follows:
- `item.id` -> `diseases.external_id` (Unique Key)
- `item.name` -> `diseases.name`
- `item.description` -> `diseases.description`
- `item.symptoms` -> `diseases.symptoms`
- `item.treatment` -> `diseases.treatment`
