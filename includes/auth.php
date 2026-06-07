<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return isset($_SESSION['kullanici_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function currentUser(): array|null {
    if (!isLoggedIn()) return null;
    return [
        'id'            => $_SESSION['kullanici_id'],
        'kullanici_adi' => $_SESSION['kullanici_adi'],
    ];
}
