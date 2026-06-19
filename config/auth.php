<?php
session_start();

// wymaga zalogowanego administratora firmy
function wymagajAdmina() {
    if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'blad' => 'Brak dostępu.']);
        exit;
    }
}

// wymaga zalogowanego super-administratora
function wymagajSuperadmina() {
    if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'superadmin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'blad' => 'Brak dostępu.']);
        exit;
    }
}

// id firmy zalogowanego administratora
function mojaFirma(): int {
    return (int)($_SESSION['firma_id'] ?? 0);
}