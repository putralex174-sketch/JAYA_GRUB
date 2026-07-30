<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';
require_once __DIR__.'/admin_actifity.php';

$flash = '';

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("DELETE FROM ulasan WHERE id=?")->execute([$id]);
    catat_log($pdo, (int)$_SESSION['user_id'], 'Hapus ulasan', "ID: $id");
    $flash = 'Ulasan berhasil dihapus.';
}

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['toggle_active'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("UPDATE ulasan SET is_active = 1 - is_active WHERE id=?")->execute([$id]);
    $flash = 'Status ulasan berhasil diperbarui.';
}

$daftar = $pdo->query("SELECT u.*, us.nama_lengkap AS nama_user, p.nama AS nama_produk
                        FROM ulasan u
                        JOIN users us ON u.user_id=us.id
                        JOIN produk p ON u.produk_id=p.id
                        ORDER BY u.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Ulasan - HikeGear Admin</title>
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

.stars{color:#f0ad00}
.ulasan-text{max-width:280px;font-size:12.5px;color:var(--gray-800)}
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
    <a href="admin_laporan.php" class="nav-link"><span class="ic">📈</span>Laporan</a>
    <a href="admin_pengaturan.php" class="nav-link"><span class="ic">⚙️</span>Pengaturan</a>
    <div class="menu-label">Lainnya</div>
    <a href="admin_ulasan.php" class="nav-link active"><span class="ic">⭐</span>Ulasan</a>
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
      <h1>⭐ Kelola Ulasan</h1>
      <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

      <table>
        <thead><tr><th>Produk</th><th>Pengguna</th><th>Rating</th><th>Ulasan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          <?php if (empty($daftar)): ?>
          <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--gray-600)">Belum ada ulasan.</td></tr>
          <?php else: foreach ($daftar as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['nama_produk']) ?></td>
            <td><?= htmlspecialchars($u['nama_user']) ?></td>
            <td class="stars"><?= str_repeat('★', (int)$u['rating']) . str_repeat('☆', 5-(int)$u['rating']) ?></td>
            <td class="ulasan-text"><b><?= htmlspecialchars($u['judul'] ?? '') ?></b><br><?= htmlspecialchars(mb_strimwidth($u['isi'] ?? '', 0, 100, '...')) ?></td>
            <td><span class="pill pill-<?= $u['is_active'] ? 'aktif' : 'nonaktif' ?>"><?= $u['is_active'] ? 'Tampil' : 'Disembunyikan' ?></span></td>
            <td>
              <form method="POST" style="display:inline">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="submit" name="toggle_active" class="act-edit" style="border:none;cursor:pointer"><?= $u['is_active']?'Sembunyikan':'Tampilkan' ?></button>
              </form>
              <form method="POST" style="display:inline" onsubmit="return confirm('Hapus ulasan ini?')">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button type="submit" name="hapus" class="act-hapus">Hapus</button>
              </form>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
