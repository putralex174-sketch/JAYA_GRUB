<?php
// ============================================================
//  reset_password.php — Reset semua password user jadi mudah
//  Jalankan: http://localhost/HIKEGEAR/USER/reset_password.php
// ============================================================
$conn = mysqli_connect('localhost', 'root', '', 'HIKEGEAR');
if (!$conn) die("DB gagal: " . mysqli_connect_error());

echo "<h2>Reset Password User</h2>";

// Password baru yang simpel
$pw_baru = '123';
$hash = password_hash($pw_baru, PASSWORD_BCRYPT);

// Update semua user
$sql = "UPDATE users SET password = '$hash'";
if (mysqli_query($conn, $sql)) {
    $affected = mysqli_affected_rows($conn);
    echo "✅ Password semua user ($affected user) direset jadi: <strong>$pw_baru</strong><br>";
} else {
    echo "❌ Gagal: " . mysqli_error($conn) . "<br>";
}

// Tampilkan daftar user
$q = mysqli_query($conn, "SELECT id, email, hp, role FROM users");
echo "<h3>Daftar User:</h3>";
echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse'>";
echo "<tr style='background:#1e6b3c;color:white'><th>ID</th><th>Email</th><th>HP</th><th>Role</th></tr>";
while ($r = mysqli_fetch_assoc($q)) {
    echo "<tr><td>{$r['id']}</td><td>{$r['email']}</td><td>{$r['hp']}</td><td>{$r['role']}</td></tr>";
}
echo "</table>";

echo "<br><br>";
echo "<p style='font-size:18px;font-weight:bold;color:green'>✅ Semua password sekarang: <span style='background:#e8f5ee;padding:5px 15px;border-radius:5px'>123</span></p>";
echo "<p><a href='LOGIN.PHP' style='display:inline-block;padding:12px 24px;background:#1e6b3c;color:white;text-decoration:none;border-radius:8px;font-size:16px;font-weight:bold'>Login Sekarang</a></p>";
echo "<p>Email: <strong>budi@email.com</strong> | Password: <strong>123</strong></p>";
echo "<p>Email: <strong>admin@hikegear.com</strong> | Password: <strong>123</strong></p>";
