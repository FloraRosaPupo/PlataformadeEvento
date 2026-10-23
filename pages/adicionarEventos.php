<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/../Classes/Usuarios.php';
require_once __DIR__ . '/../Classes/Categorias.php';

session_start();
require_once __DIR__ . '/../features/events/data/EventRepository.php';
require_once __DIR__ . '/../features/events/presentation/EventAccess.php';
requireEventManager();

if (!isset($_SESSION['user']) || !in_array(unserialize($_SESSION['user'])->getUserType(), ['admin', 'pessoal'])) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireEventCsrf();
    try {
        EventInput::fromArray($_POST);
        $connection = getConnection();
        try { $eventId = (new EventRepository($connection))->create($_POST); }
        finally { $connection->close(); }
        header('Location: detalhesEvento.php?id=' . $eventId, true, 303);
        exit();
    } catch (InvalidArgumentException $error) {
        http_response_code(400);
        echo htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastrar Evento</title>
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
                <li><a href="../pages/adicionarEventos.php">Adicionar Evento</a></li>
            <?php endif; ?>
            <?php if ($user instanceof Usuarios) : ?>
                <li><a href="../pages/perfil.php">Perfil</a></li>
                <li><a href="../Services/Deslogar.php">Sair</a></li>
            <?php else : ?>
                <li><a href="../pages/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <br>
    <br>
    <section>
        <h2>Cadastrar Evento</h2>

        <form action="./adicionarEventos.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['event_csrf'], ENT_QUOTES, 'UTF-8'); ?>">
            <div>
                <label for="title">Nome:</label>
                <input type="text" name="title" id="title" required>
            </div>
            <div>
                <label for="description">Descrição:</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div>
                <label for="date">Data:</label>
                <input type="date" name="date" id="date" required>
            </div>
            <div>
                <label for="time">Hora:</label>
                <input type="time" name="time" id="time" required>
            </div>
            <div>
                <label for="location">Localização:</label>
                <input type="text" name="location" id="location" required>
            </div>
            <div>
                <label for="category">Categoria:</label>

                <?php
                $categories = Categoria::getAll();

                if (!empty($categories)) {
                    echo "<select name='category' id='category' required>";
                    echo "<option value=''>Selecione a categoria</option>";

                    foreach ($categories as $category) {
                        echo "<option value='" . $category->getId() . "'>" . $category->getName() . "</option>";
                    }

                    echo "</select>";
                } else {
                    echo "<p>Sem Categoria</p>";
                }
                ?>
            </div>

            <div>
                <input type="submit" value="Adicionar Evento">
            </div>
        </form>
    </section>
</body>
</html>
