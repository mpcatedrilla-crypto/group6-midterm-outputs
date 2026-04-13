-- Group 6 Event Management System (PostgreSQL)
-- Run this file in pgAdmin or psql.

DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS participants;
DROP TABLE IF EXISTS events;

CREATE TABLE events (
    event_id SERIAL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    venue VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE participants (
    participant_id SERIAL PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE registrations (
    registration_id SERIAL PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id)
        REFERENCES events (event_id)
        ON DELETE CASCADE,
    CONSTRAINT fk_registrations_participant
        FOREIGN KEY (participant_id)
        REFERENCES participants (participant_id)
        ON DELETE CASCADE,
    CONSTRAINT uq_event_participant UNIQUE (event_id, participant_id)
);

-- Optional starter data
INSERT INTO events (title, description, venue, event_date)
VALUES
('Tech Summit 2026', 'Technology conference and workshop sessions.', 'CCC Main Hall', '2026-05-20'),
('Career Expo 2026', 'Career and internship networking event.', 'CCC Gymnasium', '2026-06-15');

INSERT INTO participants (full_name, email, phone)
VALUES
('Alice Dela Cruz', 'alice@example.com', '09171234567'),
('Brian Santos', 'brian@example.com', '09181234567'),
('Carla Ramos', 'carla@example.com', '09191234567');

INSERT INTO registrations (event_id, participant_id)
VALUES
(1, 1),
(1, 2),
(2, 3);
