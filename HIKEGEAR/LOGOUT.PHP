<?php
session_start();

// Hapus session user
unset($_SESSION['user']);

// Hapus cookie remember
if (isset($_COOKIE['remember_identifier'])) {
    setcookie('remember_identifier', '', time() - 3600, '/');
}

// Akhiri session
session_destroy();

header('Location: login.php');
exit;

