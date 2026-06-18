<?php
$host = 'localhost';
$baza = 'system_parkingowy';
$user = 'root';
$pass = '';   // w Laragonie hasło root jest puste

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$baza;charset=utf8mb4",
        $user, $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Błąd połączenia z bazą: ' . $e->getMessage());
}