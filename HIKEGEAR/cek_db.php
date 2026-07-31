<?php
require_once __DIR__.'/KONEKSI.PHP';

echo "<h2>Struktur tabel pesanan:</h2>";
$q = mysqli_query($conn, "DESCRIBE pesanan");
if ($q) {
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($r = mysqli_fetch_assoc($q)) {
        echo "<tr><td>{$r['Field']}</td><td>{$r['Type']}</td><td>{$r['Null']}</td><td>{$r['Key']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

echo "<h2>Struktur tabel users:</h2>";
$q = mysqli_query($conn, "DESCRIBE users");
if ($q) {
    echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
    while ($r = mysqli_fetch_assoc($q)) {
        echo "<tr><td>{$r['Field']}</td><td>{$r['Type']}</td><td>{$r['Null']}</td><td>{$r['Key']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}

echo "<h2>Data users:</h2>";
$q = mysqli_query($conn, "SELECT id, nama_lengkap, email, hp, role FROM users");
if ($q) {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Nama</th><th>Email</th><th>HP</th><th>Role</th></tr>";
    while ($r = mysqli_fetch_assoc($q)) {
        echo "<tr><td>{$r['id']}</td><td>{$r['nama_lengkap']}</td><td>{$r['email']}</td><td>{$r['hp']}</td><td>{$r['role']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

