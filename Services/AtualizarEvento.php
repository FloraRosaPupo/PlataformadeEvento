<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/Eventos.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eventId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $categoryId = $_POST['category'];
    $event = Eventos::getById($eventId);

    if ($event) {
        $event->setTitle($title);
        $event->setDescription($description);
        $event->setDate($date);
        $event->setTime($time);
        $event->setLocation($location);
        $event->setCategoryId($categoryId);
        $event->save();

        header("Location: ../Pages/detalhesEvento.php?id={$eventId}");
        exit();
    } else {
        echo "<p>Não há eventos.</p>";
    }
} else {
    echo "<p>404</p>";
}
