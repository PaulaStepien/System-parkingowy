<?php
require '../config/auth_user.php';
require '../config/db.php';
wymagajPracownika();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$imie     = trim($dane['imie'] ?? '');
$nazwisko = trim($dane['nazwisko'] ?? '');
$dzial    = trim($dane['dzial'] ?? '');
$nrRej    = trim($dane['nr_rejestracyjny'] ?? '');

$stmt = $pdo->prepare(
    'UPDATE uzytkownicy SET imie = ?, nazwisko = ?, dzial = ?, nr_rejestracyjny = ? WHERE id = ?'
);
$stmt->execute([
    $imie ?: null, $nazwisko ?: null, $dzial ?: null, $nrRej ?: null,
    $_SESSION['user_id']
]);

echo json_encode(['ok' => true]);