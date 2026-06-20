# System losowania miejsc parkingowych

Aplikacja webowa do automatycznego i sprawiedliwego losowania miejsc parkingowych
dla pracowników firmy. Projekt w budowie.

## Cel projektu

Zastąpienie ręcznego przydzielania ograniczonej liczby miejsc parkingowych
przejrzystym systemem losowania, który daje wszystkim zainteresowanym równe szanse oraz przechowuje pełną historię przydziałów.

## Technologie

- Frontend: HTML, CSS, JavaScript
- Backend: PHP 8.x
- Baza danych: MySQL
- Środowisko: Laragon (Apache + PHP + MySQL)
- Narzędzia: VS Code, Git, GitHub

## Funkcjonalność

- [ ] Role użytkowników: Podział na administratora (zarządzanie systemem) oraz pracownika (zgłoszenia do losowania).
- [ ] Zarządzanie kontami: Rejestracja i edycja profili pracowników.
- [ ] Konfiguracja parkingu: Definiowanie liczby dostępnych miejsc oraz zarządzanie listą rezerwową.
- [ ] Mechanizm losowania: Możliwość planowania i przeprowadzania losowań. Algorytm uwzględniający wagi.
- [ ] Historia: Czytelny podgląd wyników bieżacych i poprzednich losowań.

## Podział prac

- Backend — Paulina Stępień
- Frontend — Barbara Dudek

## Instalacja i uruchomienie w środowisku lokalnym

Aby uruchomić aplikację w środowisku lokalnym, wykonaj poniższe kroki. Instrukcja zakłada użycie środowiska Laragon (zalecane dla Windows) lub podobnego.

1. Przygotowanie środowiska
- [ ] Upewnij się, że masz zainstalowane oprogramowanie serwerowe (np. Laragon, XAMPP lub WAMP).
- [ ] Uruchom serwery Apache oraz MySQL.

2. Klonowanie/Kopiowanie plików
Skopiuj zawartość repozytorium projektu do głównego folderu serwera (w Laragonie jest to C:\laragon\www\system_parkingowy).

3. Konfiguracja bazy danych
- [ ] Uruchom panel zarządzania bazą danych (np. phpMyAdmin lub HeidiSQL).
- [ ] Utwórz nową bazę danych o nazwie system_parkingowy (kodowanie utf8mb4_unicode_ci).
- [ ] Zaimportuj plik init.sql znajdujący się w głównym katalogu projektu:
- [ ] W phpMyAdmin: Importuj -> Wybierz plik -> Wykonaj.
- [ ] W konsoli MySQL: mysql -u root -p system_parkingowy < init.sql.

4. Uruchomienie aplikacji
- [ ] Otwórz przeglądarkę internetową i wpisz adres: http://localhost/system_parkingowy/.
- [ ] Aplikacja powinna się załadować. Strona startowa dostępna jest w pliku index.html.
