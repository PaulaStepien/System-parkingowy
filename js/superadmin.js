document.addEventListener('DOMContentLoaded', wczytajFirmy);
document.getElementById('przyciskDodajFirme').addEventListener('click', dodajFirme);

async function wczytajFirmy() {
    const odpowiedz = await fetch('api/firmy_lista.php');
    const dane = await odpowiedz.json();
    const tbody = document.getElementById('listaFirm');
    tbody.innerHTML = '';

    if (!dane.ok || dane.firmy.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5">Brak firm. Utwórz pierwszą powyżej.</td></tr>';
        return;
    }

    dane.firmy.forEach(f => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${f.nazwa}</td>
            <td>${f.login_admina ?? '—'}</td>
            <td>${f.liczba_pracownikow}</td>
            <td>${f.utworzono.slice(0, 10)}</td>
            <td>
                <button class="btn-reset" data-id="${f.id}">Nowe hasło</button>
                <button class="btn-usun" data-id="${f.id}">Usuń</button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    document.querySelectorAll('#listaFirm .btn-usun').forEach(btn => {
        btn.addEventListener('click', () => usunFirme(btn.dataset.id));
    });
    document.querySelectorAll('#listaFirm .btn-reset').forEach(btn => {
        btn.addEventListener('click', () => resetAdmina(btn.dataset.id));
    });
}

async function dodajFirme() {
    const nazwa = document.getElementById('nazwaFirmy').value.trim();
    if (nazwa === '') {
        alert('Podaj nazwę firmy.');
        return;
    }

    const odpowiedz = await fetch('api/firma_dodaj.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nazwa: nazwa })
    });
    const dane = await odpowiedz.json();

    if (dane.ok) {
        const box = document.getElementById('noweDaneFirmy');
        box.style.display = 'block';
        box.innerHTML = `
            Utworzono firmę: <strong>${dane.nazwa}</strong><br>
            Login administratora: <strong>${dane.login}</strong><br>
            Hasło administratora: <strong>${dane.haslo}</strong><br>
            <small>Zapisz te dane i przekaż administratorowi firmy — hasło nie będzie później widoczne.</small>
        `;
        document.getElementById('nazwaFirmy').value = '';
        wczytajFirmy();
    } else {
        alert(dane.blad || 'Błąd przy tworzeniu firmy.');
    }
}

async function usunFirme(id) {
    if (!confirm('Usunąć tę firmę? Znikną też wszyscy jej użytkownicy, losowania i wyniki. Tej operacji nie można cofnąć.')) return;

    const odpowiedz = await fetch('api/firma_usun.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    });
    const dane = await odpowiedz.json();
    if (dane.ok) wczytajFirmy();
    else alert(dane.blad || 'Błąd przy usuwaniu firmy.');
}

async function resetAdmina(firmaId) {
    if (!confirm('Wygenerować nowe hasło dla administratora tej firmy? Stare przestanie działać.')) return;

    const odpowiedz = await fetch('api/firma_reset_admina.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ firma_id: firmaId })
    });
    const dane = await odpowiedz.json();

    if (dane.ok) {
        const box = document.getElementById('noweDaneFirmy');
        box.style.display = 'block';
        box.innerHTML = `
            Nowe hasło administratora firmy:<br>
            Login: <strong>${dane.login}</strong><br>
            Nowe hasło: <strong>${dane.haslo}</strong><br>
            <small>Przekaż je administratorowi — stare hasło już nie działa.</small>
        `;
    } else {
        alert(dane.blad || 'Błąd przy generowaniu nowego hasła.');
    }
}