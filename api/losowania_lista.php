<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$stmt = $pdo->prepare(
    'SELECT id, opis, data_losowania, typ, liczba_miejsc, liczba_rezerwowych, status
     FROM losowania WHERE firma_id = ? ORDER BY data_losowania DESC'
);
$stmt->execute([mojaFirma()]);
echo json_encode(['ok' => true, 'losowania' => $stmt->fetchAll()]);