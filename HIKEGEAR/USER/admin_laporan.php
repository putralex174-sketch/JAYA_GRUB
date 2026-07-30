<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';

$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT COUNT(*) AS jml, COALESCE(SUM(total),0) AS pendapatan
                        FROM pesanan WHERE created_at BETWEEN ? AND ? AND status != 'dibatalkan'");
$stmt->execute([$dari.' 00:00:00', $sampai.' 23:59:59']);
$ringkasan = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT DATE(created_at) AS tgl, COUNT(*) AS jml, SUM(total) AS total
                         FROM pesanan WHERE created_at BETWEEN ? AND ? AND status != 'dibatalkan'
                         GROUP BY DATE(created_at) ORDER BY tgl DESC");
$stmt2->execute([$dari.' 00:00:00', $sampai.' 23:59:59']);
$per_hari = $stmt2->fetchAll();

$stmt3 = $pdo->prepare("SELECT pd.nama_produk, SUM(pd.qty) AS total_qty, SUM(pd.subtotal) AS total_omzet
                         FROM pesanan_detail pd JOIN pesanan p ON pd.pesanan_id=p.id
                         WHERE p.created_at BETWEEN ? AND ? AND p.status != 'dibatalkan'
                         GROUP BY pd.nama_produk ORDER BY total_qty DESC LIMIT 10");
$stmt3->execute([$dari.' 00:00:00', $sampai.' 23:59:59']);
$produk_terlaris = $stmt3->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan - HikeGear Admin</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --sidebar-bg:#101a14;--green-dark:#1a4731;--green:#1e6b3c;--accent:#27ae60;
  --gray-50:#f7f8fa;--gray-100:#f0f2f4;--gray-200:#e2e6ea;--gray-600:#667085;--gray-800:#1d2939;
  --red:#e74c3c;--orange:#e67e22;--blue:#0b5ed7;--radius:12px;
}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--gray-50);color:var(--gray-800);font-size:13.5px}
a{text-decoration:none;color:inherit}

.layout{display:grid;grid-template-columns:230px 1fr;min-height:100vh}
.sidebar{background:var(--sidebar-bg);color:#9fb3a8;padding:20px 0;display:flex;flex-direction:column}
.sidebar .brand{display:flex;align-items:center;gap:10px;padding:0 20px 20px;color:white;font-size:17px;font-weight:800;border-bottom:1px solid rgba(255,255,255,.08);margin-bottom:14px}
.sidebar .brand .mtn{width:30px;height:30px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px}
.sidebar .menu-label{padding:14px 20px 6px;font-size:10.5px;letter-spacing:.6px;color:#5f7568;text-transform:uppercase;font-weight:700}
.sidebar a.nav-link{display:flex;align-items:center;gap:11px;padding:10px 20px;color:#b6c7bd;font-size:13px;font-weight:500;transition:all .15s;border-left:3px solid transparent}
.sidebar a.nav-link:hover{background:rgba(255,255,255,.05);color:white}
.sidebar a.nav-link.active{background:var(--green);color:white;border-left-color:var(--accent)}
.sidebar a.nav-link .ic{width:18px;text-align:center;flex-shrink:0}

.admin-main{display:flex;flex-direction:column;min-height:100vh}
.topbar{background:white;border-bottom:1px solid var(--gray-200);padding:14px 28px;display:flex;align-items:center;justify-content:flex-end;gap:20px}
.topbar .who{font-size:13px;text-align:right}
.topbar .who strong{display:block;color:var(--gray-800)}
.topbar .who span{color:var(--gray-600);font-size:11.5px}
.topbar .avatar{width:36px;height:36px;border-radius:50%;background:#e8f5ee;color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-weight:800}

.content{padding:26px 30px;flex:1}
.content h1{font-size:21px;color:var(--green-dark);margin-bottom:4px}
.content .sub{color:var(--gray-600);margin-bottom:22px;font-size:13px}

.flash{background:#e8f5ee;color:var(--green);padding:12px 18px;border-radius:8px;margin-bottom:16px;font-weight:600}
.error-box{background:#fdecea;color:var(--red);padding:12px 18px;border-radius:8px;margin-bottom:16px}

table{width:100%;border-collapse:collapse;background:white;border-radius:var(--radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06)}
th,td{padding:11px 14px;text-align:left;border-bottom:1px solid var(--gray-100);font-size:12.8px;vertical-align:middle}
th{background:var(--gray-50);font-weight:700;color:var(--gray-600);text-transform:uppercase;font-size:10.5px}
tr:last-child td{border-bottom:none}

.pill{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.pill-pending{background:#fff3cd;color:#946200}
.pill-konfirmasi{background:#d6ecff;color:var(--blue)}
.pill-diproses{background:#fde7c8;color:var(--orange)}
.pill-dikirim{background:#d3e9ff;color:#1157b0}
.pill-selesai{background:#e8f5ee;color:var(--accent)}
.pill-dibatalkan{background:#fdecea;color:var(--red)}
.pill-aktif{background:#e8f5ee;color:var(--accent)}
.pill-nonaktif{background:var(--gray-100);color:var(--gray-600)}

.btn{padding:9px 16px;border:none;border-radius:8px;font-weight:700;font-size:12.5px;cursor:pointer;text-decoration:none;display:inline-block}
.btn-primary{background:var(--green);color:white}
.btn-primary:hover{background:var(--accent)}
.btn-secondary{background:var(--gray-100);color:var(--gray-800)}
.act-edit{background:#d6ecff;color:var(--blue);padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700}
.act-hapus{background:#fdecea;color:var(--red);padding:5px 10px;border-radius:6px;font-size:11px;font-weight:700;border:none;cursor:pointer}

.stat-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:22px}
.stat-card{background:white;border-radius:var(--radius);padding:18px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
.stat-card .lbl{font-size:12px;color:var(--gray-600);font-weight:600;margin-bottom:6px}
.stat-card .val{font-size:26px;font-weight:800;color:var(--green-dark)}
.filter-bar{display:flex;gap:10px;align-items:center;margin-bottom:18px;background:white;padding:14px 16px;border-radius:var(--radius);box-shadow:0 1px 3px rgba(0,0,0,.06)}
.filter-bar input{padding:8px 10px;border:1.5px solid var(--gray-200);border-radius:7px;font-size:12.5px}
.filter-bar button{padding:8px 16px;background:var(--green);color:white;border:none;border-radius:7px;font-weight:700;cursor:pointer;font-size:12.5px}
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:18px}
@media(max-width:900px){.two-col{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="brand"><div class="mtn">⛰️</div>HikeGear</div>
    <div class="menu-label">Menu Utama</div>
    <a href="admin_dashboard.php" class="nav-link"><span class="ic">📊</span>Dashboard</a>
    <a href="admin_pesanan.php" class="nav-link"><span class="ic">🧾</span>Pesanan</a>
    <a href="admin_produk.php" class="nav-link"><span class="ic">🛒</span>Produk</a>
    <a href="admin_kategori.php" class="nav-link"><span class="ic">🗂️</span>Kategori</a>
    <a href="admin_pelanggan.php" class="nav-link"><span class="ic">👥</span>Pelanggan</a>
    <a href="admin_laporan.php" class="nav-link active"><span class="ic">📈</span>Laporan</a>
    <a href="admin_pengaturan.php" class="nav-link"><span class="ic">⚙️</span>Pengaturan</a>
    <div class="menu-label">Lainnya</div>
    <a href="admin_ulasan.php" class="nav-link"><span class="ic">⭐</span>Ulasan</a>
    <a href="admin_promo.php" class="nav-link"><span class="ic">🏷️</span>Promo</a>
    <a href="admin_banner.php" class="nav-link"><span class="ic">🖼️</span>Banner</a>
    <a href="admin_pengguna.php" class="nav-link"><span class="ic">👤</span>Pengguna</a>
    <a href="admin_log.php" class="nav-link"><span class="ic">📜</span>Log Aktivitas</a>
    <div style="margin-top:auto"></div>
    <a href="logout.php" class="nav-link"><span class="ic">🚪</span>Keluar</a>
  </aside>

  <div class="admin-main">
    <div class="topbar">
      <div class="who"><strong><?= htmlspecialchars($_SESSION['nama']) ?></strong><span>Super Admin</span></div>
      <div class="avatar"><?= strtoupper(substr($_SESSION['nama'],0,1)) ?></div>
    </div>

    <div class="content">
      <h1>📈 Laporan Penjualan</h1>
      <div class="sub">Ringkasan performa toko HikeGear</div>

      <form class="filter-bar" method="GET">
        <label style="font-size:12px;font-weight:600">Dari:</label>
        <input type="date" name="dari" value="<?= htmlspecialchars($dari) ?>">
        <label style="font-size:12px;font-weight:600">Sampai:</label>
        <input type="date" name="sampai" value="<?= htmlspecialchars($sampai) ?>">
        <button type="submit">Terapkan</button>
      </form>

      <div class="stat-grid">
        <div class="stat-card"><div class="lbl">Jumlah Transaksi</div><div class="val"><?= $ringkasan['jml'] ?></div></div>
        <div class="stat-card"><div class="lbl">Total Pendapatan</div><div class="val"><?= fmt($ringkasan['pendapatan']) ?></div></div>
      </div>

      <div class="two-col">
        <table>
          <thead><tr><th>Tanggal</th><th>Jml Transaksi</th><th>Total</th></tr></thead>
          <tbody>
            <?php if (empty($per_hari)): ?>
            <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--gray-600)">Tidak ada data.</td></tr>
            <?php else: foreach ($per_hari as $r): ?>
            <tr><td><?= date('d M Y', strtotime($r['tgl'])) ?></td><td><?= $r['jml'] ?></td><td><?= fmt($r['total']) ?></td></tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>

        <table>
          <thead><tr><th>Produk Terlaris</th><th>Terjual</th><th>Omzet</th></tr></thead>
          <tbody>
            <?php if (empty($produk_terlaris)): ?>
            <tr><td colspan="3" style="text-align:center;padding:20px;color:var(--gray-600)">Tidak ada data.</td></tr>
            <?php else: foreach ($produk_terlaris as $r): ?>
            <tr><td><?= htmlspecialchars($r['nama_produk']) ?></td><td><?= $r['total_qty'] ?></td><td><?= fmt($r['total_omzet']) ?></td></tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body>
</html>
