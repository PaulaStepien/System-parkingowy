<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'pracownik') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel pracownika — System parkingowy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="logo">System parkingowy</div>
        <nav><a href="api/logout.php" class="btn">Wyloguj się</a></nav>
    </header>

    <main class="panel">
        <section class="panel-sekcja" style="margin-bottom:24px;">
            <h2>Moje dane</h2>
            <p>Uzupełnij swoje dane. Numer rejestracyjny możesz zmienić w każdej chwili.</p>

            <label for="imie">Imię</label>
            <input type="text" id="imie" class="pole-data">

            <label for="nazwisko">Nazwisko</label>
            <input type="text" id="nazwisko" class="pole-data">

            <label for="dzial">Dział</label>
            <input type="text" id="dzial" class="pole-data">

            <label for="nrRej">Numer rejestracyjny</label>
            <input type="text" id="nrRej" class="pole-data" placeholder="np. SK 12345">

            <button id="przyciskZapiszDane" class="btn">Zapisz dane</button>
            <p id="komunikatDane" class="komunikat-ok"></p>
        </section>

        <section class="panel-sekcja">
            <h2>Udział w losowaniu</h2>
            <p>Zaznacz, jeśli chcesz wziąć udział w najbliższym losowaniu miejsca parkingowego.</p>

            <label class="checkbox-wiersz">
                <input type="checkbox" id="bierzeUdzial">
                <span>Chcę wziąć udział w losowaniu</span>
            </label>
            <p class="uwaga">Uwaga: po każdym losowaniu trzeba zaznaczyć to ponownie przed kolejnym.</p>

            <button id="przyciskZapiszUdzial" class="btn">Zapisz</button>
            <p id="komunikatUdzial" class="komunikat-ok"></p>
        </section>
        <section class="panel-sekcja" style="margin-top:24px;">
            <h2>Historia losowań</h2>
            <p>Twój udział w dotychczasowych zakończonych losowaniach.</p>

            <table class="tabela">
                <thead>
                    <tr><th>Data</th><th>Opis</th><th>Pozycja</th><th>Wynik</th></tr>
                </thead>
                <tbody id="listaHistorii"></tbody>
            </table>
        </section>
    </main>

    <script src="js/uzytkownik.js"></script>
</body>
</html>