<?php
session_start();

// przerywa, jeśli ktoś nie jest zalogowanym pracownikiem
function wymagajPracownika() {
    if (!isset($_SESSION['user_id']) || $_SESSION['rola'] !== 'pracownik') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'blad' => 'Brak dostępu.']);
        exit;
    }
}