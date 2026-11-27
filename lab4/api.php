<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;
$id = $_GET['id'] ?? null;

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

$all = array_map(
    fn(Command $c) => $c->toArray(),
    array_values($repository->findAll())
);
echo json_encode($all, $flags);
