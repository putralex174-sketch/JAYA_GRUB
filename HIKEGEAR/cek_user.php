<?php
$conn = mysqli_connect('localhost','root','','HIKEGEAR');
if (!$conn) die("❌ Koneksi gagal: " . mysqli_connect_error());

echo "<h3>=== Tabel: alamat ===</h3>";
$q = mysqli_query($conn, 'DESCRIBE alamat');
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($r = mysqli_fetch_assoc($q)) {
    echo "<tr><td>{$r['Field']}</td><td>{$r['Type']}</td></tr>";
}
echo "</table>";

echo "<h3>=== Tabel: pesanan_detail ===</h3>";
$q2 = mysqli_query($conn, 'DESCRIBE pesanan_detail');
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($r2 = mysqli_fetch_assoc($q2)) {
    echo "<tr><td>{$r2['Field']}</td><td>{$r2['Type']}</td></tr>";
}
echo "</table>";

echo "<h3>=== Tabel: keranjang ===</h3>";
$q3 = mysqli_query($conn, 'DESCRIBE keranjang');
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($r3 = mysqli_fetch_assoc($q3)) {
    echo "<tr><td>{$r3['Field']}</td><td>{$r3['Type']}</td></tr>";
}
echo "</table>";

echo "<h3>=== Tabel: users (kolom penting) ===</h3>";
$q4 = mysqli_query($conn, 'DESCRIBE users');
echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
while($r4 = mysqli_fetch_assoc($q4)) {
    echo "<tr><td>{$r4['Field']}</td><td>{$r4['Type']}</td></tr>";
}
echo "</table>";
?>
