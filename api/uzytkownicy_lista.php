<?php
require '../config/auth.php';
require '../config/db.php';
wymagajAdmina();
header('Content-Type: application/json');

$stmt = $pdo->prepare(
    "SELECT id, login, imie, nazwisko, dzial, nr_rejestracyjny
     FROM uzytkownicy
     WHERE rola = 'pracownik' AND firma_id = ?
     ORDER BY id DESC"
);
$stmt->execute([mojaFirma()]);
echo json_encode(['ok' => true, 'uzytkownicy' => $stmt->fetchAll()]);