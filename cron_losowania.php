<?php
// Skrypt uruchamiany automatycznie (cron) ORAZ testowo z przeglądarki.
// __DIR__ to folder tego pliku — dzięki temu ścieżki działają niezależnie
// od tego, skąd skrypt zostanie wywołany.
require __DIR__ . '/config/db.php';
require __DIR__ . '/config/silnik_losowania.php';

header('Content-Type: text/plain; charset=utf-8');
$teraz = date('Y-m-d H:i:s');

// znajdź zaplanowane losowania, których termin już minął
$stmt = $pdo->prepare(
    "SELECT id FROM losowania
     WHERE status = 'zaplanowane' AND data_losowania <= ?
     ORDER BY data_losowania ASC"
);
$stmt->execute([$teraz]);
$doLosowania = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (count($doLosowania) === 0) {
    echo "[$teraz] Brak losowań do przeprowadzenia.\n";
    exit;
}

foreach ($doLosowania as $id) {
    $wynik = przeprowadzLosowanie($pdo, (int)$id);
    if ($wynik['ok']) {
        echo "[$teraz] Losowanie #$id przeprowadzone: "
           . "{$wynik['liczba_wylosowanych']} wylosowanych, "
           . "{$wynik['liczba_rezerwowych']} rezerwowych.\n";
    } else {
        echo "[$teraz] Losowanie #$id pominięte: {$wynik['blad']}\n";
    }
}