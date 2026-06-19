<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$dataWejscie = trim($dane['data_losowania'] ?? '');
$typ  = $dane['typ']  ?? 'jednorazowe';
$opis = trim($dane['opis'] ?? '');

$timestamp = strtotime($dataWejscie);
if ($dataWejscie === '' || $timestamp === false) {
    echo json_encode(['ok' => false, 'blad' => 'Podaj poprawną datę i godzinę.']);
    exit;
}
$dataMysql = date('Y-m-d H:i:s', $timestamp);

$dozwoloneTypy = ['jednorazowe', 'dzienne', 'tygodniowe', 'miesieczne'];
if (!in_array($typ, $dozwoloneTypy)) {
    echo json_encode(['ok' => false, 'blad' => 'Nieprawidłowa częstotliwość.']);
    exit;
}

$stmt = $pdo->prepare('SELECT liczba_miejsc, liczba_rezerwowych FROM konfiguracja WHERE firma_id = ? LIMIT 1');
$stmt->execute([mojaFirma()]);
$konfig = $stmt->fetch();
$miejsca     = $konfig ? (int)$konfig['liczba_miejsc'] : 10;
$rezerwowych = $konfig ? (int)$konfig['liczba_rezerwowych'] : 5;

$pdo->prepare(
    'INSERT INTO losowania (firma_id, opis, data_losowania, typ, liczba_miejsc, liczba_rezerwowych, status)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
)->execute([mojaFirma(), $opis ?: null, $dataMysql, $typ, $miejsca, $rezerwowych, 'zaplanowane']);

echo json_encode(['ok' => true]);