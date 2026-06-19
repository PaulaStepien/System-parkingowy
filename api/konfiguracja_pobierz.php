<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$stmt = $pdo->prepare('SELECT liczba_miejsc, liczba_rezerwowych FROM konfiguracja WHERE firma_id = ? LIMIT 1');
$stmt->execute([mojaFirma()]);
$konfig = $stmt->fetch();

if ($konfig) {
    echo json_encode([
        'ok' => true,
        'liczba_miejsc' => (int)$konfig['liczba_miejsc'],
        'liczba_rezerwowych' => (int)$konfig['liczba_rezerwowych']
    ]);
} else {
    echo json_encode(['ok' => false, 'blad' => 'Brak konfiguracji.']);
}