<?php
/**
 * reset_password.php — SCRIPT SEKALI PAKAI untuk reset password akun tertentu.
 * CARA PAKAI:
 * 1. Ubah nilai $email_target dan $password_baru di bawah ini.
 * 2. Buka file ini lewat browser: http://localhost/HIKEGEAR/USER/reset_password.php
 * 3. Setelah berhasil, HAPUS file ini dari server (jangan dibiarkan ada, ini celah keamanan kalau dibiarkan).
 */

require_once __DIR__.'/db.php';

$email_target = 'putralex174@gmail.com'; // ganti dengan email akun Anda
$password_baru = 'Password123';           // ganti dengan password baru yang MUDAH DIINGAT (min 8 char, ada huruf besar & angka)

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->execute([$hash, $email_target]);

if ($stmt->rowCount() > 0) {
    echo "<h2 style='font-family:sans-serif;color:green'>Berhasil! Password untuk $email_target sudah direset.</h2>";
    echo "<p style='font-family:sans-serif'>Password baru: <b>$password_baru</b></p>";
    echo "<p style='font-family:sans-serif;color:red'><b>PENTING: Hapus file reset_password.php ini sekarang dari server!</b></p>";
} else {
    echo "<h2 style='font-family:sans-serif;color:red'>Gagal — tidak ada akun dengan email $email_target</h2>";
}