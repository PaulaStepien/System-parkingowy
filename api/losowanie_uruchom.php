<?php
require '../config/auth.php';
require '../config/db.php';
require '../config/silnik_losowania.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$id = (int)($dane['id'] ?? 0);

$stmt = $pdo->prepare('SELECT firma_id FROM losowania WHERE id = ?');
$stmt->execute([$id]);
$los = $stmt->fetch();
if (!$los || (int)$los['firma_id'] !== mojaFirma()) {
    echo json_encode(['ok' => false, 'blad' => 'Brak dostępu do tego losowania.']);
    exit;
}

echo json_encode(przeprowadzLosowanie($pdo, $id));