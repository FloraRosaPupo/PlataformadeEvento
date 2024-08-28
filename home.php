<?php
session_start();

require_once './Classes/Usuarios.php';
require_once './Classes/Eventos.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$events = Eventos::getAll();

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $events = Eventos::searchEvents($query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Eventos UFV</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body>
    <header>
        <h1>Eventos UFV</h1>
    </header>
    
    <nav>
        <ul>
            <li><a href="./home.php">Início</a></li>
            <?php if ($user instanceof Usuarios && $user->getUserType() === 'admin') : ?>
                <li><a href="./Pages/adicionarEventos.php">Adicionar Evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios) : ?>
                <li><a href="./Pages/perfil.php">Perfil</a></li>
                <li><a href="./Services/Deslogar.php">Sair</a></li>
            <?php else : ?>
                <li><a href="./Pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <form class="search-form" action="./home.php" method="GET">
        <input type="text" name="query" placeholder="Pesquisar eventos" value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
        <button type="submit">Pesquisar</button>
    </form>

    <section class="events">
        <?php
            if (!empty($events)) {
                foreach ($events as $event) {
                    $eventId = $event->getId();
                    $eventDetailsUrl = "./Pages/detalhesEvento.php?id=$eventId";
                    echo "<a href='$eventDetailsUrl' class='event'>";
                    echo "<h3>" . $event->getTitle() . "</h3>";
                    echo "<p>Descrição: " . $event->getDescription() . "</p>";
                    echo "<p>Data: " . $event->getDate() . "</p>";
                    echo "<p>Hora: " . $event->getTime() . "</p>";
                    echo "<p>Localização: " . $event->getLocation() . "</p>";
                    echo "</a>";
                } 
            } else {
                echo "<p>Nenhum evento encontrado!</p>";
            }
        ?>
    </section>
    
</body>
</html>
