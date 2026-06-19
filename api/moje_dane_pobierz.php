<?php
require '../config/auth_user.php';
require '../config/db.php';
wymagajPracownika();
header('Content-Type: application/json');

$stmt = $pdo->prepare(
    'SELECT imie, nazwisko, dzial, nr_rejestracyjny, bierze_udzial
     FROM uzytkownicy WHERE id = ?'
);
$stmt->execute([$_SESSION['user_id']]);
$dane = $stmt->fetch();

echo json_encode(array_merge(['ok' => true], $dane));