<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';
require_once __DIR__.'/model_produk.php';

$flash = '';
$errors = [];

// ── Ambil daftar kategori untuk dropdown ────────────────────────────────────
$kategori_list = $pdo->query("SELECT id, nama FROM kategori ORDER BY nama")->fetchAll();

// ── Handle Hapus ──────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_produk'])) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        delete_produk($pdo, $id);
        $flash = 'Produk berhasil dihapus.';
    }
}

// ── Handle Tambah / Update ────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_produk'])) {
    $id            = (int)($_POST['id'] ?? 0);
    $nama          = trim($_POST['nama'] ?? '');
    $kategori_id   = (int)($_POST['kategori_id'] ?? 0);
    $deskripsi     = trim($_POST['deskripsi'] ?? '');
    $harga         = (int)preg_replace('/[^0-9]/', '', $_POST['harga'] ?? '0');
    $harga_coret_r = trim($_POST['harga_coret'] ?? '');
    $harga_coret   = $harga_coret_r !== '' ? (int)preg_replace('/[^0-9]/', '', $harga_coret_r) : null;
    $stok          = (int)($_POST['stok'] ?? 0);
    $berat_gram    = (int)($_POST['berat_gram'] ?? 0);
    $badge         = trim($_POST['badge'] ?? '') ?: null;
    $gambar_utama  = trim($_POST['gambar_utama'] ?? '') ?: null;
    $is_active     = isset($_POST['is_active']) ? 1 : 0;

    if (!$nama) $errors[] = 'Nama produk wajib diisi.';
    if (!$kategori_id) $errors[] = 'Kategori wajib dipilih.';
    if ($harga <= 0) $errors[] = 'Harga harus lebih dari 0.';

    if (empty($errors)) {
        $data = [
            'kategori_id'  => $kategori_id,
            'nama'         => $nama,
            'deskripsi'    => $deskripsi,
            'harga'        => $harga,
            'harga_coret'  => $harga_coret,
            'stok'         => $stok,
            'berat_gram'   => $berat_gram,
            'badge'        => $badge,
            'gambar_utama' => $gambar_utama,
            'is_active'    => $is_active,
        ];
        if ($id) {
            update_produk($pdo, $id, $data);
            $flash = 'Produk berhasil diperbarui.';
        } else {
            insert_produk($pdo, $data);
            $flash = 'Produk baru berhasil ditambahkan.';
        }
    }
}

// ── Ambil produk yang sedang diedit (kalau ada) ─────────────────────────────
$edit_produk = null;
if (isset($_GET['edit'])) {
    $edit_produk = get_produk_by_id($pdo, (int)$_GET['edit']);
}

// ── Daftar semua produk (termasuk non-aktif, khusus admin) ──────────────────
$search = trim($_GET['q'] ?? '');
$where  = '1=1';
$params = [];
if ($search) { $where .= ' AND p.nama LIKE ?'; $params[] = '%'.$search.'%'; }
$stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama
                        FROM produk p JOIN kategori k ON p.kategori_id = k.id
                        WHERE $where ORDER BY p.id DESC");
$stmt->execute($params);
$daftar_produk = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Kelola Produk | HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--accent:#27ae60;
  --gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;--gray-600:#5a7568;--gray-800:#2c3e35;
  --red:#e74c3c;--radius:12px;
}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--gray-50);color:var(--gray-800);font-size:13.5px}
.layout{display:grid;grid-template-columns:220px 1fr;min-height:100vh}
.sidebar{background:var(--green-dark);color:#c8e6d0;padding:24px 0}
.sidebar .brand{padding:0 20px 24px;font-size:17px;font-weight:800;color:white;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:16px}
.sidebar a{display:flex;align-items:center;gap:10px;padding:12px 20px;color:#c8e6d0;text-decoration:none;font-size:13.5px;font-weight:500;transition:background .15s}
.sidebar a:hover, .sidebar a.active{background:rgba(255,255,255,.08);color:white}
.sidebar .sep{border-top:1px solid rgba(255,255,255,.1);margin:16px 0}
.main{padding:28px 32px}
.main h1{font-size:20px;color:var(--green-dark);margin-bottom:16px}

.flash{background:#e8f5ee;color:var(--green);padding:12px 18px;border-radius:8px;margin-bottom:16px;font-weight:600}
.error-box{background:#fdecea;color:var(--red);padding:12px 18px;border-radius:8px;margin-bottom:16px}
.error-box div{margin-bottom:3px}

.grid-2{display:grid;grid-template-columns:340px 1fr;gap:20px;align-items:start}
@media(max-width:900px){.grid-2{grid-template-columns:1fr}}

.card{background:white;border-radius:var(--radius);box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid var(--gray-200);padding:20px}
.card h3{font-size:14.5px;margin-bottom:14px;color:var(--green-dark)}

.form-group{margin-bottom:12px}
.form-group label{display:block;font-size:12.5px;font-weight:700;margin-bottom:5px;color:var(--gray-800)}
.form-group input, .form-group select, .form-group textarea{
  width:100%;padding:9px 11px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px;font-family:inherit;outline:none;
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus{border-color:var(--accent)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.chk-row{display:flex;align-items:center;gap:8px;margin-bottom:14px}
.chk-row input{width:16px;height:16px}

.btn{padding:10px 18px;border:none;border-radius:8px;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;display:inline-block}
.btn-primary{background:var(--green);color:white}
.btn-primary:hover{background:var(--accent)}
.btn-secondary{background:var(--gray-100);color:var(--gray-800)}
.btn-secondary:hover{background:var(--gray-200)}

.toolbar{display:flex;gap:10px;margin-bottom:14px}
.toolbar input{flex:1;padding:9px 12px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px}
.toolbar button{padding:9px 16px;background:var(--green);color:white;border:none;border-radius:8px;font-weight:700;cursor:pointer}

table{width:100%;border-collapse:collapse;background:white;border-radius:var(--radius);overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08)}
th,td{padding:10px 12px;text-align:left;border-bottom:1px solid var(--gray-100);font-size:12.5px;vertical-align:middle}
th{background:var(--gray-50);font-weight:700;color:var(--gray-600);text-transform:uppercase;font-size:10.5px}
tr:last-child td{border-bottom:none}
.p-thumb{width:44px;height:44px;border-radius:6px;object-fit:cover;background:var(--gray-100)}
.p-nama{font-weight:700}
.badge-inactive{display:inline-block;background:var(--gray-100);color:var(--gray-600);font-size:10px;padding:2px 8px;border-radius:10px;margin-left:6px}
.row-actions{display:flex;gap:6px}
.row-actions a, .row-actions button{font-size:11.5px;padding:5px 10px;border-radius:6px;border:none;cursor:pointer;text-decoration:none}
.act-edit{background:#d6ecff;color:#0b5ed7}
.act-hapus{background:#fdecea;color:var(--red)}
</style>
</head>
<body>

<div class="layout">
  <aside class="sidebar">
    <div class="brand">🏔️ HIKEGEAR ADMIN</div>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="admin_pesanan.php">📦 Kelola Pesanan</a>
    <a href="admin_produk.php" class="active">🛒 Kelola Produk</a>
    <div class="sep"></div>
    <a href="index.php">🌐 Lihat Situs</a>
    <a href="logout.php">🚪 Keluar</a>
  </aside>

  <main class="main">
    <h1>🛒 Kelola Produk</h1>

    <?php if ($flash): ?><div class="flash"><?= htmlspecialchars($flash) ?></div><?php endif; ?>
    <?php if ($errors): ?>
    <div class="error-box"><?php foreach ($errors as $e): ?><div>⚠️ <?= htmlspecialchars($e) ?></div><?php endforeach; ?></div>
    <?php endif; ?>

    <div class="grid-2">
      <!-- FORM Tambah/Edit -->
      <div class="card">
        <h3><?= $edit_produk ? '✏️ Edit Produk' : '➕ Tambah Produk Baru' ?></h3>
        <form method="POST">
          <?php if ($edit_produk): ?><input type="hidden" name="id" value="<?= $edit_produk['id'] ?>"><?php endif; ?>

          <div class="form-group">
            <label>Nama Produk *</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($edit_produk['nama'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label>Kategori *</label>
            <select name="kategori_id" required>
              <option value="">Pilih kategori</option>
              <?php foreach ($kategori_list as $k): ?>
              <option value="<?= $k['id'] ?>" <?= (isset($edit_produk['kategori_id']) && $edit_produk['kategori_id']==$k['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Harga (Rp) *</label>
              <input type="text" name="harga" value="<?= $edit_produk['harga'] ?? '' ?>" placeholder="cth: 250000" required>
            </div>
            <div class="form-group">
              <label>Harga Coret</label>
              <input type="text" name="harga_coret" value="<?= $edit_produk['harga_coret'] ?? '' ?>" placeholder="opsional">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Stok</label>
              <input type="number" name="stok" value="<?= $edit_produk['stok'] ?? 0 ?>">
            </div>
            <div class="form-group">
              <label>Berat (gram)</label>
              <input type="number" name="berat_gram" value="<?= $edit_produk['berat_gram'] ?? 0 ?>">
            </div>
          </div>

          <div class="form-group">
            <label>Badge (opsional)</label>
            <input type="text" name="badge" value="<?= htmlspecialchars($edit_produk['badge'] ?? '') ?>" placeholder="cth: BARU, -20%">
          </div>

          <div class="form-group">
            <label>URL Gambar</label>
            <input type="text" name="gambar_utama" value="<?= htmlspecialchars($edit_produk['gambar_utama'] ?? '') ?>" placeholder="https://...">
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="3"><?= htmlspecialchars($edit_produk['deskripsi'] ?? '') ?></textarea>
          </div>

          <div class="chk-row">
            <input type="checkbox" name="is_active" id="is_active" <?= (!isset($edit_produk) || $edit_produk['is_active']) ? 'checked' : '' ?>>
            <label for="is_active" style="margin:0">Aktif (tampil di situs)</label>
          </div>

          <button type="submit" name="simpan_produk" class="btn btn-primary">
            <?= $edit_produk ? 'Simpan Perubahan' : 'Tambah Produk' ?>
          </button>
          <?php if ($edit_produk): ?>
          <a href="admin_produk.php" class="btn btn-secondary">Batal</a>
          <?php endif; ?>
        </form>
      </div>

      <!-- Daftar Produk -->
      <div>
        <form class="toolbar" method="GET">
          <input type="text" name="q" placeholder="Cari nama produk..." value="<?= htmlspecialchars($search) ?>">
          <button type="submit">Cari</button>
        </form>

        <table>
          <thead>
            <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
          </thead>
          <tbody>
            <?php if (empty($daftar_produk)): ?>
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--gray-600)">Belum ada produk.</td></tr>
            <?php else: foreach ($daftar_produk as $p): ?>
            <tr>
              <td style="display:flex;align-items:center;gap:10px">
                <img class="p-thumb" src="<?= htmlspecialchars($p['gambar_utama'] ?: 'https://via.placeholder.com/60') ?>" alt="">
                <span class="p-nama"><?= htmlspecialchars($p['nama']) ?></span>
                <?php if (!$p['is_active']): ?><span class="badge-inactive">Nonaktif</span><?php endif; ?>
              </td>
              <td><?= htmlspecialchars($p['kategori_nama']) ?></td>
              <td><?= fmt($p['harga']) ?></td>
              <td><?= $p['stok'] ?></td>
              <td>
                <div class="row-actions">
                  <a href="?edit=<?= $p['id'] ?>" class="act-edit">Edit</a>
                  <form method="POST" onsubmit="return confirm('Yakin hapus produk ini?')" style="display:inline">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button type="submit" name="hapus_produk" class="act-hapus">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

</body>
</html>