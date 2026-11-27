<?php
// api.php — найпростіший JSON API
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
$id = $_GET['id'] ?? null;

// Один запис за ідентифікатором: api.php?id=2
if ($id !== null) {
    $command = $repository->findById((int) $id);
    if ($command === null) {
        http_response_code(404);
        echo json_encode(['error' => 'Команду не знайдено'], $flags);
        exit;
    }
    echo json_encode($command->toArray(), $flags);
    exit;
}

// Без параметра id — список усіх команд
$all = array_map(
    fn(Command $c) => $c->toArray(),
    array_values($repository->findAll())
);
echo json_encode($all, $flags);
