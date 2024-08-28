<?php
require_once '../Classes/Usuarios.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];

    if (Usuarios::existsByEmail($email)) {
        echo 'Usuário já existe com este e-mail.';
    } else {
        $user = new Usuarios(null, $name, $matricula, $email, $password, 'pessoal');
        $user->save(); // Salvar o usuário no banco de dados

        header('Location: ./login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
    <link rel="stylesheet" type="text/css" href="../CSS/login.css">
</head>
<body>
    <header>
        <h1>Eventos UFV</h1>
    </header>
    
    <main>
        <section class="register-container">
            <h2>Registrar-se</h2>
            <form action="registro.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" name="name" id="name" required>
                
                <label for="matricula">Matrícula:</label>
                <input type="text" name="matricula" id="matricula" required>
                
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required>
                
                <label for="password">Senha:</label>
                <input type="password" name="password" id="password" required>
                
                <button type="submit">Registrar-se</button>
            </form>
            <p><a href="./login.php">Voltar para o Login</a></p>
        </section>
    </main>
</body>
</html>
