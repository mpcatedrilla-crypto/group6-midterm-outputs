# Events Management API with Laravel Sanctum

A complete RESTful API built with Laravel and authenticated via Laravel Sanctum.

## Features

- **Token-based Authentication** with Laravel Sanctum
- **Full CRUD Operations** for Events
- **Full CRUD Operations** for Participants
- **PostgreSQL Database** support
- **RESTful API Design**

## Requirements

- PHP 8.1+
- PostgreSQL
- Composer

## Installation

### 1. Clone and Install Dependencies

```bash
cd events-api-laravel
composer install
```

### 2. Environment Configuration

```bash
cp .env.example .env
# Edit .env with your database credentials
php artisan key:generate
```

### 3. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

### 4. Start the Server

```bash
php artisan serve
```

## API Endpoints

### Authentication (Public)

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login & get token |
| POST | `/api/register` | Register new user |

### Protected Routes (Require Bearer Token)

#### Events

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/events` | List all events |
| GET | `/api/events/{id}` | Get single event |
| POST | `/api/events` | Create event |
| PUT | `/api/events/{id}` | Update event |
| DELETE | `/api/events/{id}` | Delete event |

#### Participants

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/participants` | List all participants |
| GET | `/api/participants/{id}` | Get single participant |
| GET | `/api/participants/event/{event_id}` | List by event |
| POST | `/api/participants` | Register participant |
| PUT | `/api/participants/{id}` | Update participant |
| DELETE | `/api/participants/{id}` | Delete participant |

#### Auth

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/logout` | Revoke token |
| GET | `/api/user` | Get current user |

## Usage Example

### 1. Login

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

Response:
```json
{
  "user": {"id": 1, "name": "Admin User", "email": "admin@example.com"},
  "token": "1|laravel_sanctum_token_here"
}
```

### 2. Access Protected Route

```bash
curl -X GET http://localhost:8000/api/events \
  -H "Authorization: Bearer 1|laravel_sanctum_token_here"
```

### 3. Create Event

```bash
curl -X POST http://localhost:8000/api/events \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"title": "New Event", "description": "Details", "event_date": "2024-12-25"}'
```

### 4. Logout

```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Default Credentials

- **Email:** admin@example.com
- **Password:** password123

## Database Schema

- **users** - Authentication
- **personal_access_tokens** - Sanctum tokens
- **events** - Event records
- **participants** - Event participants (linked to events)
