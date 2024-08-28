<?php
session_start();

require_once '../Classes/Usuarios.php';
require_once __DIR__ . '/../Data/conexao.php';
require_once '../Classes/Eventos.php';
require_once '../Classes/Categorias.php'; 

if (!isset($_SESSION['user']) || (unserialize($_SESSION['user'])->getUserType() !== 'admin' && unserialize($_SESSION['user'])->getUserType() !== 'pessoal')) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $eventId = $_GET['id'];

    $event = Eventos::getById($eventId);

    if ($event) {
        // Obtém a lista de categorias
        $categories = Categoria::getAll(); // Corrigido para Categoria

        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Evento</title>
            <link rel="stylesheet" type="text/css" href="../CSS/style.css">
            <link rel="stylesheet" type="text/css" href="../CSS/adicionarEventos.css">
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
                <h2>Editar Evento</h2>
                <form action="../Services/AtualizarEvento.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($eventId, ENT_QUOTES, 'UTF-8'); ?>">
                    <label for="title">Título:</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event->getTitle(), ENT_QUOTES, 'UTF-8'); ?>" required><br>
                    <label for="description">Descrição:</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($event->getDescription(), ENT_QUOTES, 'UTF-8'); ?></textarea><br>
                    <label for="date">Data:</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event->getDate(), ENT_QUOTES, 'UTF-8'); ?>" required><br>
                    <label for="time">Hora:</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($event->getTime(), ENT_QUOTES, 'UTF-8'); ?>" required><br>
                    <label for="location">Localização:</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event->getLocation(), ENT_QUOTES, 'UTF-8'); ?>" required><br>
                    <label for="category">Categoria:</label>
                    <select id="category" name="category" required>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?php echo htmlspecialchars($cat->getId(), ENT_QUOTES, 'UTF-8'); ?>" <?php echo $cat->getId() === $event->getCategoryId() ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
                
                    <button type="button">Atualizar</button>
                </form>


               
            </section>
            <script>
                function confirmDelete() {
                    return confirm("A exclusão é permanente, você tem certeza?");
                }
            </script>
            <footer>
            </footer>
        </body>
        </html>
        <?php
    } else {
        echo "<p>Este evento não está disponível!</p>";
    }
} else {
    echo "<p>404.</p>";
}
?>
