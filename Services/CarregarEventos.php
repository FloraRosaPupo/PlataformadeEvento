<?php
require_once '../Classes/Eventos.php';

if (isset($_POST['category_id'])) {
    $categoryId = $_POST['category_id'];
    $events = Eventos::getEventsByCategory($categoryId);
    $options = "<option value=''>Selecionar evento</option>";
    foreach ($events as $event) {
        $options .= "<option value='" . $event['id'] . "'>" . $event['name'] . "</option>";
    } echo $options;
}
?>
