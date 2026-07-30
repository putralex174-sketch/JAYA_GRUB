<?php
session_start();
require_once __DIR__.'/KONEKSI.PHP';
require_once __DIR__.'/auth_guard.php';
require_once __DIR__.'/admin_guard.php';

$flash = '';

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $pesanan_id = (int)$_POST['pesanan_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $valid = ['pending','konfirmasi','diproses','dikirim','selesai','dibatalkan'];
    if (in_array($status, $valid)) {
        mysqli_query($conn, "UPDATE pesanan SET status = '$status' WHERE id = $pesanan_id");
        $flash = 'Status berhasil diperbarui.';
    }
}

// Filter
$filter_status = $_GET['status'] ?? '';
$search = trim($_GET['q'] ?? '');
$where = '1=1';
if ($filter_status) $where .= " AND p.status = '$filter_status'";
if ($search) $where .= " AND (p.kode_pesanan LIKE '%$search%' OR u.nama_lengkap LIKE '%$search%')";

$result = mysqli_query($conn, "SELECT p.*, u.nama_lengkap AS nama_user, u.email 
                                FROM pesanan p JOIN users u ON p.user_id = u.id 
                                WHERE $where ORDER BY p.id DESC");

$label_status = [
    'pending'    => 'Pesanan Dibuat', 'konfirmasi' => 'Dikonfirmasi',
    'diproses'   => 'Sedang Dikemas', 'dikirim'    => 'Dalam Pengiriman',
    'selesai'    => 'Selesai',        'dibatalkan' => 'Dibatalkan',
];
?>
<!DOCTYPE html>
<html>
<head><title>Admin – Pesanan | HikeGear</title>
<style>
body{font-family:sans-serif;background:#f8fafb;margin:0;padding:20px}
h2{color:#1a4731}
.flash{background:#e8f5ee;color:#1e6b3c;padding:12px;border-radius:8px;margin-bottom:16px}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08)}
th,td{padding:10px 12px;text-align:left;border-bottom:1px solid #e0e8e4;font-size:13px}
th{background:#f0f4f2;color:#5a7568;font-size:11px;text-transform:uppercase}
.pill{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.pill-pending{background:#fff3cd;color:#946200}
.pill-konfirmasi{background:#d6ecff;color:#0b5ed7}
.pill-diproses{background:#fde7c8;color:#b5590a}
.pill-dikirim{background:#d3e9ff;color:#1157b0}
.pill-selesai{background:#e8f5ee;color:#1e6b3c}
.pill-dibatalkan{background:#fdecea;color:#e74c3c}
.toolbar{display:flex;gap:10px;margin-bottom:16px}
.toolbar select,.toolbar input{padding:8px 12px;border:1.5px solid #e0e8e4;border-radius:8px}
.toolbar button{padding:8px 16px;background:#1e6b3c;color:#fff;border:none;border-radius:8px;cursor:pointer}
.status-form select{padding:5px 8px;border:1px solid #e0e8e4;border-radius:5px;font-size:12px}
.status-form button{padding:5px 10px;background:#27ae60;color:#fff;border:none;border-radius:5px;cursor:pointer}
</style>
</head>
<body>
<h2>📦 Kelola Pesanan</h2>
<?php if ($flash): ?><div class="flash"><?= $flash ?></div><?php endif; ?>

<form class="toolbar" method="GET">
    <select name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <?php foreach ($label_status as $val => $lbl): ?>
        <option value="<?= $val ?>" <?= $filter_status===$val?'selected':'' ?>><?= $lbl ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="q" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Cari</button>
</form>

<table>
    <thead>
        <tr><th>Kode</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Ubah Status</th></tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) == 0): ?>
        <tr><td colspan="6" style="text-align:center;padding:30px;color:#5a7568">Tidak ada pesanan.</td></tr>
        <?php else: while ($p = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><b><?= htmlspecialchars($p['kode_pesanan']) ?></b></td>
            <td><?= htmlspecialchars($p['nama_user']) ?></td>
            <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
            <td>Rp<?= number_format($p['total'],0,',','.') ?></td>
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
        <?php endwhile; endif; ?>
    </tbody>
</table>
</body>
</html>
