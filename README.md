# Group 6 - Event Management System

RESTful API project for managing events, participants, and registrations using PHP (OOP) and PostgreSQL.

## Tech Stack

- PHP (XAMPP Apache)
- PostgreSQL
- Postman (API testing)

## Database Setup

1. Create a PostgreSQL database named `event_management_db`.
2. Run `database.sql` in pgAdmin (Query Tool) or in `psql`.
3. Make sure PostgreSQL is running before starting API requests.

## XAMPP Setup

1. Put this folder inside `xampp/htdocs/` or create an Apache alias to this project.
2. Start **Apache** in XAMPP.
3. Update DB credentials in `config/database.php` if needed:
   - host
   - port
   - dbName
   - username
   - password

Base URL example:

`http://localhost/event-management-system/api`

## API Endpoints

### Events (CRUD)

- `GET /api/events`
- `GET /api/events/{id}`
- `POST /api/events`
- `PUT /api/events/{id}`
- `DELETE /api/events/{id}`

Sample body (`POST`/`PUT`):

```json
{
  "title": "Hackathon 2026",
  "description": "24-hour coding event",
  "venue": "CCC Lab 2",
  "event_date": "2026-07-10"
}
```

### Participants (CRUD)

- `GET /api/participants`
- `GET /api/participants/{id}`
- `POST /api/participants`
- `PUT /api/participants/{id}`
- `DELETE /api/participants/{id}`

Sample body (`POST`/`PUT`):

```json
{
  "full_name": "Juan Dela Cruz",
  "email": "juan@example.com",
  "phone": "09170001111"
}
```

### Registrations

- `GET /api/registrations`
- `POST /api/registrations`
- `DELETE /api/registrations/{id}`

Sample body (`POST`):

```json
{
  "event_id": 1,
  "participant_id": 2
}
```

### Relationship Queries

- `GET /api/events/{id}/participants`
- `GET /api/participants/{id}/events`

### Analytics Queries

- `GET /api/analytics/participants-per-event`
- `GET /api/analytics/most-popular-event`
- `GET /api/analytics/total-registrations`

## Postman Testing Notes

1. Set header `Content-Type: application/json` for `POST`/`PUT`.
2. Use sample JSON bodies from this README.
3. Verify:
   - CRUD returns proper status codes (`200`, `201`, `404`, `422`)
   - Relationship endpoints return joined records
   - Analytics endpoints return aggregate results

## Member Roles and Responsibilities

- Database Designer: `Jwelynie123` (`dgperez@ccc.edu.ph`)
- Model Developer (PHP OOP): `tzahhhahaha` (`jaamado@ccc.edu.ph`)
- CRUD API Developer: `yumaki00` (`abbayanban@ccc.edu.ph`)
- Relationship API Developer: `mpcatedrilla` (`mpcatedrilla@ccc.edu.ph`)
- Data Analytics API Developer: `markmasongsong` (`mcmasongsong@ccc.edu.ph`)
- Documentation and Testing: `rayvenedburato` (`reburato@ccc.edu.ph`)
