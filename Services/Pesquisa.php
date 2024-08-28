<?php
require_once '../Classes/Eventos.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $events = Eventos::searchEvents($query);
} else {
    header("Location: index.php");
    exit();
}
?>
