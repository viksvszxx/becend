<?php
header('Content-Type: text/html; charset=utf-8');

$ch = curl_init('http://localhost/lab4/api.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Помилка cURL: ' . curl_error($ch);
    exit;
}
curl_close($ch);

$commands = json_decode($response, true);

echo "<pre>";
echo "=== Довідник команд (отримано через cURL) ===" . PHP_EOL;
foreach ($commands as $c) {
    echo htmlspecialchars(sprintf(
        "#%d %s [%s] — %s",
        $c['id'], $c['name'], $c['category'], $c['description']
    )) . PHP_EOL;
    echo htmlspecialchars("    Синтаксис: " . $c['syntax']) . PHP_EOL;
    foreach ($c['examples'] as $e) {
        echo htmlspecialchars("    Приклад: " . $e['code']) . PHP_EOL;
    }
}
echo "</pre>";
