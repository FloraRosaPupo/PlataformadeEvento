<?php
require_once __DIR__ . '/../Classes/Usuarios.php';

session_start();

$user = null;
if (isset($_SESSION['user'])) {
    $user = unserialize($_SESSION['user']);
}

if (!isset($_SESSION['user']) || ($user->getUserType() !== 'admin' && $user->getUserType() !== 'pessoal')) {
    header('Location: ../Services/SemAutorizacao.php');
    exit();
}

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $users = Usuarios::getUsers($search);
} else {
    $users = Usuarios::getUsers();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/paginaAdmin.css">
    <style>
        /* Estilos Gerais */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            text-decoration: none;
            color: inherit;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header */
        header {
            background-color: #f5f5dc; 
            color: #333; 
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: flex-start; 
            align-items: center;
            gap: 20px; 
        }

        .header-content h1 {
            font-size: 24px;
            margin: 0;
        }

        /* Navegação */
        nav {
            background-color: #e0dcd0; 
            padding: 10px 20px; 
        }

        nav ul {
            list-style: none; 
            padding: 0; 
            display: flex; 
            gap: 20px; 
            margin: 0;
        }

        nav ul li {
            position: relative;
        }

        nav ul li a {
            color: #333; 
            font-size: 16px; 
            font-weight: 500; 
            text-transform: uppercase; 
            padding: 10px 15px; 
            border-radius: 25px; 
            transition: background-color 0.3s, color 0.3s; 
            display: inline-block; 
        }

        nav ul li a:hover {
            background-color: #d0cfc0; 
            color: #333;
        }

        nav ul li a.active {
            background-color: #c0b8a4; 
            color: #333; 
        }

        /* Estilo geral para os botões */
        .button {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
            border: 1px solid transparent;
        }

        /* Estilo para o botão de Editar */
        .button.edit {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .button.edit:hover {
            background-color: #0056b3;
            border-color: #004494;
        }

        /* Estilo para o botão de Excluir */
        .button.delete {
            background-color: #dc3545;
            color: #fff;
            border-color: #dc3545;
        }

        .button.delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Estilos da Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Formulário de Pesquisa */
        form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        input[type="text"] {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            flex: 1;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Eventos UFV</h1>
        </div>
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

    <main>
        <h1>Usuários</h1>

        <form action="./paginaAdmin.php" method="get">
            <input type="text" name="search" placeholder="Pesquisar por nome">
            <button type="submit">Pesquisar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Permissão</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->getNome(), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user->getUserType(), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="./editarUsuario.php?id=<?php echo htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8'); ?>" class="button edit">Editar</a>
                        <form action="../Services/DeletarUsuario.php" method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" class="button delete">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Baixar Relatórios</h2>
        <div>
            <a href="../Services/GerarRelatorio.php?type=usuarios" class="button">Baixar Relatório de Usuários</a>
            <a href="../Services/GerarRelatorio.php?type=eventos" class="button">Baixar Relatório de Eventos</a>
        </div>
    </main>
</body>
</html>
