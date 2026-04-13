<?php

class Event
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM events ORDER BY event_date, event_id');
        return $stmt->fetchAll();
    }

    public function getById(int $eventId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM events WHERE event_id = :event_id');
        $stmt->execute(['event_id' => $eventId]);
        $event = $stmt->fetch();
        return $event ?: null;
    }

    public function create(array $data): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO events (title, description, venue, event_date)
             VALUES (:title, :description, :venue, :event_date)
             RETURNING *'
        );
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'venue' => $data['venue'],
            'event_date' => $data['event_date'],
        ]);
        return $stmt->fetch();
    }

    public function update(int $eventId, array $data): ?array
    {
        $stmt = $this->db->prepare(
            'UPDATE events
             SET title = :title,
                 description = :description,
                 venue = :venue,
                 event_date = :event_date
             WHERE event_id = :event_id
             RETURNING *'
        );
        $stmt->execute([
            'event_id' => $eventId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'venue' => $data['venue'],
            'event_date' => $data['event_date'],
        ]);
        $event = $stmt->fetch();
        return $event ?: null;
    }

    public function delete(int $eventId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM events WHERE event_id = :event_id');
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->rowCount() > 0;
    }

    public function participantsByEvent(int $eventId): array
    {
        $stmt = $this->db->prepare(
            'SELECT p.participant_id, p.full_name, p.email, p.phone, r.registered_at
             FROM registrations r
             INNER JOIN participants p ON p.participant_id = r.participant_id
             WHERE r.event_id = :event_id
             ORDER BY r.registered_at DESC'
        );
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll();
    }
}
