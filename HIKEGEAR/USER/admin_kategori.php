<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';
require_once __DIR__.'/admin_actifity.php';

$flash = '';
$errors = [];

// Fungsi bantu jika belum ada di helpers
if (!function_exists('slug')) {
    function slug($s) {
        $s = preg_replace('/[^a-z0-9]+/i', '-', $s);
        return strtolower(trim($s, '-'));
    }
}
if (!function_exists('catat_log')) {
    function catat_log($pdo, $user_id, $aksi, $detail = '') {
        try {
            $pdo->prepare("INSERT INTO login_log (user_id, identifier, status, created_at) VALUES (?, ?, 'success', NOW())")->execute([$user_id, $aksi . ' - ' . $detail]);
        } catch (Exception $e) {}
    }
}

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['hapus'])) {
    $id = (int)$_POST['id'];
    $pdo->prepare("DELETE FROM kategori WHERE id=?")->execute([$id]);
    catat_log($pdo, (int)$_SESSION['user_id'], 'Hapus kategori', "ID: $id");
    $flash = 'Kategori berhasil dihapus.';
}

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['simpan'])) {
    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $gambar = trim($_POST['gambar'] ?? '') ?: null;
    $urutan = (int)($_POST['urutan'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!$nama) $errors[] = 'Nama kategori wajib diisi.';

    if (empty($errors)) {
        $slug = slug($nama);
        if ($id) {
            $pdo->prepare("UPDATE kategori SET nama=?, slug=?, deskripsi=?, gambar=?, urutan=?, is_active=? WHERE id=?")
                ->execute([$nama, $slug, $deskripsi, $gambar, $urutan, $is_active, $id]);
            $flash = 'Kategori berhasil diperbarui.';
            catat_log($pdo, (int)$_SESSION['user_id'], 'Update kategori', $nama);
        } else {
            $pdo->prepare("INSERT INTO kategori (nama, slug, deskripsi, gambar, urutan, is_active) VALUES (?,?,?,?,?,?)")
                ->execute([$nama, $slug, $deskripsi, $gambar, $urutan, $is_active]);
            $flash = 'Kategori baru berhasil ditambahkan.';
            catat_log($pdo, (int)$_SESSION['user_id'], 'Tambah kategori', $nama);
        }
    }
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM kategori WHERE id=?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query("SELECT k.*, (SELECT COUNT(*) FROM produk p WHERE p.kategori_id=k.id) AS jml_produk
                        FROM kategori k ORDER BY k.urutan, k.nama")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Kategori - HikeGear Admin</title>
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
.topbar .avatar{width:36px;height:36px;border-radius:50%;background:var(--green-light,#e8f5ee);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-weight:800}

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

.grid-2{display:grid;grid-template-columns:340px 1fr;gap:18px;align-items:start}
@media(max-width:900px){.grid-2{grid-template-columns:1fr}}
.card{background:white;border-radius:var(--radius);box-shadow:0 1px 3px rgba(0,0,0,.06);padding:18px}
.form-group{margin-bottom:12px}
.form-group label{display:block;font-size:12px;font-weight:700;margin-bottom:5px}
.form-group input,.form-group textarea{width:100%;padding:9px 11px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px;font-family:inherit}
.chk-row{display:flex;align-items:center;gap:8px;margin-bottom:14px}
.k-thumb{width:36px;height:36px;border-radius:6px;object-fit:cover;background:var(--gray-100)}
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
    <a href="admin_kategori.php" class="nav-link active"><span class="ic">🗂️</span>Kategori</a>
    <a href="admin_pelanggan.php" class="nav-link"><span class="ic">👥</span>Pelanggan</a>
    <a href="admin_laporan.php" class="nav-link"><span class="ic">📈</span>Laporan</a>
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
      <h1>🗂️ Kelola Kategori</h1>

      <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>
      <?php if ($errors): ?><div class="error-box"><?php foreach($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?></div><?php endif; ?>

      <div class="grid-2">
        <div class="card">
          <h3 style="margin-bottom:14px;color:var(--green-dark)"><?= $edit ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
          <form method="POST">
            <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
            <div class="form-group"><label>Nama Kategori *</label><input type="text" name="nama" value="<?= htmlspecialchars($edit['nama'] ?? '') ?>" required></div>
            <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" rows="3"><?= htmlspecialchars($edit['deskripsi'] ?? '') ?></textarea></div>
            <div class="form-group"><label>URL Gambar</label><input type="text" name="gambar" value="<?= htmlspecialchars($edit['gambar'] ?? '') ?>" placeholder="https://..."></div>
            <div class="form-group"><label>Urutan</label><input type="number" name="urutan" value="<?= $edit['urutan'] ?? 0 ?>"></div>
            <div class="chk-row"><input type="checkbox" name="is_active" id="ia" <?= (!isset($edit) || $edit['is_active']) ? 'checked' : '' ?>><label for="ia" style="margin:0">Aktif</label></div>
            <button type="submit" name="simpan" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah Kategori' ?></button>
            <?php if ($edit): ?><a href="admin_kategori.php" class="btn btn-secondary">Batal</a><?php endif; ?>
          </form>
        </div>

        <table>
          <thead><tr><th>Kategori</th><th>Jml Produk</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            <?php if (empty($daftar)): ?>
            <tr><td colspan="5" style="text-align:center;padding:20px;color:var(--gray-600)">Belum ada kategori.</td></tr>
            <?php else: foreach ($daftar as $k): ?>
            <tr>
              <td style="display:flex;align-items:center;gap:8px">
                <img class="k-thumb" src="<?= htmlspecialchars($k['gambar'] ?: 'https://via.placeholder.com/36') ?>">
                <b><?= htmlspecialchars($k['nama']) ?></b>
              </td>
              <td><?= $k['jml_produk'] ?></td>
              <td><?= $k['urutan'] ?></td>
              <td><span class="pill pill-<?= $k['is_active'] ? 'aktif' : 'nonaktif' ?>"><?= $k['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
              <td>
                <a href="?edit=<?= $k['id'] ?>" class="act-edit">Edit</a>
                <form method="POST" style="display:inline" onsubmit="return confirm('Hapus kategori ini?')">
                  <input type="hidden" name="id" value="<?= $k['id'] ?>">
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
</div>
</body>
</html>
