<?php
require '../config/auth.php';
require '../config/db.php';
wymagajSuperadmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$firmaId = (int)($dane['firma_id'] ?? 0);

// znajdź administratora tej firmy
$stmt = $pdo->prepare("SELECT id, login FROM uzytkownicy WHERE firma_id = ? AND rola = 'admin' LIMIT 1");
$stmt->execute([$firmaId]);
$admin = $stmt->fetch();

if (!$admin) {
    echo json_encode(['ok' => false, 'blad' => 'Ta firma nie ma administratora.']);
    exit;
}

// wygeneruj nowe hasło
$znaki = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$haslo = '';
for ($i = 0; $i < 8; $i++) {
    $haslo .= $znaki[random_int(0, strlen($znaki) - 1)];
}

// zapisz nowe hasło jako hash (stare przestaje działać)
$pdo->prepare('UPDATE uzytkownicy SET haslo_hash = ? WHERE id = ?')
    ->execute([password_hash($haslo, PASSWORD_DEFAULT), $admin['id']]);

echo json_encode(['ok' => true, 'login' => $admin['login'], 'haslo' => $haslo]);