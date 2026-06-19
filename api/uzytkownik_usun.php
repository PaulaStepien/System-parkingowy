<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$id = (int)($dane['id'] ?? 0);

$pdo->prepare("DELETE FROM uzytkownicy WHERE id = ? AND rola = 'pracownik' AND firma_id = ?")
    ->execute([$id, mojaFirma()]);

echo json_encode(['ok' => true]);