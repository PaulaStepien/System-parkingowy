<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$id = (int)($dane['id'] ?? 0);

$stmt = $pdo->prepare("SELECT login FROM uzytkownicy WHERE id = ? AND rola = 'pracownik' AND firma_id = ?");
$stmt->execute([$id, mojaFirma()]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['ok' => false, 'blad' => 'Nie znaleziono użytkownika.']);
    exit;
}

$znaki = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$haslo = '';
for ($i = 0; $i < 8; $i++) {
    $haslo .= $znaki[random_int(0, strlen($znaki) - 1)];
}

$pdo->prepare('UPDATE uzytkownicy SET haslo_hash = ? WHERE id = ?')
    ->execute([password_hash($haslo, PASSWORD_DEFAULT), $id]);

echo json_encode(['ok' => true, 'login' => $user['login'], 'haslo' => $haslo]);