<?php
// Run only against the disposable CI database; inserts are rolled back.
if (getenv('RUN_DB_TESTS') !== '1') { fwrite(STDERR, "Set RUN_DB_TESTS=1 for a disposable database.\n"); exit(1); }
require_once __DIR__ . '/../Data/conexao.php';
require_once __DIR__ . '/../features/events/data/EventRepository.php';
$connection = getConnection();
$connection->begin_transaction();
try {
    $connection->query("INSERT INTO categories (name) VALUES ('Repository test')");
    $category = $connection->insert_id;
    $repo = new EventRepository($connection);
    $data = ['title'=>"Evento d'água", 'description'=>'Teste', 'date'=>'2026-10-23', 'time'=>'09:30', 'location'=>'Sala', 'category'=>$category];
    $id = $repo->create($data);
    $data['title'] = 'Atualizado';
    $repo->update($id, $data);
    $row = $connection->query('SELECT title, category_id FROM events WHERE id=' . (int)$id)->fetch_assoc();
    if ($row['title'] !== 'Atualizado' || (int)$row['category_id'] !== $category) throw new RuntimeException('Persistence mismatch');
    echo "Repository integration tests passed\n";
} finally { $connection->rollback(); $connection->close(); }
