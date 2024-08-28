<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/../Classes/Usuarios.php';
require_once __DIR__ . '/../Classes/Categorias.php';

session_start();

if (!isset($_SESSION['user']) || !in_array(unserialize($_SESSION['user'])->getUserType(), ['admin', 'pessoal'])) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $categoryId = $_POST['category'];
    
    $conn = getConnection();

    // Verifique se a conexão é válida
    if (!$conn) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    // Ajuste a consulta SQL para refletir o número correto de colunas
    $stmt = $conn->prepare("INSERT INTO events (title, description, date, time, location, category_id) VALUES (?, ?, ?, ?, ?, ?)");

    // Verifique se a preparação da consulta foi bem-sucedida
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Bind dos parâmetros
    $stmt->bind_param('sssssi', $title, $description, $date, $time, $location, $categoryId);

    // Execute a consulta
    if ($stmt->execute()) {
        $event_id = $stmt->insert_id;
        echo "Evento cadastrado com sucesso. ID do Evento: " . $event_id;
    } else {
        echo "Erro ao cadastrar evento: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
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
    <br>
    <br>
    <section>
        <h2>Cadastrar Evento</h2>
       
        <form action="./adicionarEventos.php" method="POST" enctype="multipart/form-data">
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
