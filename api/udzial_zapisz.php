<?php
require '../config/auth_user.php';
require '../config/db.php';
wymagajPracownika();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$bierze = !empty($dane['bierze_udzial']) ? 1 : 0;

$stmt = $pdo->prepare('UPDATE uzytkownicy SET bierze_udzial = ? WHERE id = ?');
$stmt->execute([$bierze, $_SESSION['user_id']]);

echo json_encode(['ok' => true]);