<?php

class Registration
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT r.registration_id, r.event_id, e.title AS event_title,
                    r.participant_id, p.full_name AS participant_name, r.registered_at
             FROM registrations r
             INNER JOIN events e ON e.event_id = r.event_id
             INNER JOIN participants p ON p.participant_id = r.participant_id
             ORDER BY r.registration_id'
        );
        return $stmt->fetchAll();
    }

    public function create(array $data): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO registrations (event_id, participant_id)
             VALUES (:event_id, :participant_id)
             RETURNING *'
        );
        $stmt->execute([
            'event_id' => $data['event_id'],
            'participant_id' => $data['participant_id'],
        ]);
        return $stmt->fetch();
    }

    public function delete(int $registrationId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM registrations WHERE registration_id = :registration_id');
        $stmt->execute(['registration_id' => $registrationId]);
        return $stmt->rowCount() > 0;
    }

    public function participantsPerEvent(): array
    {
        $stmt = $this->db->query(
            'SELECT e.event_id, e.title, COUNT(r.registration_id) AS participant_count
             FROM events e
             LEFT JOIN registrations r ON r.event_id = e.event_id
             GROUP BY e.event_id, e.title
             ORDER BY participant_count DESC, e.event_id ASC'
        );
        return $stmt->fetchAll();
    }

    public function mostPopularEvent(): ?array
    {
        $stmt = $this->db->query(
            'SELECT e.event_id, e.title, COUNT(r.registration_id) AS participant_count
             FROM events e
             LEFT JOIN registrations r ON r.event_id = e.event_id
             GROUP BY e.event_id, e.title
             ORDER BY participant_count DESC, e.event_id ASC
             LIMIT 1'
        );
        $event = $stmt->fetch();
        return $event ?: null;
    }

    public function totalRegistrations(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) AS total FROM registrations');
        $row = $stmt->fetch();
        return (int) $row['total'];
    }
}
