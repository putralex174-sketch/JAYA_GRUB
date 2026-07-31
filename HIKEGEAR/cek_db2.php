<?php
require_once __DIR__.'/KONEKSI.PHP';

echo "<pre>";

echo "=== TABEL PESANAN ===\n";
$q = mysqli_query($conn, "SHOW COLUMNS FROM pesanan");
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
        echo $r['Field'] . " - " . $r['Type'] . " - " . ($r['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
} else {
    echo "Gagal: " . mysqli_error($conn) . "\n";
}

echo "\n=== TABEL USERS ===\n";
$q = mysqli_query($conn, "SHOW COLUMNS FROM users");
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
        echo $r['Field'] . " - " . $r['Type'] . " - " . ($r['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
} else {
    echo "Gagal: " . mysqli_error($conn) . "\n";
}

echo "\n=== DATA USERS ===\n";
$q = mysqli_query($conn, "SELECT id, nama_lengkap, email, hp, role, password FROM users");
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
        echo "ID: {$r['id']}, Nama: {$r['nama_lengkap']}, Email: {$r['email']}, HP: {$r['hp']}, Role: {$r['role']}, Password: {$r['password']}\n";
    }
} else {
    echo "Gagal: " . mysqli_error($conn) . "\n";
}

echo "</pre>";
?>

