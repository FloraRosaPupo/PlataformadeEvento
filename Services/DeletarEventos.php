<?php
session_start();
require_once '../Classes/RegistrarEventos.php';

if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];

    Registrar::deleteById($eventId);

    header('Location: ../Pages/perfil.php');
    exit();
} else {
    header('Location: ../Pages/perfil.php');
    exit();
}
?>