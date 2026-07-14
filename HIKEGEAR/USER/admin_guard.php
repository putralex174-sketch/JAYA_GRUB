<?php
/**
 * admin_guard.php – WAJIB LOGIN sebagai admin sebelum bisa akses halaman admin.
 * Cara pakai: taruh baris ini PALING ATAS di setiap file halaman admin
 * (setelah auth_guard.php):
 *
 *   require_once __DIR__.'/auth_guard.php';
 *   require_once __DIR__.'/admin_guard.php';
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auth_guard.php sudah memastikan user login; di sini cek role-nya admin
if (($_SESSION['role'] ?? 'user') !== 'admin') {
    header('Location: index.php');
    exit;
}