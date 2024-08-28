<?php
require_once '../Classes/Eventos.php';

if (isset($_POST['search_text'])) {
    $searchText = $_POST['search_text'];

    $events = Eventos::searchEvents($searchText);

    if (!empty($events)) {
        foreach ($events as $event) {
            echo '<div class="event">';
            echo '<a class="event-link" href="#" data-event-id="' . $event->getId() . '">';
            echo '<h3>' . $event->getTitle() . '</h3>';
            echo '</a>';
            echo '<div class="event-details hidden">';
            
            echo '<p>' . $event->getDescription() . '</p>';
            echo '<p>Data: ' . $event->getDate() . '</p>';
            echo '<p>Hora: ' . $event->getTime() . '</p>';
            echo '<p>Localização: ' . $event->getLocation() . '</p>';
            
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>Nenhum evento disponível.</p>";
    }
} else {
    echo "<p>Nenhum termo de pesquisa fornecido.</p>";
}
?>
