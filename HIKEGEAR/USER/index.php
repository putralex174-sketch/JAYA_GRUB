<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php'; // <-- WAJIB LOGIN sebelum lanjut ke bawah ini

// Kalau baris di atas lolos, berarti user sudah login.
// $_SESSION['user_id'] dan $_SESSION['nama'] sudah tersedia di sini.
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beranda – HikeGear</title>
<style>
body{font-family:'Segoe UI',system-ui,sans-serif;background:#f8fafb;margin:0;padding:0}
header{background:#1e6b3c;color:white;padding:16px 32px;display:flex;align-items:center;justify-content:space-between}
header a{color:white;text-decoration:none;font-weight:600}
.wrap{max-width:900px;margin:60px auto;text-align:center;padding:0 20px}
.wrap h1{font-size:28px;color:#1a4731;margin-bottom:10px}
.wrap p{color:#5a7568;margin-bottom:26px}
.menu{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}
.menu a{display:inline-block;padding:14px 26px;background:#27ae60;color:white;border-radius:9px;font-weight:700;text-decoration:none;transition:background .2s}
.menu a:hover{background:#1f9e53}
</style>
</head>
<body>

<header>
  <strong>HIKEGEAR</strong>
  <div>
    Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?> &nbsp;|&nbsp;
    <a href="logout.php">Keluar</a>
  </div>
</header>

<div class="wrap">
  <h1>Selamat Datang di HikeGear 🏔️</h1>
  <p>Anda sudah berhasil login. Silakan mulai berbelanja perlengkapan pendakian.</p>
  <div class="menu">
    <a href="produk.php">Lihat Produk</a>
    <a href="keranjang.php">Keranjang Belanja</a>
    <a href="checkout.php">Checkout</a>
    <?php if (($_SESSION['role'] ?? 'user') === 'admin'): ?>
    <a href="admin_pesanan.php" style="background:#1a4731">🛠️ Kelola Pesanan (Admin)</a>
    <?php endif; ?>
  </div>
</div>

</body>
</html>