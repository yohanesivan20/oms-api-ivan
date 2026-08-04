# OMS API (Order Management System)

Order Management System (OMS) API built with **Laravel 10** as part of a backend technical assessment.

The application provides REST APIs for managing orders, payments, shipments, shipment tracking, queue processing, logging, and external API integrations.

---

# Features

* Product API Integration (DummyJSON)
* Create Order
* Order Detail
* Payment Integration
* Payment Webhook
* Shipment Integration (RajaOngkir)
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

---

# Installation

## 1. Clone Repository

```bash
git clone https://github.com/yourusername/oms-api.git

cd oms-api
```

---

## 2. Install Dependency

```bash
composer install
```

---

## 3. Copy Environment File

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

## 6. Configure External API

```env
DUMMYJSON_URL=https://dummyjson.com

RAJAONGKIR_BASE_URL=https://rajaongkir.komerce.id/api/v1

RAJAONGKIR_API_KEY=YOUR_API_KEY
```

---

## 7. Configure Queue

```env
QUEUE_CONNECTION=database
```

Generate Queue Table

```bash
php artisan queue:table
```

Run Migration

```bash
php artisan migrate
```

---

## 8. Configure Mail

Example using Log Driver

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="OMS API"
```

---

# Database Migration

Run all migrations

```bash
php artisan migrate
```

If you need fresh database

```bash
php artisan migrate:fresh
```

---

# Run Queue Worker

Open another terminal

```bash
php artisan queue:work
```

Queue is used for:

* Payment Processing
* Email Notification

---

# API Documentation

Generate OpenAPI documentation

```bash
php artisan scramble:export
```

Open documentation

```
http://localhost:8000/docs/api
```

---

# Run Application

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

| Method | Endpoint           |
| ------ | ------------------ |
| GET    | /api/products      |
| GET    | /api/products/{id} |

---

## Orders

| Method | Endpoint         |
| ------ | ---------------- |
| POST   | /api/orders      |
| GET    | /api/orders/{id} |

---

## Payments

| Method | Endpoint                     |
| ------ | ---------------------------- |
| POST   | /api/orders/{order}/payments |
| POST   | /api/payments/webhook        |

---

## Shipment

| Method | Endpoint                           |
| ------ | ---------------------------------- |
| GET    | /api/shipping/destination          |
| POST   | /api/shipping/cost                 |
| POST   | /api/orders/{order}/shipments      |
| GET    | /api/shipments/{shipment}/tracking |

---

# Logging

All external API calls are logged into the `api_logs` table.

Logged information includes:

* Service Name
* Endpoint
* HTTP Method
* Request Payload
* Response Payload
* Status Code
* Success / Failed Status
* Error Message

---

# Queue

Queue Driver

```
Database
```

Jobs

* ProcessPaymentJob
* SendOrderCreatedEmailJob

---

# Testing

The project can be tested using:

* Postman Collection
* Scramble Documentation

---

# Author

Ivan Danasuta
