<?php
session_start();
require_once __DIR__.'/KONEKSI.PHP';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f8fafb;color:#2c3e35;font-size:13.5px}
.layout{display:grid;grid-template-columns:220px 1fr;min-height:100vh}
.sidebar{background:#1a4731;color:#c8e6d0;padding:24px 0}
.sidebar .brand{padding:0 20px 24px;font-size:17px;font-weight:800;color:white;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px}
.sidebar a{display:flex;align-items:center;gap:10px;padding:12px 20px;color:#c8e6d0;text-decoration:none;font-size:13.5px;font-weight:500;transition:background .15s}
.sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,.08);color:white}
.sidebar .sep{border-top:1px solid rgba(255,255,255,.1);margin:16px 0}
.main{padding:28px 32px}
.main h1{font-size:22px;color:#1a4731;margin-bottom:4px}
.main .sub{color:#5a7568;margin-bottom:24px}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.stat-card{background:white;border-radius:12px;padding:18px 20px;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid #e0e8e4}
.stat-card .lbl{font-size:11.5px;color:#5a7568;text-transform:uppercase;letter-spacing:.4px;font-weight:700;margin-bottom:6px}
.stat-card .val{font-size:24px;font-weight:800;color:#1a4731}
</style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="brand">🏔️ HIKEGEAR ADMIN</div>
    <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
    <a href="admin_pesanan.php">📦 Kelola Pesanan</a>
    <a href="admin_produk.php">🛒 Kelola Produk</a>
    <a href="admin_kategori.php">📁 Kategori</a>
    <a href="admin_pelanggan.php">👥 Pelanggan</a>
    <a href="admin_promo.php">🏷️ Promo</a>
    <div class="sep"></div>
    <a href="index.php">🌐 Lihat Situs</a>
    <a href="LOGOUT.PHP">🚪 Keluar</a>
  </aside>
  <main class="main">
    <h1>Dashboard</h1>
    <div class="sub">Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></div>
    <div class="stat-grid">
      <div class="stat-card"><div class="lbl">Total Pesanan</div><div class="val">-</div></div>
      <div class="stat-card"><div class="lbl">Total Pendapatan</div><div class="val">-</div></div>
      <div class="stat-card"><div class="lbl">Produk Aktif</div><div class="val">-</div></div>
      <div class="stat-card"><div class="lbl">Total Pelanggan</div><div class="val">-</div></div>
    </div>
  </main>
</div>
</body>
</html>
