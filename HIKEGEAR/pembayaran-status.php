<?php
// ============================================================
//  pembayaran-status.php — HikeGear | Polling Status Pembayaran
//  Dipanggil via AJAX dari pembayaran.php
// ============================================================
session_start();
require_once __DIR__ . '/KONEKSI.PHP';
require_once __DIR__ . '/auth_guard.php';

$user_id = (int)$_SESSION['user_id'];
$kode    = trim($_POST['kode'] ?? '');

if (!$kode) {
    echo json_encode(['paid' => false]);
    exit;
}

$kode_esc = mysqli_real_escape_string($conn, $kode);
$q = mysqli_query($conn, "SELECT status_bayar, status FROM pesanan 
                           WHERE kode_pesanan = '$kode_esc' AND user_id = $user_id");
$p = mysqli_fetch_assoc($q);

if (!$p) {
    echo json_encode(['paid' => false]);
    exit;
}

$paid = $p['status_bayar'] === 'paid';

echo json_encode([
    'paid'     => $paid,
    'status_order' => $p['status'],
    'redirect' => $paid ? 'selesai.php?kode=' . urlencode($kode) : null
]);
exit;
