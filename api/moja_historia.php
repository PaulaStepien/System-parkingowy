<?php
require '../config/auth_user.php';
require '../config/db.php';
wymagajPracownika();
header('Content-Type: application/json');

// wszystkie wyniki tego pracownika, połączone z danymi losowania
$stmt = $pdo->prepare(
    "SELECT l.opis, l.data_losowania, w.status, w.pozycja
     FROM wyniki w
     JOIN losowania l ON l.id = w.losowanie_id
     WHERE w.uzytkownik_id = ?
     ORDER BY l.data_losowania DESC, l.id DESC"
);
$stmt->execute([$_SESSION['user_id']]);

echo json_encode(['ok' => true, 'historia' => $stmt->fetchAll()]);