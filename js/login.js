// logowanie przez naciśnięcie przycisku
document.getElementById('przyciskLogowania').addEventListener('click', zaloguj);

// logowanie klawiszem Enter w polu hasła
document.getElementById('haslo').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') zaloguj();
});

async function zaloguj() {
    const login = document.getElementById('login').value;
    const haslo = document.getElementById('haslo').value;
    const komunikat = document.getElementById('komunikat');
    komunikat.textContent = '';

    try {
        const odpowiedz = await fetch('api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ login: login, haslo: haslo })
        });

        const dane = await odpowiedz.json();

        if (dane.ok) {
            window.location.href = 'panel.php';   // logowanie udane
        } else {
            komunikat.textContent = dane.blad;     // błąd
        }
    } catch (e) {
        komunikat.textContent = 'Błąd połączenia z serwerem.';
    }
}