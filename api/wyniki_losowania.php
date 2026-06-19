<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$dane = json_decode(file_get_contents('php://input'), true);
$wybraneId = (int)($dane['losowanie_id'] ?? 0);

$stmt = $pdo->prepare(
    "SELECT id, opis, data_losowania FROM losowania
     WHERE status = 'zakonczone' AND firma_id = ?
     ORDER BY data_losowania DESC, id DESC"
);
$stmt->execute([mojaFirma()]);
$losowania = $stmt->fetchAll();

if (count($losowania) === 0) {
    echo json_encode(['ok' => true, 'losowania' => [], 'wyniki' => []]);
    exit;
}

// wybrane losowanie musi należeć do tej firmy; w razie czego bierzemy najnowsze
$dozwolone = array_map('intval', array_column($losowania, 'id'));
if ($wybraneId === 0 || !in_array($wybraneId, $dozwolone)) {
    $wybraneId = (int)$losowania[0]['id'];
}

$stmt = $pdo->prepare(
    "SELECT w.pozycja, w.status, u.imie, u.nazwisko, u.dzial, u.nr_rejestracyjny
     FROM wyniki w
     JOIN uzytkownicy u ON u.id = w.uzytkownik_id
     WHERE w.losowanie_id = ?
     ORDER BY w.pozycja ASC"
);
$stmt->execute([$wybraneId]);

echo json_encode([
    'ok' => true,
    'wybrane_id' => $wybraneId,
    'losowania' => $losowania,
    'wyniki' => $stmt->fetchAll()
]);