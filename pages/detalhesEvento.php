<?php
session_start();

require_once '../Classes/Usuarios.php';
require_once '../Classes/RegistrarEventos.php';
require_once '../Classes/Eventos.php';
require_once '../Data/conexao.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);
    $event = Eventos::getById($eventId);

    if ($event) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user)) {
            try {
                $registrationId = Registrar::createRegistration($user->getId(), $eventId);
                echo "Inscrição realizada com sucesso!";
            } catch (Exception $e) {
                echo "Erro: " . $e->getMessage();
            }
        }
    } else {
        echo "<p>Evento não encontrado!</p>";
    }
} else {
    echo "<p>ID do evento não fornecido!</p>";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Evento</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/detalhesEventos.css">
</head>
<body>
    <header>
        <h1>Eventos UFV</h1>
    </header>
    <nav>
        <ul>
    
            <li><a href="../home.php">Início</a></li>
            <?php if ($user instanceof Usuarios && $user->getUserType() === 'admin') : ?>
                <li><a href="../Pages/adicionarEventos.php">Adicionar Evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios) : ?>
                <li><a href="../Pages/perfil.php">Perfil</a></li>
                <li><a href="../Services/Deslogar.php">Sair</a></li>
            <?php else : ?>
                <li><a href="../Pages/login.php">Login</a></li>
            <?php endif; ?>

        </ul>
    </nav>
    
    <section class="event-details-card">
        <?php if ($event): ?>
            <h2><?php echo htmlspecialchars($event->getTitle(), ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>Descrição: <?php echo htmlspecialchars($event->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Data: <?php echo htmlspecialchars($event->getDate(), ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Hora: <?php echo htmlspecialchars($event->getTime(), ENT_QUOTES, 'UTF-8'); ?></p>
            <p>Local: <?php echo htmlspecialchars($event->getLocation(), ENT_QUOTES, 'UTF-8'); ?></p>
            <?php if (isset($user)): ?>
                <form action="" method="post">
                    <input type="submit" value="Fazer Inscrição">
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p>Evento não encontrado!</p>
        <?php endif; ?>
    </section>
</body>
</html>
