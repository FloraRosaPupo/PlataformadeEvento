<?php
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/../Classes/Eventos.php';
require_once __DIR__ . '/../features/events/data/EventRepository.php';
require_once __DIR__ . '/../features/events/presentation/EventAccess.php';
requireEventManager();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Método não permitido.'); }
requireEventCsrf();
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if ($id === false) { http_response_code(400); exit('Evento inválido.'); }
try {
    EventInput::fromArray($_POST);
    if (!Eventos::getById($id)) { http_response_code(404); exit('Evento não encontrado.'); }
    $connection = getConnection();
    try { (new EventRepository($connection))->update($id, $_POST); } finally { $connection->close(); }
    header('Location: ../pages/detalhesEvento.php?id=' . $id, true, 303);
    exit();
} catch (InvalidArgumentException $error) {
    http_response_code(400); echo htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8');
}
