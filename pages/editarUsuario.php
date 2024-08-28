<?php
require_once __DIR__ . '/../Classes/Usuarios.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

// Inicialize $userToEdit como null
$userToEdit = null;
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);  // Convertendo para inteiro para evitar injeção de SQL
    $userToEdit = Usuarios::getById($userId);
} else {
    echo "ID do usuário não fornecido!";
    exit();
}

// Verifique se o usuário está definido e tem permissões apropriadas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($userToEdit) {
        $userId = intval($_POST['user_id']);  // Convertendo para inteiro para evitar injeção de SQL
        $name = $_POST['name'];
        $email = $_POST['email'];
        $userType = isset($_POST['user_type']) ? $_POST['user_type'] : $userToEdit->getUserType();  // Mantém o tipo de usuário atual se não fornecido
        $password = !empty($_POST['password']) ? $_POST['password'] : $userToEdit->getSenha();  // Usa a senha existente se não for fornecida

        if ($user instanceof Usuarios && $user->getUserType() === 'admin') {
            // O administrador pode alterar o nome, e-mail e tipo de usuário
            $userToEdit->setNome($name);
            $userToEdit->setEmail($email);
            $userToEdit->setUserType($userType);
            if (!empty($_POST['password'])) {
                $userToEdit->setSenha($password); // Somente atualiza a senha se um novo valor for fornecido
            }
        } elseif ($user instanceof Usuarios && $user->getUserType() === 'pessoal' && $user->getId() == $userId) {
            // Usuário pessoal pode alterar nome, e-mail e senha (apenas seu próprio perfil)
            $userToEdit->setNome($name);
            $userToEdit->setEmail($email);
            if (!empty($_POST['password'])) {
                $userToEdit->setSenha($password); // Somente atualiza a senha se um novo valor for fornecido
            }
        } else {
            // Redirecionar para uma página de erro se o usuário não tiver permissão
            header('Location: ../Services/SemAutorizacao.php');
            exit();
        }

        // Atualiza o usuário
        $userToEdit->save();  // Atualiza o usuário chamando o método save()

        header('Location: ./perfil.php');
        exit();
    } else {
        echo "Usuário para edição não encontrado!";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/perfil.css">
    <style>
        .edit-user-section {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
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

    <section class="edit-user-section">
        <h1>Editar Usuário</h1>

        <?php if ($userToEdit) : ?>
            <form action="./editarUsuario.php?id=<?php echo htmlspecialchars($userToEdit->getId()); ?>" method="post">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userToEdit->getId()); ?>">
                
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" value="<?php echo htmlspecialchars($userToEdit->getMatricula()); ?>" readonly><br>
                
                <label for="name">Nome:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($userToEdit->getNome()); ?>" <?php if ($user->getUserType() !== 'admin' && $user->getId() !== $userToEdit->getId()) echo 'disabled'; ?> required><br>
                
                <label for="email">E-mail:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($userToEdit->getEmail()); ?>" <?php if ($user->getUserType() !== 'admin' && $user->getId() !== $userToEdit->getId()) echo 'disabled'; ?> required><br>
                
                <?php if ($user->getUserType() === 'admin') : ?>
                    <label for="user_type">Permissão:</label>
                    <select name="user_type" required>
                        <option value="pessoal" <?php if ($userToEdit->getUserType() === 'pessoal') echo 'selected'; ?>>Pessoal</option>
                        <option value="admin" <?php if ($userToEdit->getUserType() === 'admin') echo 'selected'; ?>>Administrador</option>
                    </select><br>
                <?php else : ?>
                    <input type="hidden" name="user_type" value="<?php echo htmlspecialchars($userToEdit->getUserType()); ?>">
                <?php endif; ?>
                
                <label for="password">Senha:</label>
                <input type="password" name="password"><br>
                
                <button type="submit">Salvar</button>
            </form>
        <?php else : ?>
            <p>O usuário não foi encontrado!</p>
        <?php endif; ?>
    </section>
</body>
</html>
