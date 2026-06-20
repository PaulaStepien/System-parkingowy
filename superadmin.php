<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'superadmin') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel super-administratora — System parkingowy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="logo">System parkingowy — super-administrator</div>
        <nav><a href="api/logout.php" class="btn">Wyloguj się</a></nav>
    </header>

    <main class="panel">
        <section class="panel-sekcja">
            <h2>Firmy</h2>
            <p>Dodaj nową firmę — system utworzy ją razem z pierwszym administratorem (login i hasło).</p>

            <label for="nazwaFirmy">Nazwa firmy</label>
            <input type="text" id="nazwaFirmy" class="pole-data" placeholder="np. Acme Sp. z o.o.">

            <button id="przyciskDodajFirme" class="btn">Utwórz firmę</button>

            <div id="noweDaneFirmy" class="nowe-dane" style="display:none;"></div>

            <table class="tabela">
                <thead>
                    <tr>
                        <th>Nazwa firmy</th><th>Administrator</th>
                        <th>Pracownicy</th><th>Utworzono</th><th></th>
                    </tr>
                </thead>
                <tbody id="listaFirm"></tbody>
            </table>
        </section>
    </main>

    <script src="js/superadmin.js"></script>
</body>
</html>