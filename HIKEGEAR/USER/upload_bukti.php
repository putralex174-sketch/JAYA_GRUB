<?php
session_start();
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/auth_guard.php';

$user_id = (int)$_SESSION['user_id'];
$flash = '';
$upload_sukses = false;
$error = '';

$kode = $_GET['kode'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM pesanan WHERE kode_pesanan = ? AND user_id = ?");
$stmt->execute([$kode, $user_id]);
$pesanan = $stmt->fetch();

if (!$pesanan) {
    die('<p style="font-family:sans-serif;padding:40px;text-align:center">Pesanan tidak ditemukan. <a href="lacak_pesanan.php">Kembali ke Pesanan Saya</a></p>');
}

// Kalau metode bayar COD, tidak perlu upload bukti
if ($pesanan['metode_bayar'] === 'cod' || $pesanan['metode_bayar'] === 'Bayar di Tempat') {
    header('Location: lacak_pesanan.php?kode=' . urlencode($kode));
    exit;
}

// ── Handle upload ─────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
    $file = $_FILES['bukti'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Gagal mengunggah file. Coba lagi.';
    } else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($ext, $allowed)) {
            $error = 'Format file harus JPG, PNG, atau PDF.';
        } elseif ($file['size'] > 5 * 1024 * 1024) {
            $error = 'Ukuran file maksimal 5MB.';
        } else {
            $upload_dir = __DIR__ . '/uploads/bukti_bayar/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $filename = 'bukti_' . $pesanan['kode_pesanan'] . '_' . time() . '.' . $ext;
            $dest = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $pdo->prepare("UPDATE pesanan SET bukti_pembayaran = ? WHERE id = ?")
                    ->execute(['uploads/bukti_bayar/' . $filename, $pesanan['id']]);

                // Refresh data pesanan
                $stmt = $pdo->prepare("SELECT * FROM pesanan WHERE id = ?");
                $stmt->execute([$pesanan['id']]);
                $pesanan = $stmt->fetch();

                $flash = 'Bukti pembayaran berhasil diunggah! Admin akan segera memverifikasi.';
                $upload_sukses = true;
            } else {
                $error = 'Gagal menyimpan file di server.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Bukti Pembayaran – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Segoe UI',system-ui,sans-serif;background:#f8fafb;color:#1a2e25;font-size:14px}
header{background:#1a4731;color:white;padding:16px 32px;display:flex;justify-content:space-between;align-items:center}
header a{color:white;text-decoration:none}
.wrap{max-width:560px;margin:40px auto;padding:0 20px}
.card{background:white;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid #e0e8e4;padding:28px}
.card h2{color:#1a4731;margin-bottom:6px}
.card .sub{color:#5a7568;font-size:13px;margin-bottom:20px}

.info-box{background:#e8f5ee;border-radius:10px;padding:16px 18px;margin-bottom:20px;font-size:13px}
.info-box .row{display:flex;justify-content:space-between;margin-bottom:6px}
.info-box .row:last-child{margin-bottom:0}
.info-box .lbl{color:#5a7568}
.info-box .val{font-weight:700;color:#1a4731}

.flash{background:#e8f5ee;color:#1e6b3c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-weight:600;font-size:13px}

/* Success Modal */
.success-overlay{position:fixed;inset:0;background:rgba(10,25,15,.55);display:none;align-items:center;justify-content:center;z-index:999;padding:20px}
.success-overlay.show{display:flex}
.success-modal{background:white;border-radius:16px;padding:40px 32px;max-width:380px;width:100%;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.25);animation:popIn .3s ease}
@keyframes popIn{from{transform:scale(.9);opacity:0}to{transform:scale(1);opacity:1}}
.success-icon{width:72px;height:72px;border-radius:50%;background:#e8f5ee;display:flex;align-items:center;justify-content:center;margin:0 auto 18px}
.success-icon svg{width:36px;height:36px;stroke:#1e6b3c;fill:none;stroke-width:2.5}
.success-modal h3{font-size:20px;color:#1a4731;margin-bottom:8px}
.success-modal p{font-size:13.5px;color:#5a7568;line-height:1.6;margin-bottom:22px}
.success-modal button{width:100%;padding:12px;background:#1e6b3c;color:white;border:none;border-radius:9px;font-weight:700;font-size:14px;cursor:pointer}
.success-modal button:hover{background:#27ae60}
.error{background:#fdecea;color:#e74c3c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:13px}

.upload-area{border:2px dashed #cdd9d2;border-radius:10px;padding:28px;text-align:center;margin-bottom:16px;cursor:pointer;transition:border-color .2s}
.upload-area:hover{border-color:#27ae60}
.upload-area input{display:none}
.upload-area .icon{font-size:32px;margin-bottom:8px}
.upload-area .txt{color:#5a7568;font-size:13px}
.file-name{font-size:12.5px;color:#1e6b3c;font-weight:600;margin-top:8px;display:none}

.existing-proof{margin-bottom:16px;padding:14px;background:#f8fafb;border-radius:8px;border:1px solid #e0e8e4}
.existing-proof img{max-width:100%;border-radius:8px;margin-top:8px}

.btn{width:100%;padding:13px;background:#1e6b3c;color:white;border:none;border-radius:9px;font-weight:700;font-size:14.5px;cursor:pointer}
.btn:hover{background:#27ae60}
.btn-back{display:block;text-align:center;margin-top:14px;color:#1e6b3c;font-weight:600;text-decoration:none;font-size:13px}
</style>
</head>
<body>

<header>
  <strong>HIKEGEAR</strong>
  <a href="lacak_pesanan.php">← Pesanan Saya</a>
</header>

<div class="wrap">
  <div class="card">
    <h2>Upload Bukti Pembayaran</h2>
    <div class="sub">Pesanan <b><?= htmlspecialchars($pesanan['kode_pesanan']) ?></b></div>

    <?php if ($flash): ?><div class="flash">✓ <?= htmlspecialchars($flash) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="info-box">
      <div class="row"><span class="lbl">Metode Pembayaran</span><span class="val"><?= htmlspecialchars($pesanan['metode_bayar']) ?></span></div>
      <div class="row"><span class="lbl">Total yang Ditransfer</span><span class="val"><?= fmt($pesanan['total']) ?></span></div>
      <div class="row"><span class="lbl">Status Pembayaran</span><span class="val"><?= ucfirst($pesanan['status_bayar']) ?></span></div>
    </div>

    <?php if ($pesanan['bukti_pembayaran']): ?>
    <div class="existing-proof">
      <b style="font-size:13px">Bukti yang sudah diunggah:</b>
      <?php if (str_ends_with($pesanan['bukti_pembayaran'], '.pdf')): ?>
        <p style="margin-top:8px"><a href="<?= htmlspecialchars($pesanan['bukti_pembayaran']) ?>" target="_blank">📄 Lihat file PDF</a></p>
      <?php else: ?>
        <img src="<?= htmlspecialchars($pesanan['bukti_pembayaran']) ?>" alt="Bukti pembayaran">
      <?php endif; ?>
    </div>
    <p style="font-size:12px;color:#5a7568;margin-bottom:14px">Upload ulang di bawah kalau ingin mengganti bukti pembayaran.</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="uploadForm">
      <label class="upload-area" id="uploadArea">
        <div class="icon">📤</div>
        <div class="txt">Klik untuk pilih file (JPG, PNG, atau PDF, maks. 5MB)</div>
        <input type="file" name="bukti" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" required>
        <div class="file-name" id="fileName"></div>
      </label>
      <button type="submit" class="btn">Kirim Bukti Pembayaran</button>
    </form>

    <a href="lacak_pesanan.php?kode=<?= urlencode($pesanan['kode_pesanan']) ?>" class="btn-back">Kembali ke Detail Pesanan</a>
  </div>
</div>

<!-- Modal Sukses -->
<div class="success-overlay<?= $upload_sukses ? ' show' : '' ?>" id="successOverlay">
  <div class="success-modal">
    <div class="success-icon">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h3>Berhasil Dikirim! 🎉</h3>
    <p>Bukti pembayaran kamu sudah kami terima. Admin akan segera memverifikasi dan memproses pesananmu.</p>
    <button onclick="document.getElementById('successOverlay').classList.remove('show')">Oke, Mengerti</button>
  </div>
</div>

<script>
document.getElementById('fileInput').addEventListener('change', function() {
  const nameEl = document.getElementById('fileName');
  if (this.files.length > 0) {
    nameEl.textContent = '📎 ' + this.files[0].name;
    nameEl.style.display = 'block';
  }
});
</script>
</body>
</html>
