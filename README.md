# ⚙️ Project Setup

To get started, run:

```bash
composer run setup
```

Select `yes` when it asks you if you want to create a database.

# API Documentation

## **Authentication**

### **Register**
**Endpoint:**
```http
POST /api/register
```
**Request Body:**
```json
{
    "name": "John Doe",
    "email": "johndoe@example.com",
    "mobile": null,
    "password": "password123",
    "password_confirmation": "password123"
}
```
**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "mobile": null,
    },
    "token": "your-auth-token"
}
```

---

### **Login**
**Endpoint:**
```http
POST /api/login
```
**Request Body:**
```json
{
    "email": "johndoe@example.com",
    "password": "password123"
}
```
**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "mobile": null
    },
    "token": "your-auth-token"
}
```

---

### **Logout**
**Endpoint:**
```http
POST /api/logout
```
**Headers:**
```http
Authorization: Bearer your-auth-token
```
**Response:**
```json
{
    "message": "Logged out successfully"
}
```

---

## **Car Parks**
**Public endpoints — No authentication required**

### **View all car parks**
**Endpoint:**
```http
GET /api/carpark
```
**Query Parameters:**
- `date_from` (optional) — Start date to check availability and price.
- `date_to` (optional) — End date to check availability and price.
- Leave off to check todays availability and price.

**Response Example:**
```json
{
    "data": [
        {
            "id": 1,
            "name": "Gatwick Airport Parking",
            "total_spaces": 100,
            "available_spaces": 20,
            "date_from": "Sunday, 1st June 2025",
            "date_to": "Monday, 16th June 2025",
            "total_price": "£244.00"
        },
        {
            "id": 2,
            "name": "Heathrow Terminal 1 Parking",
            "total_spaces": 50,
            "available_spaces": 10,
            "date_from": "Sunday, 1st June 2025",
            "date_to": "Monday, 16th June 2025",
            "total_price": "£244.00"
        }
    ]
}
```

---

### **View a single car park**
**Endpoint:**
```http
GET /api/carpark/{id}
```
**Query Parameters:**
- `date_from` (optional) — Start date to check availability and price.
- `date_to` (optional) — End date to check availability and price.
- Leave off to check todays availability and price.

**Response Example:**
```json
{
    "data": {
        "id": 1,
        "name": "Gatwick Airport Parking",
        "total_spaces": 100,
        "available_spaces": 20,
        "date_from": "Sunday, 1st June 2025",
        "date_to": "Monday, 16th June 2025",
        "total_price": "£244.00"
    }
}
```

---

## **Bookings**
**All endpoints require authentication**

### **View a booking**
**Endpoint:**
```http
GET /api/bookings/{id}
```
**Headers:**
```http
Authorization: Bearer your-auth-token
```
**Response Example:**
```json
{
    "data": {
        "id": 1,
        "number_plate": "EM56RIC",
        "date_from": "Tuesday, 1st July 2025",
        "date_to": "Sunday, 6th July 2025",
        "total_price": "£92.00"
    }
}
```

---

### **Create a booking**
**Endpoint:**
```http
POST /api/bookings
```
**Headers:**
```http
Authorization: Bearer your-auth-token
```
**Request Body:**
```json
{
    "car_park_id": 1,
    "number_plate": "AB12 XYZ",
    "date_from": "2025-06-01",
    "date_to": "2025-06-07"
}
```
**Response:**
```json
{
    "data": {
        "id": 52,
        "number_plate": "AB12XYZ",
        "date_from": "Sunday, 1st June 2025",
        "date_to": "Saturday, 7th June 2025",
        "total_price": "£106.00"
    }
}
```

---

### **Update a booking**
**Endpoint:**
```http
PATCH /api/bookings/{id}
```
**Headers:**
```http
Authorization: Bearer your-auth-token
```
**Request Body:**
```json
{
    "date_from": "2024-06-03",
    "date_to": "2024-06-10"
}
```
**Response:**
```json
{
    "data": {
        "id": 52,
        "number_plate": "AB12XYZ",
        "date_from": "Sunday, 1st June 2025",
        "date_to": "Saturday, 7th June 2025",
        "total_price": "£106.00"
    }
}
```

---

### **Delete a booking**
**Endpoint:**
```http
DELETE /api/bookings/{id}
```
**Headers:**
```http
Authorization: Bearer your-auth-token
```
**Response:**
```
No response body is returned.
```

---

## **Notes**
- **Ensure to include `Authorization: Bearer your-auth-token` in protected endpoints**
- **Dates should be in `YYYY-MM-DD` format**

## **Improvements**
- **Separate some logic into service classes.**
- **Add types of parking spaces. Disabled, EV etc.**
- **Add checkin/checkout functionality.**

