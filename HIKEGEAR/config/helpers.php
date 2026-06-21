<?php
/**
 * helpers.php – Fungsi bantu umum HikeGear
 * require_once __DIR__.'/../config/helpers.php';
 */

// Format rupiah
function fmt(int|float $n): string {
    return 'Rp' . number_format($n, 0, ',', '.');
}

// Slug generator
function slug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
    return preg_replace('/[\s\-]+/', '-', $text);
}

// Escape HTML
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Redirect
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

// Cek login
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

// Wajib login
function require_login(): void {
    if (!is_logged_in()) {
        redirect('/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
}

// Cek admin
function is_admin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Wajib admin
function require_admin(): void {
    if (!is_admin()) {
        redirect('/login.php');
    }
}

// Tanggal Indonesia
function tgl_id(\DateTime|string $date): string {
    $bulan = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $d = is_string($date) ? new \DateTime($date) : $date;
    return $d->format('d') . ' ' . $bulan[(int)$d->format('m')] . ' ' . $d->format('Y');
}

// Bintang rating
function stars(float $r): string {
    $full  = floor($r);
    $half  = ($r - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    return str_repeat('★', $full) . ($half ? '½' : '') . str_repeat('☆', $empty);
}

// Generate kode pesanan
function gen_kode(): string {
    return 'ORD-' . strtoupper(substr(uniqid(), -6)) . rand(10, 99);
}

// Flash message (pakai session)
function set_flash(string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}
function get_flash(): ?array {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
