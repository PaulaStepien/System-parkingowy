// wczyta zapisane dane pracownika
document.addEventListener('DOMContentLoaded', wczytajMojeDane);
document.getElementById('przyciskZapiszDane').addEventListener('click', zapiszDane);
document.getElementById('przyciskZapiszUdzial').addEventListener('click', zapiszUdzial);
document.addEventListener('DOMContentLoaded', wczytajHistorie);

async function wczytajMojeDane() {
    const odpowiedz = await fetch('api/moje_dane_pobierz.php');
    const dane = await odpowiedz.json();
    if (dane.ok) {
        document.getElementById('imie').value = dane.imie ?? '';
        document.getElementById('nazwisko').value = dane.nazwisko ?? '';
        document.getElementById('dzial').value = dane.dzial ?? '';
        document.getElementById('nrRej').value = dane.nr_rejestracyjny ?? '';
        document.getElementById('bierzeUdzial').checked = (dane.bierze_udzial == 1);
    }
}

async function zapiszDane() {
    const odpowiedz = await fetch('api/moje_dane_zapisz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            imie: document.getElementById('imie').value,
            nazwisko: document.getElementById('nazwisko').value,
            dzial: document.getElementById('dzial').value,
            nr_rejestracyjny: document.getElementById('nrRej').value
        })
    });
    const dane = await odpowiedz.json();
    const komunikat = document.getElementById('komunikatDane');
    komunikat.style.color = dane.ok ? '#059669' : '#dc2626';
    komunikat.textContent = dane.ok ? 'Zapisano dane.' : (dane.blad || 'Błąd zapisu.');
}

async function zapiszUdzial() {
    const odpowiedz = await fetch('api/udzial_zapisz.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            bierze_udzial: document.getElementById('bierzeUdzial').checked ? 1 : 0
        })
    });
    const dane = await odpowiedz.json();
    const komunikat = document.getElementById('komunikatUdzial');
    komunikat.style.color = dane.ok ? '#059669' : '#dc2626';

    if (!dane.ok) {
        komunikat.textContent = dane.blad || 'Błąd zapisu.';
    } else if (document.getElementById('bierzeUdzial').checked) {
        komunikat.textContent = 'Zapisano — bierzesz udział w najbliższym losowaniu.';
    } else {
        komunikat.textContent = 'Zapisano — nie bierzesz udziału.';
    }
}

async function wczytajHistorie() {
    const odpowiedz = await fetch('api/moja_historia.php');
    const dane = await odpowiedz.json();
    const tbody = document.getElementById('listaHistorii');
    tbody.innerHTML = '';

    if (!dane.ok || dane.historia.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4">Nie brałeś jeszcze udziału w żadnym zakończonym losowaniu.</td></tr>';
        return;
    }

    dane.historia.forEach(h => {
        const etykieta = h.status === 'wylosowany'
            ? '<span class="badge badge-ok">Wylosowany</span>'
            : '<span class="badge badge-rez">Rezerwowy</span>';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${h.data_losowania.slice(0, 16).replace('T', ' ')}</td>
            <td>${h.opis ?? '—'}</td>
            <td>${h.pozycja}</td>
            <td>${etykieta}</td>
        `;
        tbody.appendChild(tr);
    });
}