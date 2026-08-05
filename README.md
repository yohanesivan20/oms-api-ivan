# OMS API (Order Management System)

Order Management System (OMS) API built with **Laravel 10** as part of a backend technical assessment.

This application provides REST APIs for product retrieval, order management, payment processing, shipment management, shipment cost calculation, shipment tracking, queue processing, logging, and external API integration.

---

# Features

* Product API Integration (DummyJSON)
* Create Order
* Payment Integration
* Payment Webhook
* Shipment Integration (RajaOngkir)
* Shipping Cost Calculation
* Destination Search
* Queue Processing
* Email Notification
* External API Logging
* API Documentation (Scramble)
* Repository Pattern
* Service Layer
* Database Transaction
* RESTful API

---

# Tech Stack

* PHP 8.1+
* Laravel 10
* MySQL
* Laravel Queue (Database Driver)
* Laravel Mail
* Laravel HTTP Client
* Scramble (OpenAPI Documentation)

---

# External Services

## Product API

DummyJSON

https://dummyjson.com/

---

## Shipping API

RajaOngkir API v1

https://rajaongkir.com/

---

# Project Structure

```text
app
├── Clients
├── Enums
├── Helpers
├── Http
│   ├── Controllers
│   └── Requests
├── Jobs
├── Mail
├── Models
├── Repositories
├── Services
```

---

# Installation

## 1. Clone Repository

```bash
git clone https://github.com/yourusername/oms-api.git

cd oms-api
```

---

## 2. Install Dependencies

```bash
composer install
```

---

## 3. Copy Environment File

Linux / Mac

```bash
cp .env.example .env
```

Windows

```bash
copy .env.example .env
```

---

## 4. Generate Application Key

```bash
php artisan key:generate
```

---

## 5. Configure Database

Update your `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oms_api
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6. Configure External Services

```env
DUMMYJSON_URL=https://dummyjson.com

SHIPPING_URL=https://rajaongkir.komerce.id/api/v1
SHIPPING_API_KEY=YOUR_RAJAONGKIR_API_KEY=your_api_key
```

---

## 7. Configure Queue

```env
QUEUE_CONNECTION=database
```

Generate queue table

```bash
php artisan queue:table
```

---

## 8. Run Database Migration

```bash
php artisan migrate
```

---

## 9. Configure Mail

Example using Log Driver

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="OMS API"
```

---

## 10. Clear Configuration Cache

```bash
php artisan config:clear

php artisan cache:clear

php artisan route:clear

php artisan config:cache
```

---

## 11. Generate API Documentation

```bash
php artisan scramble:export
```

Documentation URL

```
http://localhost:8000/docs/api
```

---

## 12. Start Queue Worker

Open another terminal.

```bash
php artisan queue:work
```

Queue is used for:

* Payment Processing
* Email Notification

---

## 13. Run Application

```bash
php artisan serve
```

Application URL

```
http://127.0.0.1:8000
```

---

# API Endpoints

## Products

| Method | Endpoint             |
| ------ | -------------------- |
| GET    | `/api/products`      |
| GET    | `/api/products/{id}` |

---

## Orders

| Method | Endpoint      |
| ------ | ------------- |
| POST   | `/api/orders` |

---

## Payments

| Method | Endpoint                       |
| ------ | ------------------------------ |
| POST   | `/api/orders/{order}/payments` |

---

## Webhooks

| Method | Endpoint                |
| ------ | ----------------------- |
| POST   | `/api/webhooks/payment` |

---

## Shipments

| Method | Endpoint                             |
| ------ | ------------------------------------ |
| GET    | `/api/shipments/search-destinations` |
| POST   | `/api/shipments/calculate-cost`      |
| POST   | `/api/orders/{order}/shipments`      |

---

# Queue Flow

```text
Create Order
      │
      ▼
Dispatch ProcessPaymentJob
      │
      ▼
Dispatch SendOrderCreatedEmailJob
      │
      ▼
Queue Worker
      │
      ├────────────► Create Payment
      │
      └────────────► Send Email
```

---

# Order Flow

```text
Client
   │
   ▼
Create Order
   │
   ▼
Fetch Products
   │
   ▼
Calculate Grand Total
   │
   ▼
Store Order
   │
   ▼
Store Order Items
   │
   ▼
Dispatch Queue Jobs
   │
   ▼
Return Response
```

---

# Shipment Flow

```text
Search Destination
        │
        ▼
Calculate Shipping Cost
        │
        ▼
Create Shipment
        │
        ▼
Store Shipment Data
```

---

# Logging & Monitoring

All external API calls are stored in the **api_logs** table.

Logged information includes:

* Service Name
* Endpoint
* HTTP Method
* Request Payload
* Response Payload
* HTTP Status Code
* Response Time
* Success / Failed Status
* Error Message

---

# Queue

Queue Driver

```text
Database
```

Jobs

* ProcessPaymentJob
* SendOrderCreatedEmailJob

---

# Testing

The project can be tested using:

* Scramble API Documentation
* Postman Collection

---

# Author

**Ivan Danasuta**
