<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

do {
    $login = 'prac' . random_int(1000, 9999);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM uzytkownicy WHERE login = ?');
    $stmt->execute([$login]);
} while ($stmt->fetchColumn() > 0);

$znaki = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$haslo = '';
for ($i = 0; $i < 8; $i++) {
    $haslo .= $znaki[random_int(0, strlen($znaki) - 1)];
}

$pdo->prepare('INSERT INTO uzytkownicy (firma_id, login, haslo_hash, rola) VALUES (?, ?, ?, ?)')
    ->execute([mojaFirma(), $login, password_hash($haslo, PASSWORD_DEFAULT), 'pracownik']);

echo json_encode(['ok' => true, 'login' => $login, 'haslo' => $haslo]);