<?php
function przeprowadzLosowanie(PDO $pdo, int $losowanieId): array {

    $stmt = $pdo->prepare('SELECT * FROM losowania WHERE id = ?');
    $stmt->execute([$losowanieId]);
    $los = $stmt->fetch();

    if (!$los) {
        return ['ok' => false, 'blad' => 'Nie znaleziono losowania.'];
    }
    if ($los['status'] === 'zakonczone') {
        return ['ok' => false, 'blad' => 'To losowanie zostało już przeprowadzone.'];
    }

    $firmaId     = (int)$los['firma_id'];
    $miejsca     = (int)$los['liczba_miejsc'];
    $rezerwowych = (int)$los['liczba_rezerwowych'];
    $ilu = $miejsca + $rezerwowych;

    // kandydaci = pracownicy TEJ firmy z zaznaczonym checkboxem
    $stmt = $pdo->prepare(
        "SELECT id FROM uzytkownicy
         WHERE rola = 'pracownik' AND bierze_udzial = 1 AND firma_id = ?"
    );
    $stmt->execute([$firmaId]);
    $kandydaci = $stmt->fetchAll();

    if (count($kandydaci) === 0) {
        return ['ok' => false, 'blad' => 'Nikt nie zgłosił udziału w losowaniu.'];
    }

    // zakończone losowania TEJ firmy (od najnowszego) — do liczenia passy
    $stmt = $pdo->prepare(
        "SELECT id FROM losowania
         WHERE status = 'zakonczone' AND firma_id = ?
         ORDER BY data_losowania DESC, id DESC"
    );
    $stmt->execute([$firmaId]);
    $zakonczone = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($kandydaci as &$k) {
        $passa = policzPasse($pdo, (int)$k['id'], $zakonczone);
        if ($passa === 0)     { $waga = 1.0; }
        elseif ($passa === 1) { $waga = 0.8; }
        else                  { $waga = 0.6; }
        $k['klucz'] = (mt_rand() / mt_getrandmax()) * $waga;
    }
    unset($k);

    usort($kandydaci, fn($a, $b) => $b['klucz'] <=> $a['klucz']);
    $wybrani = array_slice($kandydaci, 0, $ilu);

    $pdo->beginTransaction();
    $insert = $pdo->prepare(
        'INSERT INTO wyniki (losowanie_id, uzytkownik_id, status, pozycja) VALUES (?, ?, ?, ?)'
    );
    $pozycja = 1;
    foreach ($wybrani as $w) {
        $status = ($pozycja <= $miejsca) ? 'wylosowany' : 'rezerwowy';
        $insert->execute([$losowanieId, $w['id'], $status, $pozycja]);
        $pozycja++;
    }

    $pdo->prepare("UPDATE losowania SET status = 'zakonczone' WHERE id = ?")
        ->execute([$losowanieId]);

    // odznacz checkboxy TYLKO w tej firmie
    $pdo->prepare(
        "UPDATE uzytkownicy SET bierze_udzial = 0
         WHERE rola = 'pracownik' AND bierze_udzial = 1 AND firma_id = ?"
    )->execute([$firmaId]);

    if ($los['typ'] !== 'jednorazowe') {
        $nastepna = nastepnaData($los['data_losowania'], $los['typ']);
        $pdo->prepare(
            'INSERT INTO losowania (firma_id, opis, data_losowania, typ, liczba_miejsc, liczba_rezerwowych, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        )->execute([$firmaId, $los['opis'], $nastepna, $los['typ'], $miejsca, $rezerwowych, 'zaplanowane']);
    }

    $pdo->commit();

    return [
        'ok' => true,
        'liczba_wylosowanych' => min($miejsca, count($wybrani)),
        'liczba_rezerwowych'  => max(0, count($wybrani) - $miejsca),
        'liczba_zgloszonych'  => count($kandydaci)
    ];
}

function policzPasse(PDO $pdo, int $uzytkownikId, array $zakonczone): int {
    $passa = 0;
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM wyniki
         WHERE losowanie_id = ? AND uzytkownik_id = ? AND status = 'wylosowany'"
    );
    foreach ($zakonczone as $losId) {
        $stmt->execute([$losId, $uzytkownikId]);
        if ($stmt->fetchColumn() > 0) { $passa++; }
        else { break; }
    }
    return $passa;
}

function nastepnaData(string $data, string $typ): string {
    $modyfikator = match ($typ) {
        'dzienne'    => '+1 day',
        'tygodniowe' => '+1 week',
        'miesieczne' => '+1 month',
        default      => '+1 day',
    };
    return date('Y-m-d H:i:s', strtotime($data . ' ' . $modyfikator));
}