<?php
require '../config/auth.php';
require '../config/db.php';
wymagajSuperadmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$nazwa = trim($dane['nazwa'] ?? '');

if ($nazwa === '') {
    echo json_encode(['ok' => false, 'blad' => 'Podaj nazwę firmy.']);
    exit;
}

// wygeneruj unikatowy login administratora (np. admin5821)
do {
    $login = 'admin' . random_int(1000, 9999);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM uzytkownicy WHERE login = ?');
    $stmt->execute([$login]);
} while ($stmt->fetchColumn() > 0);

// wygeneruj hasło
$znaki = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$haslo = '';
for ($i = 0; $i < 8; $i++) {
    $haslo .= $znaki[random_int(0, strlen($znaki) - 1)];
}

// firma + admin + domyślna konfiguracja jako jedna całość
$pdo->beginTransaction();

$pdo->prepare('INSERT INTO firmy (nazwa) VALUES (?)')->execute([$nazwa]);
$firmaId = (int)$pdo->lastInsertId();

$pdo->prepare('INSERT INTO uzytkownicy (firma_id, login, haslo_hash, rola) VALUES (?, ?, ?, ?)')
    ->execute([$firmaId, $login, password_hash($haslo, PASSWORD_DEFAULT), 'admin']);

$pdo->prepare('INSERT INTO konfiguracja (firma_id, liczba_miejsc, liczba_rezerwowych) VALUES (?, ?, ?)')
    ->execute([$firmaId, 10, 5]);

$pdo->commit();

echo json_encode(['ok' => true, 'nazwa' => $nazwa, 'login' => $login, 'haslo' => $haslo]);