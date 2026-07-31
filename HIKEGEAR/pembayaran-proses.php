<?php
// ============================================================
//  pembayaran-proses.php — HikeGear | Proses Upload Bukti Bayar
//  Menerima AJAX POST dari pembayaran.php
// ============================================================
session_start();
require_once __DIR__ . '/KONEKSI.PHP';
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/helpers.php';

$user_id = (int)$_SESSION['user_id'];

// ── Validasi Method ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode request tidak valid.']);
    exit;
}

// ── Validasi CSRF ───────────────────────────────────────────
$csrf_input = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || $csrf_input !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Token CSRF tidak valid.']);
    exit;
}

// ── Ambil data ──────────────────────────────────────────────
$kode_pesanan = trim($_POST['kode_pesanan'] ?? '');
$metode_bayar = trim($_POST['metode_bayar'] ?? '');
$file_bukti   = $_FILES['bukti'] ?? null;

if (!$kode_pesanan) {
    echo json_encode(['success' => false, 'message' => 'Kode pesanan tidak ditemukan.']);
    exit;
}

// ── Cek pesanan ─────────────────────────────────────────────
$kode_esc = mysqli_real_escape_string($conn, $kode_pesanan);
$pq = mysqli_query($conn, "SELECT * FROM pesanan WHERE kode_pesanan = '$kode_esc' AND user_id = $user_id");
$pesanan = mysqli_fetch_assoc($pq);

if (!$pesanan) {
    echo json_encode(['success' => false, 'message' => 'Pesanan tidak ditemukan.']);
    exit;
}

// ── COD dan QRIS tidak perlu upload bukti ────────────────────
$cod_qris = ['cod', 'qris'];
if (in_array($metode_bayar, $cod_qris)) {
    // Update status pembayaran langsung
    mysqli_query($conn, "UPDATE pesanan SET 
        status_bayar = 'paid',
        dibayar_at = NOW(),
        status = 'diproses'
        WHERE id = {$pesanan['id']}");

    echo json_encode([
        'success' => true,
        'message' => 'Pesanan berhasil dikonfirmasi!',
        'redirect' => 'selesai.php?kode=' . urlencode($kode_pesanan)
    ]);
    exit;
}

// ── Validasi file upload ─────────────────────────────────────
if (!$file_bukti || $file_bukti['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Harap upload bukti pembayaran.']);
    exit;
}

$max_size = 3 * 1024 * 1024; // 3MB
$allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

$ext = strtolower(pathinfo($file_bukti['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed_ext)) {
    echo json_encode(['success' => false, 'message' => 'Format file harus JPG, PNG, atau PDF.']);
    exit;
}

if ($file_bukti['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'Ukuran file maksimal 3MB.']);
    exit;
}

// ── Simpan file ─────────────────────────────────────────────
$upload_dir = __DIR__ . '/uploads/bukti/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$filename = 'bukti_' . $kode_pesanan . '_' . time() . '.' . $ext;
$dest = $upload_dir . $filename;

if (!move_uploaded_file($file_bukti['tmp_name'], $dest)) {
    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan file.']);
    exit;
}

// ── Update database ─────────────────────────────────────────
$bukti_path = 'uploads/bukti/' . $filename;
$update = mysqli_query($conn, "UPDATE pesanan SET 
    bukti_pembayaran = '$bukti_path',
    status_bayar = 'paid',
    dibayar_at = NOW(),
    status = 'diproses'
    WHERE id = {$pesanan['id']}");

if (!$update) {
    // Hapus file jika gagal update
    @unlink($dest);
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status pesanan.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Bukti pembayaran berhasil dikirim!',
    'redirect' => 'selesai.php?kode=' . urlencode($kode_pesanan)
]);
exit;
