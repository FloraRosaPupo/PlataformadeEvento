<?php
require_once __DIR__ . '/../domain/EventInput.php';
final class EventRepository {
    public function __construct(private mysqli $connection) {}
    public function create(array $input): int {
        $data = EventInput::fromArray($input);
        $stmt = $this->connection->prepare('INSERT INTO events (title, description, date, time, location, category_id) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssi', $data['title'], $data['description'], $data['date'], $data['time'], $data['location'], $data['category']);
        try { $stmt->execute(); return $stmt->insert_id; } finally { $stmt->close(); }
    }
    public function update(int $id, array $input): void {
        if ($id <= 0) throw new InvalidArgumentException('Evento inválido.');
        $data = EventInput::fromArray($input);
        $stmt = $this->connection->prepare('UPDATE events SET title=?, description=?, date=?, time=?, location=?, category_id=? WHERE id=?');
        $stmt->bind_param('sssssii', $data['title'], $data['description'], $data['date'], $data['time'], $data['location'], $data['category'], $id);
        try { $stmt->execute(); } finally { $stmt->close(); }
    }
}
