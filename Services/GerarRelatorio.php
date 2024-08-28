<?php
require_once __DIR__ . '/../Classes/Usuarios.php';
require_once __DIR__ . '/../Classes/Eventos.php';
require_once __DIR__ . '/../Data/conexao.php'; // Para obter a conexão com o banco de dados

function getCategoryName($categoryId) {
    $conn = getConnection();
    $query = "SELECT name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $row['name'] ?? 'Desconhecida';
}

// Obtém o tipo de relatório a partir dos parâmetros da URL, padrão é 'usuarios'
$type = isset($_GET['type']) ? $_GET['type'] : 'usuarios';

// Verifica o tipo de relatório e gera o arquivo CSV correspondente
if ($type === 'usuarios') {
    $filename = "relatorio_usuarios.csv";
    $users = Usuarios::getUsers();

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Nome', 'E-mail', 'Permissão']);

    foreach ($users as $user) {
        fputcsv($output, [$user->getNome(), $user->getEmail(), $user->getUserType()]);
    }

    fclose($output);
} elseif ($type === 'eventos') {
    $filename = "relatorio_eventos.csv";
    $events = Eventos::getAll();

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Título', 'Data', 'Hora', 'Categoria']);

    foreach ($events as $event) {
        // Obtém o nome da categoria usando o ID da categoria
        $categoryName = getCategoryName($event->getCategoryId());

        fputcsv($output, [
            $event->getTitle(),
            $event->getDate(),
            $event->getTime(),
            $categoryName,
        ]);
    }

    fclose($output);
}
exit();
?>
