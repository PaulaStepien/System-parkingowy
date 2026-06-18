<?php
session_start();
header('Content-Type: application/json');
require '../config/db.php';

$dane  = json_decode(file_get_contents('php://input'), true);
$login = trim($dane['login'] ?? '');
$haslo = $dane['haslo'] ?? '';

$stmt = $pdo->prepare('SELECT * FROM uzytkownicy WHERE login = ?');
$stmt->execute([$login]);
$user = $stmt->fetch();

if ($user && password_verify($haslo, $user['haslo_hash'])) {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['login']    = $user['login'];
    $_SESSION['rola']     = $user['rola'];
    $_SESSION['firma_id'] = $user['firma_id'];   // NULL dla super-admina
    echo json_encode(['ok' => true, 'rola' => $user['rola']]);
} else {
    echo json_encode(['ok' => false, 'blad' => 'Błędny login lub hasło.']);
}