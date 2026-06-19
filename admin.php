<?php
session_start();
// dostęp tylko dla administratora
if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'admin') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel administratora — System parkingowy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="logo">System parkingowy — panel administratora</div>
        <nav><a href="api/logout.php" class="btn">Wyloguj się</a></nav>
    </header>

    <main class="panel">
        <section class="panel-sekcja" style="margin-bottom:24px;">
            <h2>Konfiguracja parkingu</h2>
            <p>Ustaw liczbę miejsc parkingowych oraz wielkość listy rezerwowej.</p>

            <label for="liczbaMiejsc">Liczba miejsc parkingowych</label>
            <input type="number" id="liczbaMiejsc" min="1" class="pole-num">

            <label for="liczbaRezerwowych">Liczba osób na liście rezerwowej</label>
            <input type="number" id="liczbaRezerwowych" min="0" class="pole-num">

            <button id="przyciskZapiszKonfig" class="btn">Zapisz konfigurację</button>
            <p id="komunikatKonfig" class="komunikat-ok"></p>
        </section>
        <section class="panel-sekcja" style="margin-bottom:24px;">
            <h2>Losowania</h2>
            <p>Zaplanuj termin losowania. Wybierz datę i godzinę z kalendarza oraz częstotliwość.</p>

            <label for="dataLosowania">Data i godzina losowania</label>
            <input type="datetime-local" id="dataLosowania" class="pole-data">

            <label for="typLosowania">Częstotliwość</label>
            <select id="typLosowania" class="pole-data">
                <option value="jednorazowe">Jednorazowe</option>
                <option value="dzienne">Codziennie</option>
                <option value="tygodniowe">Co tydzień</option>
                <option value="miesieczne">Co miesiąc</option>
            </select>

            <label for="opisLosowania">Opis (opcjonalnie)</label>
            <input type="text" id="opisLosowania" class="pole-data" placeholder="np. Losowanie na lipiec">

            <button id="przyciskZaplanuj" class="btn">Zaplanuj losowanie</button>
            <p id="komunikatLosowanie" class="komunikat-ok"></p>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Data i godzina</th><th>Częstotliwość</th>
                        <th>Miejsca</th><th>Rezerwowi</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody id="listaLosowan"></tbody>
            </table>
        </section>
        <section class="panel-sekcja" style="margin-bottom:24px;">
            <h2>Wyniki losowania</h2>
            <p>Wybierz losowanie, aby zobaczyć, kto otrzymał miejsce parkingowe.</p>

            <label for="wyborLosowania">Losowanie</label>
            <select id="wyborLosowania" class="pole-data"></select>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Poz.</th><th>Imię</th><th>Nazwisko</th>
                        <th>Dział</th><th>Nr rej.</th><th>Status</th>
                    </tr>
                </thead>
                <tbody id="listaWynikow"></tbody>
            </table>
        </section>
        <section class="panel-sekcja">
            <h2>Użytkownicy</h2>
            <p>Kliknij, aby utworzyć nowego pracownika — system wygeneruje unikatowy login i hasło.</p>
            <button id="przyciskDodaj" class="btn">Utwórz nowego użytkownika</button>

            <div id="noweDane" class="nowe-dane" style="display:none;"></div>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Login</th><th>Imię</th><th>Nazwisko</th>
                        <th>Dział</th><th>Nr rej.</th><th></th>
                    </tr>
                </thead>
                <tbody id="listaUzytkownikow"></tbody>
            </table>
        </section>
    </main>

    <script src="js/admin.js"></script>
</body>
</html>