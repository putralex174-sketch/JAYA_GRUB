<?php
/**
 * db.php – Koneksi database HikeGear menggunakan PDO
 * Lokasi file: config/db.php
 * Cara pakai : require_once __DIR__.'/../config/db.php';
 */

define('DB_HOST',    'localhost');
define('DB_NAME',    'hikegear');
define('DB_USER',    'root');      // ← ganti username MySQL kamu
define('DB_PASS',    '');          // ← ganti password MySQL kamu
define('DB_PORT',    '3306');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('<h3 style="font-family:sans-serif;color:#e74c3c">Koneksi database gagal!<br>
        <small style="color:#555">Periksa pengaturan di <code>config/db.php</code></small></h3>');
}
