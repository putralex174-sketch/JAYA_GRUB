<?php
/**
 * auth_guard.php – WAJIB LOGIN sebelum bisa lihat halaman manapun.
 * Cara pakai: taruh baris ini PALING ATAS di setiap file halaman
 * (sebelum HTML apapun ditampilkan):
 *
 *   require_once __DIR__.'/auth_guard.php';
 *
 * File ini otomatis redirect ke login.php kalau belum login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    // Simpan halaman tujuan supaya setelah login diarahkan balik ke sini
    $current_page = basename($_SERVER['PHP_SELF']);
    header('Location: login.php?redirect=' . urlencode($current_page));
    exit;
}