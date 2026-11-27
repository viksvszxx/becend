<?php
// demo_edit.php — демонстрація запису змін назад у CSV
// Працює з КОПІЯМИ файлів у тимчасовій теці, тому оригінали в data/ не змінюються
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Example.php';
require_once __DIR__ . '/classes/Command.php';
require_once __DIR__ . '/classes/CommandRepository.php';

header('Content-Type: text/html; charset=utf-8');

$tmp = sys_get_temp_dir() . '/lab4_demo';
if (!is_dir($tmp)) {
    mkdir($tmp);
}
foreach (['categories', 'commands', 'examples'] as $name) {
    copy(__DIR__ . "/data/$name.csv", "$tmp/$name.csv");
}

$repository = new CommandRepository(
    "$tmp/categories.csv", "$tmp/commands.csv", "$tmp/examples.csv"
);

function show(string $title, string $file): void {
    echo "=== $title ===" . PHP_EOL;
    echo htmlspecialchars(file_get_contents($file)) . PHP_EOL;
}

echo "<pre>";
show('commands.csv ДО змін', "$tmp/commands.csv");

$network = $repository->getCategories()[2];
$repository->update(3, [
    'description' => 'Перевіряє доступність вузла та час відповіді',
]);
$repository->create(
    'netstat', $network, 'Показує мережеві підключення', 'netstat [-a] [-n]'
);
$repository->delete(4);

show('commands.csv ПІСЛЯ змін (update #3, create netstat, delete #4)',
     "$tmp/commands.csv");
show('examples.csv ПІСЛЯ змін (приклад команди #4 видалено разом з нею)',
     "$tmp/examples.csv");
echo "</pre>";
