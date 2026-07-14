<?php
session_start();

// Hapus semua data session
$_SESSION = [];
session_destroy();

// Hapus cookie remember (kalau ada)
if (isset($_COOKIE['remember_identifier'])) {
    setcookie('remember_identifier', '', time() - 3600, '/');
}

// Arahkan ke halaman login (path relatif, tanpa garis miring di depan)
header('Location: login.php');
exit;