<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
if ($_SESSION['rola'] === 'superadmin') {
    header('Location: superadmin.php');
} elseif ($_SESSION['rola'] === 'admin') {
    header('Location: admin.php');
} else {
    header('Location: uzytkownik.php');
}
exit;