<?php
session_start();
require_once __DIR__.'/KONEKSI.PHP';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';

$flash = '';
$errors = [];

// ── HAPUS PRODUK ─────────────────────────────────────────────
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM produk WHERE id = $id")) {
        $flash = '✅ Produk berhasil dihapus.';
    } else {
        $flash = '❌ Gagal menghapus: ' . mysqli_error($conn);
    }
}

// ── SIMPAN / EDIT PRODUK ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id        = (int)($_POST['id'] ?? 0);
    $nama      = trim($_POST['nama'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $harga     = (int)str_replace(['.', ' '], '', $_POST['harga'] ?? 0);
    $harga_coret = (int)str_replace(['.', ' '], '', $_POST['harga_coret'] ?? 0);
    $stok      = (int)($_POST['stok'] ?? 0);
    $berat     = (int)($_POST['berat_gram'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $badge     = trim($_POST['badge'] ?? '');
    $gambar    = trim($_POST['gambar_utama'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!$nama) $errors[] = 'Nama produk wajib diisi.';
    if (!$kategori_id) $errors[] = 'Kategori wajib dipilih.';
    if ($harga <= 0) $errors[] = 'Harga harus lebih dari 0.';

    if (empty($errors)) {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nama));
        $slug = trim($slug, '-');

        if ($id > 0) {
            // UPDATE
            $sql = "UPDATE produk SET 
                        kategori_id = $kategori_id,
                        nama = '" . mysqli_real_escape_string($conn, $nama) . "',
                        slug = '" . mysqli_real_escape_string($conn, $slug) . "',
                        deskripsi = '" . mysqli_real_escape_string($conn, $deskripsi) . "',
                        harga = $harga,
                        harga_coret = " . ($harga_coret > 0 ? $harga_coret : 'NULL') . ",
                        stok = $stok,
                        berat_gram = " . ($berat > 0 ? $berat : 'NULL') . ",
                        gambar_utama = " . ($gambar ? "'" . mysqli_real_escape_string($conn, $gambar) . "'" : 'NULL') . ",
                        badge = " . ($badge ? "'" . mysqli_real_escape_string($conn, $badge) . "'" : 'NULL') . ",
                        is_featured = $is_featured,
                        is_active = $is_active
                    WHERE id = $id";
            if (mysqli_query($conn, $sql)) {
                $flash = '✅ Produk berhasil diperbarui.';
            } else {
                $flash = '❌ Gagal memperbarui: ' . mysqli_error($conn);
            }
        } else {
            // INSERT
            $sql = "INSERT INTO produk (kategori_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, gambar_utama, badge, is_featured, is_active, created_at) 
                    VALUES ($kategori_id, 
                            '" . mysqli_real_escape_string($conn, $nama) . "',
                            '" . mysqli_real_escape_string($conn, $slug . '-' . time()) . "',
                            '" . mysqli_real_escape_string($conn, $deskripsi) . "',
                            $harga,
                            " . ($harga_coret > 0 ? $harga_coret : 'NULL') . ",
                            $stok,
                            " . ($berat > 0 ? $berat : 'NULL') . ",
                            " . ($gambar ? "'" . mysqli_real_escape_string($conn, $gambar) . "'" : 'NULL') . ",
                            " . ($badge ? "'" . mysqli_real_escape_string($conn, $badge) . "'" : 'NULL') . ",
                            $is_featured, $is_active, NOW())";
            if (mysqli_query($conn, $sql)) {
                $flash = '✅ Produk baru berhasil ditambahkan.';
            } else {
                $flash = '❌ Gagal menambah: ' . mysqli_error($conn);
            }
        }
    }
}

// ── AMBIL DATA EDIT ──────────────────────────────────────────
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
    if ($q) $edit = mysqli_fetch_assoc($q);
}

// ── AMBIL SEMUA PRODUK ───────────────────────────────────────
$produk = mysqli_query($conn, "SELECT p.*, k.nama AS kategori_nama 
                               FROM produk p 
                               LEFT JOIN kategori k ON p.kategori_id = k.id 
                               ORDER BY p.id DESC");

// ── AMBIL KATEGORI ───────────────────────────────────────────
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Kelola Produk | HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f2;color:#2c3e35;font-size:13.5px}
a{text-decoration:none;color:inherit}

.layout{display:grid;grid-template-columns:220px 1fr;min-height:100vh}
.sidebar{background:#1a4731;color:#c8e6d0;padding:24px 0}
.sidebar .brand{padding:0 20px 24px;font-size:17px;font-weight:800;color:white;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px}
.sidebar a{display:flex;align-items:center;gap:10px;padding:12px 20px;color:#c8e6d0;text-decoration:none;font-size:13.5px;font-weight:500;transition:background .15s}
.sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,.08);color:white}
.sidebar .sep{border-top:1px solid rgba(255,255,255,.1);margin:16px 0}

.main{padding:28px 32px}
.main h1{font-size:20px;color:#1a4731;margin-bottom:4px}
.main .sub{color:#5a7568;margin-bottom:20px}

.flash{background:#e8f5ee;color:#1e6b3c;padding:12px 18px;border-radius:8px;margin-bottom:16px;font-weight:600;border:1px solid #86efac}
.error-box{background:#fdecea;color:#c0392b;padding:12px 18px;border-radius:8px;margin-bottom:16px;font-weight:600;border:1px solid #f5b7b1}

.card{background:white;border-radius:12px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid #e0e8e4;margin-bottom:18px}

.grid-2{display:grid;grid-template-columns:380px 1fr;gap:18px;align-items:start}
@media(max-width:1000px){.grid-2{grid-template-columns:1fr}}

.form-group{margin-bottom:12px}
.form-group label{display:block;font-size:12px;font-weight:700;color:#2c3e35;margin-bottom:4px}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:9px 12px;border:1.5px solid #e0e8e4;border-radius:8px;font-size:13px;font-family:inherit;outline:none;transition:border-color .2s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:#27ae60}
.form-group textarea{resize:vertical;min-height:60px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.chk-row{display:flex;align-items:center;gap:8px;margin:8px 0}
.chk-row input[type=checkbox]{width:16px;height:16px;accent-color:#27ae60}

.btn{padding:9px 18px;border:none;border-radius:8px;font-weight:700;font-size:12.5px;cursor:pointer;display:inline-block;text-decoration:none}
.btn-primary{background:#1e6b3c;color:white}
.btn-primary:hover{background:#27ae60}
.btn-secondary{background:#e0e8e4;color:#2c3e35}
.btn-secondary:hover{background:#cdd9d2}
.btn-danger{background:#e74c3c;color:white}
.btn-danger:hover{background:#c0392b}
.btn-sm{padding:5px 10px;font-size:11px;border-radius:6px;font-weight:600}

table{width:100%;border-collapse:collapse;background:white;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);font-size:12.8px}
th,td{padding:10px 12px;text-align:left;border-bottom:1px solid #e0e8e4;vertical-align:middle}
th{background:#f0f4f2;font-weight:700;color:#5a7568;font-size:11px;text-transform:uppercase;letter-spacing:.4px}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f8fafb}

.badge-produk{display:inline-block;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:700;background:#e8f5ee;color:#1e6b3c}
.badge-nonaktif{background:#f0f4f2;color:#5a7568}
.badge-diskon{background:#fdecea;color:#e74c3c}
.badge-baru{background:#d6ecff;color:#0b5ed7}
.badge-terlaris{background:#fff3cd;color:#946200}

.act-edit{background:#d6ecff;color:#0b5ed7;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;display:inline-block}
.act-hapus{background:#fdecea;color:#e74c3c;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;border:none;cursor:pointer;display:inline-block}

.prod-thumb{width:40px;height:40px;border-radius:6px;object-fit:cover;background:#f0f4f2;vertical-align:middle;margin-right:8px}
</style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="brand">🏔️ HIKEGEAR ADMIN</div>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="admin_pesanan.php">📦 Kelola Pesanan</a>
    <a href="admin_produk.php" class="active">🛒 Kelola Produk</a>
    <a href="admin_kategori.php">📁 Kategori</a>
    <div class="sep"></div>
    <a href="index.php">🌐 Lihat Situs</a>
    <a href="LOGOUT.PHP">🚪 Keluar</a>
  </aside>
  <main class="main">
    <h1>🛒 Kelola Produk</h1>
    <div class="sub"><?= $edit ? 'Edit produk' : 'Tambah produk baru' ?></div>

    <?php if ($flash): ?><div class="flash"><?= $flash ?></div><?php endif; ?>
    <?php if ($errors): ?><div class="error-box"><?php foreach($errors as $e): ?><div>❌ <?= htmlspecialchars($e) ?></div><?php endforeach; ?></div><?php endif; ?>

    <div class="grid-2">
      <!-- FORM TAMBAH/EDIT PRODUK -->
      <div class="card">
        <h3 style="margin-bottom:14px;color:#1a4731;font-size:15px"><?= $edit ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' ?></h3>
        <form method="POST">
          <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>

          <div class="form-group">
            <label>Nama Produk *</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($edit['nama'] ?? '') ?>" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Kategori *</label>
              <select name="kategori_id" required>
                <option value="">Pilih Kategori</option>
                <?php 
                // Reset query pointer
                mysqli_data_seek($kategori, 0);
                while ($k = mysqli_fetch_assoc($kategori)): 
                ?>
                <option value="<?= $k['id'] ?>" <?= ($edit && $edit['kategori_id'] == $k['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($k['nama']) ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Stok *</label>
              <input type="number" name="stok" value="<?= $edit['stok'] ?? 0 ?>" min="0" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Harga * (Rp)</label>
              <input type="text" name="harga" value="<?= $edit ? number_format($edit['harga'],0,',','.') : '' ?>" placeholder="Contoh: 1250000" required>
            </div>
            <div class="form-group">
              <label>Harga Coret (Rp) — opsional</label>
              <input type="text" name="harga_coret" value="<?= $edit && $edit['harga_coret'] ? number_format($edit['harga_coret'],0,',','.') : '' ?>" placeholder="Harga sebelum diskon">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Berat (gram)</label>
              <input type="number" name="berat_gram" value="<?= $edit['berat_gram'] ?? 0 ?>" min="0" placeholder="Contoh: 500">
            </div>
            <div class="form-group">
              <label>Badge (opsional)</label>
              <input type="text" name="badge" value="<?= htmlspecialchars($edit['badge'] ?? '') ?>" placeholder="BARU, -10%, TERLARIS">
            </div>
          </div>

          <div class="form-group">
            <label>URL Gambar Utama (opsional)</label>
            <input type="text" name="gambar_utama" value="<?= htmlspecialchars($edit['gambar_utama'] ?? '') ?>" placeholder="https://...">
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= htmlspecialchars($edit['deskripsi'] ?? '') ?></textarea>
          </div>

          <div class="chk-row">
            <input type="checkbox" name="is_featured" id="is_featured" <?= ($edit && $edit['is_featured']) ? 'checked' : '' ?>>
            <label for="is_featured">Tampilkan di Featured / Unggulan</label>
          </div>
          <div class="chk-row">
            <input type="checkbox" name="is_active" id="is_active" <?= (!isset($edit) || $edit['is_active']) ? 'checked' : '' ?>>
            <label for="is_active">Aktif (tampilkan di toko)</label>
          </div>

          <div style="display:flex;gap:8px;margin-top:14px">
            <button type="submit" name="simpan" class="btn btn-primary"><?= $edit ? '💾 Simpan Perubahan' : '➕ Tambah Produk' ?></button>
            <?php if ($edit): ?><a href="admin_produk.php" class="btn btn-secondary">Batal</a><?php endif; ?>
          </div>
        </form>
      </div>

      <!-- TABEL DAFTAR PRODUK -->
      <div class="card" style="padding:0;overflow:hidden">
        <div style="padding:14px 18px;border-bottom:1px solid #e0e8e4;display:flex;align-items:center;justify-content:space-between">
          <strong>📋 Daftar Produk</strong>
          <span style="font-size:12px;color:#5a7568"><?= mysqli_num_rows($produk) ?> produk</span>
        </div>
        <table>
          <thead>
            <tr>
              <th>Produk</th>
              <th>Kategori</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($produk && mysqli_num_rows($produk) > 0): ?>
              <?php while ($p = mysqli_fetch_assoc($produk)): ?>
              <tr>
                <td style="display:flex;align-items:center;gap:8px">
                  <img class="prod-thumb" src="<?= htmlspecialchars($p['gambar_utama'] ?: 'https://via.placeholder.com/40') ?>">
                  <div>
                    <strong><?= htmlspecialchars($p['nama']) ?></strong>
                    <?php if ($p['badge']): ?>
                    <span class="badge-produk <?= strpos($p['badge'], '-') !== false ? 'badge-diskon' : (strpos($p['badge'], 'BARU') !== false ? 'badge-baru' : 'badge-terlaris') ?>"><?= htmlspecialchars($p['badge']) ?></span>
                    <?php endif; ?>
                  </div>
                </td>
                <td><?= htmlspecialchars($p['kategori_nama'] ?? '-') ?></td>
                <td><strong>Rp <?= number_format($p['harga'],0,',','.') ?></strong></td>
                <td><?= (int)$p['stok'] ?></td>
                <td>
                  <span class="badge-produk <?= $p['is_active'] ? '' : 'badge-nonaktif' ?>">
                    <?= $p['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                  </span>
                </td>
                <td>
                  <a href="?edit=<?= $p['id'] ?>" class="act-edit">✏️ Edit</a>
                  <a href="?hapus=<?= $p['id'] ?>" class="act-hapus" onclick="return confirm('Hapus produk "<?= htmlspecialchars($p['nama']) ?>"?')">🗑️ Hapus</a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" style="text-align:center;padding:24px;color:#5a7568">Belum ada produk. Tambah produk baru di form sebelah kiri.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>
</body>
</html>
