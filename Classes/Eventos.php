<?php
require_once __DIR__ . '/Categorias.php';

class Eventos {
    private $id;
    private $title;
    private $date;
    private $time;
    private $categoryId;
    private $description; // Supondo que você tenha uma descrição
    private $location;    // Supondo que você tenha uma localização


    public function __construct($id, $title, $date, $time, $categoryId, $description = null, $location = null) {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->time = $time;
        $this->categoryId = $categoryId;
        $this->description = $description;
        $this->location = $location;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function getCategory() {
        return Categoria::getById($this->categoryId);
    }

    public function getDescription() {
        return $this->description;
    }

    public function getLocation() {
        return $this->location;
    }

   
    public static function getAll() {
        $conn = getConnection();
        
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $sql = "SELECT id, title, date, time, category_id, description, location FROM events";
        $result = $conn->query($sql);

        if (!$result) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        $events = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $event = new Eventos(
                    $row['id'],
                    $row['title'],
                    $row['date'],
                    $row['time'],
                    $row['category_id'],
                    $row['description'],
                    $row['location'],
               
                );
                $events[] = $event;
            }
        }

        $conn->close();
        return $events;
    }

    public static function getById($id) {
        $conn = getConnection();

        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $sql = "SELECT id, title, date, time, category_id, description, location FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $event = new Eventos(
                $row['id'],
                $row['title'],
                $row['date'],
                $row['time'],
                $row['category_id'],
                $row['description'],
                $row['location'],
       
            );
            $conn->close();
            return $event;
        } else {
            $conn->close();
            return null;
        }
    }

    public static function searchEvents($query) {
        $conn = getConnection();
        
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }

        $sql = "SELECT id, title, date, time, category_id, description, location FROM events WHERE title LIKE ? OR description LIKE ?";
        $stmt = $conn->prepare($sql);
        $searchQuery = "%{$query}%";
        $stmt->bind_param('ss', $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        $events = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $event = new Eventos(
                    $row['id'],
                    $row['title'],
                    $row['date'],
                    $row['time'],
                    $row['category_id'],
                    $row['description'],
                    $row['location'],
                    
                );
                $events[] = $event;
            }
        }

        $conn->close();
        return $events;
    }
}
?>
