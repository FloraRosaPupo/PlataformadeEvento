<?php
require_once '../Classes/Usuarios.php';
require_once '../Data/conexao.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $error .= 'Por favor, informe o e-mail.<br>';
    }
    if (empty($password)) {
        $error .= 'Por favor, informe a senha.<br>';
    }

    if (empty($error)) {
        // Autenticar usuário
        $user = Usuarios::authenticate($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user'] = serialize($user); 
            header('Location: perfil.php'); 
            exit();
        } else {
            $error = 'Credenciais inválidas.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../CSS/login.css">
</head>
<body>
    <header>
        <h1>Eventos UFV</h1>
    </header>

    <main>
        <section class="login-container">
            <h2>Login</h2>
            <?php if (!empty($error)) : ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST" action="">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
            </form>

            <p>Não possui uma conta? <a href="./registro.php">Registrar-se</a></p>
        </section>
    </main>
</body>
</html>
