<?php
require_once '../Classes/Eventos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];

    $events = Eventos::getEventsByCategory($categoryId);

    if (!empty($events)) {
        foreach ($events as $event) {
            echo '<div class="event">';
                echo '<a class="event-link" href="#" data-event-id="' . $event['id'] . '">';
                echo '<h3>' . $event['title'] . '</h3>';
            echo '</a>';
            echo '<div class="event-details hidden">';
                echo '<p>' . $event['description'] . '</p>';
                echo '<p>Data: ' . $event['date'] . '</p>';
                echo '<p>Hora: ' . $event['time'] . '</p>';
                echo '<p>Localização: ' . $event['location'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>Não há eventos!</p>';
    }
}
?>
