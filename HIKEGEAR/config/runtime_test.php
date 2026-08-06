<?php
// ============================================================
//  runtime_test.php — Load semua halaman & deteksi error
//  Jalankan: http://localhost/HIKEGEAR/config/runtime_test.php
// ============================================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

$base = 'http://localhost/HIKEGEAR/USER/';

$pages = [
    'beranda.php',
    'index.php',
    'PRODUK.PHP',
    'KATEGORI.PHP',
    'BRANDA.PHP',
    'PROMO.PHP',
    'ARTIKEL.PHP',
    'TENTANGKAMI.PHP',
    'login.php',
    'register.php',
    'detail-produk.php?id=1',
    'detail-produk.php?id=99',
    'PROMO.PHP',
];

echo "<h2>Halaman Publik (tanpa login)</h2>\n";
foreach ($pages as $p) {
    $url = $base . $p;
    $ctx = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
    $html = @file_get_contents($url, false, $ctx);
    $status = 'OK';
    if ($html === false) {
        $status = '🎯 FAIL - tidak bisa diakses';
    } else {
        // Deteksi pesan error PHP
        if (preg_match('/Fatal error|Parse error|Warning:|Notice:|Uncaught|Undefined variable|mysqli_sql_exception/i', $html)) {
            $status = '⚠️ ERROR DETECTED';
        } elseif (strpos($html, 'Koneksi database gagal') !== false) {
            $status = '⚠️ DB KONEKSI GAGAL';
        }
    }
    $len = $html === false ? 0 : strlen($html);
    echo "<div style='margin:4px 0'>$status &nbsp; <b>$p</b> &nbsp; (".$len." bytes)</div>";
    if ($status !== 'OK') {
        // Tampilkan baris error
        if (preg_match('/(Fatal error[^<]+|Warning[^<]+|Notice[^<]+|mysqli_sql_exception[^<]+)/i', $html, $m)) {
            echo "<div style='color:red;margin-left:20px'>→ ".htmlspecialchars(substr($m[1],0,200))."</div>";
        }
    }
}

echo "<hr><h2>Halaman Admin (perlu login)</h2>\n";
echo "<div style='color:gray'>Admin pages membutuhkan sesi login — akan dialihkan ke login.php (normal).</div>\n";
$admin_pages = ['admin_dashboard.php','admin_produk.php','admin_pesanan.php','admin_kategori.php','admin_brand.php','admin_promo.php','admin_ulasan.php','admin_pelanggan.php','admin_pengguna.php','admin_laporan.php','admin_pengaturan.php','admin_log.php','admin_banner.php','admin_actifity.php'];
foreach ($admin_pages as $ap) {
    $url = $base . $ap;
    $ctx = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
    $html = @file_get_contents($url, false, $ctx);
    if ($html === false) {
        echo "<div style='margin:4px 0'>FAIL <b>$ap</b> - tidak bisa diakses</div>\n";
    } else {
        // Jika redirect ke login, itu OK (guard bekerja)
        if (preg_match('/Fatal error|Parse error|Uncaught|mysqli_sql_exception/i', $html)) {
            echo "<div style='margin:4px 0;color:red'>⚠️ ERROR <b>$ap</b></div>\n";
            if (preg_match('/(Fatal error[^<]+|Uncaught[^<]+|mysqli_sql_exception[^<]+)/i', $html, $m)) {
                echo "<div style='color:red;margin-left:20px'>→ ".htmlspecialchars(substr($m[1],0,200))."</div>\n";
            }
        } else {
            echo "<div style='margin:4px 0;color:gray'>redirect (guard OK) <b>$ap</b></div>\n";
        }
    }
}

echo "<hr><h3>Selesai</h3>\n";
?>
