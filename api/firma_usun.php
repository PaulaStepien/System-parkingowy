<?php
require '../config/auth.php';
require '../config/db.php';
wymagajSuperadmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$id = (int)($dane['id'] ?? 0);

$pdo->prepare('DELETE FROM firmy WHERE id = ?')->execute([$id]);

echo json_encode(['ok' => true]);