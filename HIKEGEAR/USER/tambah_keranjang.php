<?php
/**
 * tambah_keranjang.php – Endpoint AJAX untuk tombol "Tambah ke Keranjang"
 * Dipanggil lewat fetch() dari JavaScript, mengembalikan JSON.
 */
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/model_keranjang.php';

header('Content-Type: application/json');

// Wajib login (tanpa redirect HTML, karena ini dipanggil via AJAX)
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'msg' => 'Silakan login terlebih dahulu.', 'need_login' => true]);
    exit;
}

$produk_id = (int)($_POST['produk_id'] ?? 0);
$qty       = max(1, (int)($_POST['qty'] ?? 1));

if (!$produk_id) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Produk tidak valid.']);
    exit;
}

try {
    tambah_keranjang($pdo, (int)$_SESSION['user_id'], $produk_id, $qty);

    // Hitung ulang total item di keranjang untuk update badge di header
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(qty),0) AS total FROM keranjang WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total = (int)$stmt->fetchColumn();

    echo json_encode(['ok' => true, 'msg' => 'Berhasil ditambahkan ke keranjang!', 'cart_total' => $total]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Gagal menambahkan ke keranjang: ' . $e->getMessage()]);
}