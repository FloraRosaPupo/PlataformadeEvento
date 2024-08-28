<?php
require_once __DIR__ . '/../Data/conexao.php'; // Ajuste o caminho conforme necessário

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    if (is_numeric($userId)) {
        $conn = getConnection(); // Supondo que você tenha uma função getConnection para obter a conexão com o banco de dados

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header('Location: ../Pages/paginaAdmin.php'); // Redireciona de volta para a página de administração após a exclusão
            exit();
        } else {
            // Lidar com falhas na exclusão, como usuário não encontrado
            echo "Erro ao excluir usuário.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "ID de usuário inválido.";
    }
} else {
    echo "Método de solicitação inválido ou parâmetro ausente.";
}
?>
