<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/Eventos.php';


class Registrar {
    private $id;
    private $userId;
    private $eventId;

    public function __construct($userId, $eventId) {
        $this->userId = $userId;
        $this->eventId = $eventId;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getEventId() {
        return $this->eventId;
    }

    // Setters
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setEventId($eventId) {
        $this->eventId = $eventId;
    }

    // Database interaction methods
    public function save() {
        $conn = getConnection();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("INSERT INTO registrations (user_id, event_id) VALUES (?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ii", $this->userId, $this->eventId);

        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
        } else {
            die("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    }

    public static function getById($id) {
        $conn = getConnection();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("SELECT * FROM registrations WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $registration = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $registration ? new Registrar($registration['user_id'], $registration['event_id']) : null;
    }

    public static function createRegistration($userId, $eventId) {
        $registration = new Registrar($userId, $eventId);
        $registration->save();
        return $registration->getId();
    }

    public static function getRegisteredEvents($userId) {
        $conn = getConnection();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("SELECT e.id, e.title, e.description, e.date, e.time, e.location, e.category_id
                                FROM events e 
                                INNER JOIN registrations r ON e.id = r.event_id 
                                WHERE r.user_id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $events = [];
        while ($event = $result->fetch_assoc()) {
            $events[] = new Eventos($event['id'], $event['title'], $event['description'], $event['date'], $event['time'], $event['location'], $event['category_id']);
        }
        $stmt->close();
        $conn->close();

        return $events;
    }

    public static function deleteById($eventId) {
        $conn = getConnection();
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $stmt = $conn->prepare("DELETE FROM registrations WHERE event_id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }
}
?>
