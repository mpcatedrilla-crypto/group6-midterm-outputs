<?php

class Participant
{
    public function __construct(private PDO $db)
    {
    }

    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM participants ORDER BY participant_id');
        return $stmt->fetchAll();
    }

    public function getById(int $participantId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM participants WHERE participant_id = :participant_id');
        $stmt->execute(['participant_id' => $participantId]);
        $participant = $stmt->fetch();
        return $participant ?: null;
    }

    public function create(array $data): array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO participants (full_name, email, phone)
             VALUES (:full_name, :email, :phone)
             RETURNING *'
        );
        $stmt->execute([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);
        return $stmt->fetch();
    }

    public function update(int $participantId, array $data): ?array
    {
        $stmt = $this->db->prepare(
            'UPDATE participants
             SET full_name = :full_name,
                 email = :email,
                 phone = :phone
             WHERE participant_id = :participant_id
             RETURNING *'
        );
        $stmt->execute([
            'participant_id' => $participantId,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);
        $participant = $stmt->fetch();
        return $participant ?: null;
    }

    public function delete(int $participantId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM participants WHERE participant_id = :participant_id');
        $stmt->execute(['participant_id' => $participantId]);
        return $stmt->rowCount() > 0;
    }

    public function eventsByParticipant(int $participantId): array
    {
        $stmt = $this->db->prepare(
            'SELECT e.event_id, e.title, e.venue, e.event_date, r.registered_at
             FROM registrations r
             INNER JOIN events e ON e.event_id = r.event_id
             WHERE r.participant_id = :participant_id
             ORDER BY e.event_date ASC'
        );
        $stmt->execute(['participant_id' => $participantId]);
        return $stmt->fetchAll();
    }
}
