<?php
require '../config/auth.php';
require '../config/db.php';
wymagajSuperadmina();
header('Content-Type: application/json');

$firmy = $pdo->query('SELECT id, nazwa, utworzono FROM firmy ORDER BY id DESC')->fetchAll();

// do każdej firmy dołóż login jej administratora i liczbę pracowników
$loginAdmina = $pdo->prepare("SELECT login FROM uzytkownicy WHERE firma_id = ? AND rola = 'admin' LIMIT 1");
$liczbaPrac  = $pdo->prepare("SELECT COUNT(*) FROM uzytkownicy WHERE firma_id = ? AND rola = 'pracownik'");

foreach ($firmy as &$f) {
    $loginAdmina->execute([$f['id']]);
    $f['login_admina'] = $loginAdmina->fetchColumn() ?: null;

    $liczbaPrac->execute([$f['id']]);
    $f['liczba_pracownikow'] = (int)$liczbaPrac->fetchColumn();
}
unset($f);

echo json_encode(['ok' => true, 'firmy' => $firmy]);