<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';   // wajib login
require_once __DIR__.'/admin_guard.php';  // wajib admin
require_once __DIR__.'/model_pesanan.php';

$flash = '';

// ── Handle update status ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $pesanan_id  = (int)($_POST['pesanan_id'] ?? 0);
    $status_baru = $_POST['status'] ?? '';
    $status_valid = ['pending','konfirmasi','diproses','dikirim','selesai','dibatalkan'];

    if ($pesanan_id && in_array($status_baru, $status_valid)) {
        update_status_pesanan($pdo, $pesanan_id, $status_baru);
        $flash = 'Status pesanan berhasil diperbarui.';
    } else {
        $flash = 'Data tidak valid.';
    }
}

// ── Filter ────────────────────────────────────────────────────────────────────
$filter_status = $_GET['status'] ?? '';
$search        = trim($_GET['q'] ?? '');

$opt = ['per_page' => 50, 'page' => 1];
if ($filter_status) $opt['status'] = $filter_status;
if ($search)        $opt['q']      = $search;

$daftar_pesanan = get_all_pesanan($pdo, $opt);

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
<title>Admin – Kelola Pesanan | HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--accent:#27ae60;
  --gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;--gray-600:#5a7568;--gray-800:#2c3e35;
  --red:#e74c3c;--radius:10px;
}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--gray-50);color:var(--gray-800);font-size:13.5px}
.layout{display:grid;grid-template-columns:220px 1fr;min-height:100vh}
.sidebar{background:var(--green-dark);color:#c8e6d0;padding:24px 0}
.sidebar .brand{padding:0 20px 24px;font-size:17px;font-weight:800;color:white;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px}
.sidebar a{display:flex;align-items:center;gap:10px;padding:12px 20px;color:#c8e6d0;text-decoration:none;font-size:13.5px;font-weight:500;transition:background .15s}
.sidebar a:hover, .sidebar a.active{background:rgba(255,255,255,.08);color:white}
.sidebar .sep{border-top:1px solid rgba(255,255,255,.1);margin:16px 0}
.main{padding:28px 32px}
.wrap{max-width:1300px;margin:0 auto;padding:24px}
.flash{background:var(--green-dark) ;color:white;background:#e8f5ee;color:var(--green);padding:12px 18px;border-radius:8px;margin-bottom:16px;font-weight:600}

.toolbar{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;align-items:center}
.toolbar select, .toolbar input{padding:9px 12px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px}
.toolbar button{padding:9px 16px;background:var(--green);color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer}

table{width:100%;border-collapse:collapse;background:white;border-radius:var(--radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08)}
th,td{padding:11px 14px;text-align:left;border-bottom:1px solid var(--gray-100);font-size:13px}
th{background:var(--gray-50);font-weight:700;color:var(--gray-600);text-transform:uppercase;font-size:11px;letter-spacing:.4px}
tr:last-child td{border-bottom:none}

.pill{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.pill-pending{background:#fff3cd;color:#946200}
.pill-konfirmasi{background:#d6ecff;color:#0b5ed7}
.pill-diproses{background:#fde7c8;color:#b5590a}
.pill-dikirim{background:#d3e9ff;color:#1157b0}
.pill-selesai{background:#e8f5ee;color:var(--green)}
.pill-dibatalkan{background:#fdecea;color:var(--red)}

.status-form{display:flex;gap:6px}
.status-form select{padding:6px 8px;border:1.5px solid var(--gray-200);border-radius:6px;font-size:12.5px}
.status-form button{padding:6px 12px;background:var(--accent);color:white;border:none;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer}
.status-form button:hover{background:var(--green)}
</style>
</head>
<body>

<div class="layout">
  <aside class="sidebar">
    <div class="brand">🏔️ HIKEGEAR ADMIN</div>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="admin_pesanan.php" class="active">📦 Kelola Pesanan</a>
    <a href="admin_produk.php">🛒 Kelola Produk</a>
    <div class="sep"></div>
    <a href="index.php">🌐 Lihat Situs</a>
    <a href="logout.php">🚪 Keluar</a>
  </aside>

  <main class="main">

  <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>

  <h2 style="margin-bottom:16px;color:var(--green-dark)">📦 Kelola Pesanan</h2>

  <form class="toolbar" method="GET">
    <select name="status" onchange="this.form.submit()">
      <option value="">Semua Status</option>
      <?php foreach ($label_status as $val => $lbl): ?>
      <option value="<?= $val ?>" <?= $filter_status===$val?'selected':'' ?>><?= $lbl ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="q" placeholder="Cari kode pesanan / nama..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Cari</button>
  </form>

  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Pelanggan</th>
        <th>Tanggal</th>
        <th>Total</th>
        <th>Status Saat Ini</th>
        <th>Ubah Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($daftar_pesanan)): ?>
      <tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray-600)">Tidak ada pesanan.</td></tr>
      <?php else: foreach ($daftar_pesanan as $p): ?>
      <tr>
        <td><b><?= htmlspecialchars($p['kode_pesanan']) ?></b></td>
        <td><?= htmlspecialchars($p['nama_user']) ?><br><span style="color:var(--gray-600);font-size:11.5px"><?= htmlspecialchars($p['email']) ?></span></td>
        <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
        <td><?= fmt($p['total']) ?></td>
        <td><span class="pill pill-<?= $p['status'] ?>"><?= $label_status[$p['status']] ?? $p['status'] ?></span></td>
        <td>
          <form class="status-form" method="POST">
            <input type="hidden" name="pesanan_id" value="<?= $p['id'] ?>">
            <select name="status">
              <?php foreach ($label_status as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= $p['status']===$val?'selected':'' ?>><?= $lbl ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" name="update_status">Simpan</button>
          </form>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  </main>
</div>

</body>
</html>