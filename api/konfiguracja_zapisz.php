<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$liczbaMiejsc      = (int)($dane['liczba_miejsc'] ?? 0);
$liczbaRezerwowych = (int)($dane['liczba_rezerwowych'] ?? 0);

if ($liczbaMiejsc < 1) {
    echo json_encode(['ok' => false, 'blad' => 'Liczba miejsc musi być co najmniej 1.']);
    exit;
}
if ($liczbaRezerwowych < 0) {
    echo json_encode(['ok' => false, 'blad' => 'Liczba rezerwowych nie może być ujemna.']);
    exit;
}

$stmt = $pdo->prepare('SELECT id FROM konfiguracja WHERE firma_id = ? LIMIT 1');
$stmt->execute([mojaFirma()]);
$wiersz = $stmt->fetch();

if ($wiersz) {
    $pdo->prepare('UPDATE konfiguracja SET liczba_miejsc = ?, liczba_rezerwowych = ? WHERE id = ?')
        ->execute([$liczbaMiejsc, $liczbaRezerwowych, $wiersz['id']]);
} else {
    $pdo->prepare('INSERT INTO konfiguracja (firma_id, liczba_miejsc, liczba_rezerwowych) VALUES (?, ?, ?)')
        ->execute([mojaFirma(), $liczbaMiejsc, $liczbaRezerwowych]);
}

echo json_encode(['ok' => true]);