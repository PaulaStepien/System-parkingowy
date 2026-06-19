// po otwarciu strony wczytanie listy użytkowników
document.addEventListener('DOMContentLoaded', wczytajUzytkownikow);
// przycisk tworzenia nowego użytkownika
document.getElementById('przyciskDodaj').addEventListener('click', dodajUzytkownika);

document.addEventListener('DOMContentLoaded', wczytajKonfiguracje);
document.getElementById('przyciskZapiszKonfig').addEventListener('click', zapiszKonfiguracje);

document.addEventListener('DOMContentLoaded', wczytajLosowania);
document.getElementById('przyciskZaplanuj').addEventListener('click', zaplanujLosowanie);

document.addEventListener('DOMContentLoaded', () => wczytajWyniki());
document.getElementById('wyborLosowania').addEventListener('change', function () {
    wczytajWyniki(this.value);
});

async function wczytajUzytkownikow() {
    const odpowiedz = await fetch('api/uzytkownicy_lista.php');
    const dane = await odpowiedz.json();
    const tbody = document.getElementById('listaUzytkownikow');
    tbody.innerHTML = '';

    if (dane.uzytkownicy.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">Brak użytkowników. Utwórz pierwszego powyżej.</td></tr>';
        return;
    }

    dane.uzytkownicy.forEach(u => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${u.login}</td>
            <td>${u.imie ?? '—'}</td>
            <td>${u.nazwisko ?? '—'}</td>
            <td>${u.dzial ?? '—'}</td>
            <td>${u.nr_rejestracyjny ?? '—'}</td>
            <td>
                <button class="btn-reset" data-id="${u.id}">Nowe hasło</button>
                <button class="btn-usun" data-id="${u.id}">Usuń</button>
            </td>
        `;
        tbody.appendChild(tr);
    });

    document.querySelectorAll('.btn-usun').forEach(btn => {
        btn.addEventListener('click', () => usunUzytkownika(btn.dataset.id));
    });
    document.querySelectorAll('.btn-reset').forEach(btn => {
        btn.addEventListener('click', () => resetHasla(btn.dataset.id));
    });
}

async function dodajUzytkownika() {
    const odpowiedz = await fetch('api/uzytkownik_dodaj.php', { method: 'POST' });
    const dane = await odpowiedz.json();

    if (dane.ok) {
        const box = document.getElementById('noweDane');
        box.style.display = 'block';
        box.innerHTML = `
            Utworzono nowego użytkownika:<br>
            Login: <strong>${dane.login}</strong><br>
            Hasło: <strong>${dane.haslo}</strong><br>
            <small>Zapisz te dane i przekaż je pracownikowi — hasło nie będzie później widoczne.</small>
        `;
        wczytajUzytkownikow();
    } else {
        alert(dane.blad || 'Błąd przy tworzeniu użytkownika.');
    }
}

async function usunUzytkownika(id) {
    if (!confirm('Na pewno usunąć tego użytkownika?')) return;
    const odpowiedz = await fetch('api/uzytkownik_usun.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    });
    const dane = await odpowiedz.json();
    if (dane.ok) wczytajUzytkownikow();
}

async function resetHasla(id) {
    if (!confirm('Wygenerować nowe hasło dla tego użytkownika? Stare przestanie działać.')) return;

    const odpowiedz = await fetch('api/uzytkownik_reset.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    });
    const dane = await odpowiedz.json();

    if (dane.ok) {
        const box = document.getElementById('noweDane');
        box.style.display = 'block';
        box.innerHTML = `
            Nowe hasło dla użytkownika:<br>
            Login: <strong>${dane.login}</strong><br>
            Nowe hasło: <strong>${dane.haslo}</strong><br>
            <small>Przekaż je pracownikowi — stare hasło już nie działa.</small>
        `;
    } else {
        alert(dane.blad || 'Błąd przy generowaniu nowego hasła.');
    }
}
async function wczytajKonfiguracje() {
    const odpowiedz = await fetch('api/konfiguracja_pobierz.php');
    const dane = await odpowiedz.json();
    if (dane.ok) {
        document.getElementById('liczbaMiejsc').value = dane.liczba_miejsc;
        document.getElementById('liczbaRezerwowych').value = dane.liczba_rezerwowych;
    }
}

async function zapiszKonfiguracje() {
    const odpowiedz = await fetch('api/konfiguracja_zapisz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            liczba_miejsc: document.getElementById('liczbaMiejsc').value,
            liczba_rezerwowych: document.getElementById('liczbaRezerwowych').value
        })
    });
    const dane = await odpowiedz.json();
    const komunikat = document.getElementById('komunikatKonfig');
    komunikat.textContent = dane.ok ? 'Zapisano konfigurację.' : (dane.blad || 'Błąd zapisu.');
}

// nazwy częstotliwości i statusów
const typLabels = {
    jednorazowe: 'Jednorazowe', dzienne: 'Codziennie',
    tygodniowe: 'Co tydzień', miesieczne: 'Co miesiąc'
};
const statusLabels = { zaplanowane: 'Zaplanowane', zakonczone: 'Zakończone' };

async function wczytajLosowania() {
    const odpowiedz = await fetch('api/losowania_lista.php');
    const dane = await odpowiedz.json();
    const tbody = document.getElementById('listaLosowan');
    tbody.innerHTML = '';

    if (dane.losowania.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6">Brak zaplanowanych losowań.</td></tr>';
        return;
    }

    dane.losowania.forEach(l => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${l.data_losowania.slice(0, 16).replace('T', ' ')}</td>
            <td>${typLabels[l.typ] ?? l.typ}</td>
            <td>${l.liczba_miejsc}</td>
            <td>${l.liczba_rezerwowych}</td>
            <td>${statusLabels[l.status] ?? l.status}</td>
            <td>${l.status === 'zaplanowane'
                ? `<button class="btn-uruchom" data-id="${l.id}">Uruchom</button>
                       <button class="btn-usun" data-id="${l.id}">Usuń</button>`
                : ''}</td>
        `;
        tbody.appendChild(tr);
    });

    document.querySelectorAll('#listaLosowan .btn-usun').forEach(btn => {
        btn.addEventListener('click', () => usunLosowanie(btn.dataset.id));
    });
    document.querySelectorAll('#listaLosowan .btn-uruchom').forEach(btn => {
        btn.addEventListener('click', () => uruchomLosowanie(btn.dataset.id));
    });
}

async function zaplanujLosowanie() {
    const odpowiedz = await fetch('api/losowanie_dodaj.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            data_losowania: document.getElementById('dataLosowania').value,
            typ: document.getElementById('typLosowania').value,
            opis: document.getElementById('opisLosowania').value
        })
    });
    const dane = await odpowiedz.json();
    const komunikat = document.getElementById('komunikatLosowanie');

    if (dane.ok) {
        komunikat.textContent = 'Zaplanowano losowanie.';
        document.getElementById('opisLosowania').value = '';
        wczytajLosowania();
    } else {
        komunikat.style.color = '#dc2626';
        komunikat.textContent = dane.blad || 'Błąd zapisu.';
    }
}

async function usunLosowanie(id) {
    if (!confirm('Na pewno usunąć to zaplanowane losowanie?')) return;
    const odpowiedz = await fetch('api/losowanie_usun.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    });
    const dane = await odpowiedz.json();
    if (dane.ok) wczytajLosowania();
}

async function uruchomLosowanie(id) {
    if (!confirm('Przeprowadzić losowanie teraz? Tej operacji nie można cofnąć.')) return;

    const odpowiedz = await fetch('api/losowanie_uruchom.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    });
    const dane = await odpowiedz.json();
    const komunikat = document.getElementById('komunikatLosowanie');

    if (dane.ok) {
        komunikat.style.color = '#059669';
        komunikat.textContent =
            `Gotowe! Wylosowano ${dane.liczba_wylosowanych} osób, ` +
            `${dane.liczba_rezerwowych} na liście rezerwowej (zgłoszonych: ${dane.liczba_zgloszonych}).`;
        wczytajLosowania();
    } else {
        komunikat.style.color = '#dc2626';
        komunikat.textContent = dane.blad || 'Błąd losowania.';
    }
}

async function wczytajWyniki(id = 0) {
    const odpowiedz = await fetch('api/wyniki_losowania.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ losowanie_id: id })
    });
    const dane = await odpowiedz.json();

    const select = document.getElementById('wyborLosowania');
    const tbody = document.getElementById('listaWynikow');

    if (dane.losowania.length === 0) {
        select.innerHTML = '<option>Brak zakończonych losowań</option>';
        tbody.innerHTML = '<tr><td colspan="6">Brak wyników — przeprowadź najpierw losowanie.</td></tr>';
        return;
    }

    // budowanie rozwijanej listy 
    if (select.options.length !== dane.losowania.length) {
        select.innerHTML = '';
        dane.losowania.forEach(l => {
            const opcja = document.createElement('option');
            opcja.value = l.id;
            const data = l.data_losowania.slice(0, 16);
            opcja.textContent = (l.opis ? l.opis + ' — ' : '') + data;
            select.appendChild(opcja);
        });
    }
    select.value = dane.wybrane_id;

    // wypełnianie tabelę
    tbody.innerHTML = '';
    dane.wyniki.forEach(w => {
        const etykieta = w.status === 'wylosowany'
            ? '<span class="badge badge-ok">Wylosowany</span>'
            : '<span class="badge badge-rez">Rezerwowy</span>';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${w.pozycja}</td>
            <td>${w.imie ?? '—'}</td>
            <td>${w.nazwisko ?? '—'}</td>
            <td>${w.dzial ?? '—'}</td>
            <td>${w.nr_rejestracyjny ?? '—'}</td>
            <td>${etykieta}</td>
        `;
        tbody.appendChild(tr);
    });
}