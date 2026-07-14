<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';

// ── Statistik ─────────────────────────────────────────────────────────────────
$total_pesanan = (int)$pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();

$per_status = [];
$stmt = $pdo->query("SELECT status, COUNT(*) AS jumlah FROM pesanan GROUP BY status");
foreach ($stmt->fetchAll() as $row) $per_status[$row['status']] = (int)$row['jumlah'];

$total_pendapatan = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM pesanan WHERE status != 'dibatalkan'")->fetchColumn();
$total_produk      = (int)$pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$total_user         = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

// ── Pesanan terbaru (5 terakhir) ─────────────────────────────────────────────
$stmt = $pdo->query("SELECT p.*, u.nama_lengkap AS nama_user
                      FROM pesanan p JOIN users u ON p.user_id = u.id
                      ORDER BY p.created_at DESC LIMIT 5");
$pesanan_terbaru = $stmt->fetchAll();

$label_status = [
    'pending'     => 'Pesanan Dibuat',
    'konfirmasi'  => 'Dikonfirmasi',
    'diproses'    => 'Sedang Dikemas',
    'dikirim'     => 'Dalam Pengiriman',
    'selesai'     => 'Selesai',
    'dibatalkan'  => 'Dibatalkan',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--accent:#27ae60;
  --gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;--gray-600:#5a7568;--gray-800:#2c3e35;
  --red:#e74c3c;--orange:#e67e22;--blue:#0b5ed7;--radius:12px;
}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--gray-50);color:var(--gray-800);font-size:13.5px}

/* Sidebar layout */
.layout{display:grid;grid-template-columns:220px 1fr;min-height:100vh}
.sidebar{background:var(--green-dark);color:#c8e6d0;padding:24px 0}
.sidebar .brand{padding:0 20px 24px;font-size:17px;font-weight:800;color:white;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px}
.sidebar a{display:flex;align-items:center;gap:10px;padding:12px 20px;color:#c8e6d0;text-decoration:none;font-size:13.5px;font-weight:500;transition:background .15s}
.sidebar a:hover, .sidebar a.active{background:rgba(255,255,255,.08);color:white}
.sidebar .sep{border-top:1px solid rgba(255,255,255,.1);margin:16px 0}

.main{padding:28px 32px}
.main h1{font-size:22px;color:var(--green-dark);margin-bottom:4px}
.main .sub{color:var(--gray-600);margin-bottom:24px}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
@media(max-width:900px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
.stat-card{background:white;border-radius:var(--radius);padding:18px 20px;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid var(--gray-200)}
.stat-card .lbl{font-size:11.5px;color:var(--gray-600);text-transform:uppercase;letter-spacing:.4px;font-weight:700;margin-bottom:6px}
.stat-card .val{font-size:24px;font-weight:800;color:var(--green-dark)}
.stat-card.accent .val{color:var(--accent)}

.status-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:28px}
@media(max-width:900px){.status-grid{grid-template-columns:repeat(2,1fr)}}
.status-card{background:white;border-radius:10px;padding:14px;text-align:center;border:1px solid var(--gray-200)}
.status-card .num{font-size:20px;font-weight:800}
.status-card .lbl{font-size:11px;color:var(--gray-600);margin-top:3px}
.status-card.pending .num{color:#946200}
.status-card.konfirmasi .num{color:var(--blue)}
.status-card.diproses .num{color:var(--orange)}
.status-card.dikirim .num{color:#1157b0}
.status-card.selesai .num{color:var(--accent)}

table{width:100%;border-collapse:collapse;background:white;border-radius:var(--radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08)}
th,td{padding:11px 14px;text-align:left;border-bottom:1px solid var(--gray-100);font-size:13px}
th{background:var(--gray-50);font-weight:700;color:var(--gray-600);text-transform:uppercase;font-size:11px}
tr:last-child td{border-bottom:none}
.pill{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.pill-pending{background:#fff3cd;color:#946200}
.pill-konfirmasi{background:#d6ecff;color:var(--blue)}
.pill-diproses{background:#fde7c8;color:var(--orange)}
.pill-dikirim{background:#d3e9ff;color:#1157b0}
.pill-selesai{background:#e8f5ee;color:var(--accent)}
.pill-dibatalkan{background:#fdecea;color:var(--red)}

.section-title{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.section-title h2{font-size:15px;color:var(--green-dark)}
.section-title a{font-size:12.5px;color:var(--accent);font-weight:700;text-decoration:none}
</style>
</head>
<body>

<div class="layout">
  <aside class="sidebar">
    <div class="brand">🏔️ HIKEGEAR ADMIN</div>
    <a href="admin_dashboard.php" class="active">📊 Dashboard</a>
    <a href="admin_pesanan.php">📦 Kelola Pesanan</a>
    <a href="admin_produk.php">🛒 Kelola Produk</a>
    <div class="sep"></div>
    <a href="index.php">🌐 Lihat Situs</a>
    <a href="logout.php">🚪 Keluar</a>
  </aside>

  <main class="main">
    <h1>Dashboard</h1>
    <div class="sub">Halo, <?= htmlspecialchars($_SESSION['nama']) ?> — ringkasan toko HikeGear Anda</div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="lbl">Total Pesanan</div>
        <div class="val"><?= $total_pesanan ?></div>
      </div>
      <div class="stat-card accent">
        <div class="lbl">Total Pendapatan</div>
        <div class="val"><?= fmt($total_pendapatan) ?></div>
      </div>
      <div class="stat-card">
        <div class="lbl">Produk Aktif</div>
        <div class="val"><?= $total_produk ?></div>
      </div>
      <div class="stat-card">
        <div class="lbl">Total Pelanggan</div>
        <div class="val"><?= $total_user ?></div>
      </div>
    </div>

    <div class="section-title"><h2>Pesanan per Status</h2></div>
    <div class="status-grid">
      <?php foreach (['pending','konfirmasi','diproses','dikirim','selesai'] as $s): ?>
      <div class="status-card <?= $s ?>">
        <div class="num"><?= $per_status[$s] ?? 0 ?></div>
        <div class="lbl"><?= $label_status[$s] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="section-title">
      <h2>Pesanan Terbaru</h2>
      <a href="admin_pesanan.php">Lihat semua →</a>
    </div>
    <table>
      <thead>
        <tr><th>Kode</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (empty($pesanan_terbaru)): ?>
        <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--gray-600)">Belum ada pesanan.</td></tr>
        <?php else: foreach ($pesanan_terbaru as $p): ?>
        <tr>
          <td><b><?= htmlspecialchars($p['kode_pesanan']) ?></b></td>
          <td><?= htmlspecialchars($p['nama_user']) ?></td>
          <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
          <td><?= fmt($p['total']) ?></td>
          <td><span class="pill pill-<?= $p['status'] ?>"><?= $label_status[$p['status']] ?? $p['status'] ?></span></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </main>
</div>

</body>
</html>