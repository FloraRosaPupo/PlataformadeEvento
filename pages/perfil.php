<?php
session_start();
require_once '../Classes/Usuarios.php';
require_once '../Classes/RegistrarEventos.php';

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

$registeredEvents = [];
if ($user) {
    $registeredEvents = Registrar::getRegisteredEvents($user->getId());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">    
    <link rel="stylesheet" type="text/css" href="../CSS/perfil.css">    
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
    
    <section>
        <div class="user-profile">
            <h2>Perfil</h2>
            <?php if ($user) : ?>
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($user->getNome()); ?></p>
                <p><strong>E-mail:</strong> <?php echo htmlspecialchars($user->getEmail()); ?></p>
                <p><strong>Permissão:</strong> <?php echo htmlspecialchars($user->getUserType()); ?></p>
                <?php if ($user->getUserType() === 'admin') : ?>
                    <a href="../Pages/paginaAdmin.php" class="button">Administração</a>
                <?php endif; ?>
                <?php if ($user->getUserType() === 'pessoal') : ?>
                    <a href="./editarUsuario.php?id=<?php echo $user->getId(); ?>" class="button">Editar Perfil</a>
                <?php endif; ?>
            <?php else : ?>
                <p>Você não está logado!</p>
            <?php endif; ?>
        </div>
        
        <?php if ($user) : ?>
            <h3>Eventos Registrados</h3>
            <?php if (!empty($registeredEvents)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Data</th> 
                            <th>Horário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registeredEvents as $event) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event->getTitle()); ?></td>
                                <td><?php echo htmlspecialchars($event->getDate()); ?></td>
                                <td><?php echo htmlspecialchars($event->getTime()); ?></td>
                                <td class="actions">
                                    <?php if ($user->getUserType() === 'admin') : ?>
                                        <a href="../Pages/editarEvento.php?id=<?php echo htmlspecialchars($event->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="button edit">Editar</a>
                                        
                                    <?php endif; ?>
                                    <form action="../Services/DeletarEventos.php" method="post" style="display:inline;">
                                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="button delete">Excluir</button>
                                        </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="no-events">Nenhum evento registrado.</p>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>
</html>

