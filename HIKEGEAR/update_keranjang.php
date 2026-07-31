<?php
session_start();
require_once __DIR__.'/KONEKSI.PHP';
require_once __DIR__.'/auth_guard.php';

$user_id = (int)$_SESSION['user_id'];
$id = (int)($_GET['id'] ?? 0);
$qty = max(1, (int)($_GET['qty'] ?? 1));

if ($id > 0 && $qty > 0) {
    $qty_esc = (int)$qty;
    mysqli_query($conn, "UPDATE keranjang SET qty = $qty_esc WHERE id = $id AND user_id = $user_id");
}

header('Location: KERANJANG.PHP');
exit;

