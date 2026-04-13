<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Event.php';
require_once __DIR__ . '/../models/Participant.php';
require_once __DIR__ . '/../models/Registration.php';

function jsonResponse(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function requestBody(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) {
        return [];
    }
    $parsed = json_decode($raw, true);
    if (!is_array($parsed)) {
        jsonResponse(400, ['message' => 'Invalid JSON body.']);
    }
    return $parsed;
}

function requireFields(array $payload, array $fields): void
{
    foreach ($fields as $field) {
        if (!array_key_exists($field, $payload) || $payload[$field] === '') {
            jsonResponse(422, ['message' => "Missing required field: {$field}"]);
        }
    }
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $eventModel = new Event($db);
    $participantModel = new Participant($db);
    $registrationModel = new Registration($db);

    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = array_values(array_filter(explode('/', trim($path, '/'))));
    if (!empty($segments) && $segments[0] === 'api') {
        array_shift($segments);
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if (count($segments) === 0) {
        jsonResponse(200, ['message' => 'Group 6 Event Management API is running.']);
    }

    if ($segments[0] === 'events') {
        if (count($segments) === 1 && $method === 'GET') {
            jsonResponse(200, $eventModel->getAll());
        }
        if (count($segments) === 1 && $method === 'POST') {
            $body = requestBody();
            requireFields($body, ['title', 'venue', 'event_date']);
            jsonResponse(201, $eventModel->create($body));
        }
        if (count($segments) === 2 && ctype_digit($segments[1])) {
            $eventId = (int) $segments[1];
            if ($method === 'GET') {
                $event = $eventModel->getById($eventId);
                if (!$event) {
                    jsonResponse(404, ['message' => 'Event not found.']);
                }
                jsonResponse(200, $event);
            }
            if ($method === 'PUT') {
                $body = requestBody();
                requireFields($body, ['title', 'venue', 'event_date']);
                $updated = $eventModel->update($eventId, $body);
                if (!$updated) {
                    jsonResponse(404, ['message' => 'Event not found.']);
                }
                jsonResponse(200, $updated);
            }
            if ($method === 'DELETE') {
                $deleted = $eventModel->delete($eventId);
                if (!$deleted) {
                    jsonResponse(404, ['message' => 'Event not found.']);
                }
                jsonResponse(200, ['message' => 'Event deleted successfully.']);
            }
        }
        if (count($segments) === 3 && ctype_digit($segments[1]) && $segments[2] === 'participants' && $method === 'GET') {
            $eventId = (int) $segments[1];
            $event = $eventModel->getById($eventId);
            if (!$event) {
                jsonResponse(404, ['message' => 'Event not found.']);
            }
            $participants = $eventModel->participantsByEvent($eventId);
            jsonResponse(200, [
                'event' => [
                    'event_id' => $event['event_id'],
                    'title' => $event['title'],
                    'venue' => $event['venue'],
                    'event_date' => $event['event_date'],
                ],
                'participants_count' => count($participants),
                'participants' => $participants,
            ]);
        }
    }

    if ($segments[0] === 'participants') {
        if (count($segments) === 1 && $method === 'GET') {
            jsonResponse(200, $participantModel->getAll());
        }
        if (count($segments) === 1 && $method === 'POST') {
            $body = requestBody();
            requireFields($body, ['full_name', 'email']);
            jsonResponse(201, $participantModel->create($body));
        }
        if (count($segments) === 2 && ctype_digit($segments[1])) {
            $participantId = (int) $segments[1];
            if ($method === 'GET') {
                $participant = $participantModel->getById($participantId);
                if (!$participant) {
                    jsonResponse(404, ['message' => 'Participant not found.']);
                }
                jsonResponse(200, $participant);
            }
            if ($method === 'PUT') {
                $body = requestBody();
                requireFields($body, ['full_name', 'email']);
                $updated = $participantModel->update($participantId, $body);
                if (!$updated) {
                    jsonResponse(404, ['message' => 'Participant not found.']);
                }
                jsonResponse(200, $updated);
            }
            if ($method === 'DELETE') {
                $deleted = $participantModel->delete($participantId);
                if (!$deleted) {
                    jsonResponse(404, ['message' => 'Participant not found.']);
                }
                jsonResponse(200, ['message' => 'Participant deleted successfully.']);
            }
        }
        if (count($segments) === 3 && ctype_digit($segments[1]) && $segments[2] === 'events' && $method === 'GET') {
            $participantId = (int) $segments[1];
            $participant = $participantModel->getById($participantId);
            if (!$participant) {
                jsonResponse(404, ['message' => 'Participant not found.']);
            }
            $events = $participantModel->eventsByParticipant($participantId);
            jsonResponse(200, [
                'participant' => [
                    'participant_id' => $participant['participant_id'],
                    'full_name' => $participant['full_name'],
                    'email' => $participant['email'],
                ],
                'events_count' => count($events),
                'events' => $events,
            ]);
        }
    }

    if ($segments[0] === 'registrations') {
        if (count($segments) === 1 && $method === 'GET') {
            jsonResponse(200, $registrationModel->getAll());
        }
        if (count($segments) === 1 && $method === 'POST') {
            $body = requestBody();
            requireFields($body, ['event_id', 'participant_id']);
            jsonResponse(201, $registrationModel->create($body));
        }
        if (count($segments) === 2 && ctype_digit($segments[1]) && $method === 'DELETE') {
            $registrationId = (int) $segments[1];
            $deleted = $registrationModel->delete($registrationId);
            if (!$deleted) {
                jsonResponse(404, ['message' => 'Registration not found.']);
            }
            jsonResponse(200, ['message' => 'Registration deleted successfully.']);
        }
    }

    if ($segments[0] === 'analytics') {
        if (count($segments) === 2 && $segments[1] === 'participants-per-event' && $method === 'GET') {
            $rows = $registrationModel->participantsPerEvent();
            jsonResponse(200, [
                'total_events' => count($rows),
                'data' => $rows,
            ]);
        }
        if (count($segments) === 2 && $segments[1] === 'most-popular-event' && $method === 'GET') {
            $event = $registrationModel->mostPopularEvent();
            jsonResponse(200, [
                'data' => $event,
                'message' => $event ? 'Most popular event retrieved.' : 'No events found.',
            ]);
        }
        if (count($segments) === 2 && $segments[1] === 'total-registrations' && $method === 'GET') {
            jsonResponse(200, ['total_registrations' => $registrationModel->totalRegistrations()]);
        }
    }

    jsonResponse(404, ['message' => 'Endpoint not found.']);
} catch (PDOException $e) {
    jsonResponse(500, ['message' => 'Database error.', 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    jsonResponse(500, ['message' => 'Server error.', 'error' => $e->getMessage()]);
}
