<?php
// ============================================================
//  pembayaran.php — HikeGear | Halaman Pembayaran
//  Fitur:
//  - Instruksi bayar per metode (transfer, e-wallet, COD, QRIS)
//  - Countdown timer batas bayar
//  - Upload bukti transfer (AJAX + drag & drop)
//  - Copy nomor rekening 1 klik
//  - Polling status pembayaran otomatis
//  - Responsive Bootstrap 5
// ============================================================
session_start();
require_once __DIR__ . '/KONEKSI.PHP';
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/helpers.php';

$user_id = (int)$_SESSION['user_id'];

// ── Ambil kode pesanan dari URL ──────────────────────────────
$kode_pesanan = trim($_GET['kode'] ?? '');
if (!$kode_pesanan) {
    $_SESSION['flash'] = ['msg' => 'Kode pesanan tidak ditemukan.', 'type' => 'error'];
    header('Location: pesanan.php');
    exit;
}

// ── Ambil data pesanan dari database ─────────────────────────
$kode_esc = mysqli_real_escape_string($conn, $kode_pesanan);
$pq = mysqli_query($conn, "SELECT * FROM pesanan WHERE kode_pesanan = '$kode_esc' AND user_id = $user_id");
$pesanan = mysqli_fetch_assoc($pq);

if (!$pesanan) {
    $_SESSION['flash'] = ['msg' => 'Pesanan tidak ditemukan.', 'type' => 'error'];
    header('Location: pesanan.php');
    exit;
}

// ── Ambil detail produk dari pesanan ─────────────────────────
$items = [];
$diq = mysqli_query($conn, "SELECT pd.*, p.gambar_utama, p.slug as produk_slug 
                             FROM pesanan_detail pd 
                             LEFT JOIN produk p ON pd.produk_id = p.id 
                             WHERE pd.pesanan_id = {$pesanan['id']}");
while ($d = mysqli_fetch_assoc($diq)) {
    $items[] = $d;
}

$csrf = bin2hex(random_bytes(16));
$_SESSION['csrf_token'] = $csrf;

$metode = [
    'label'     => 'Pembayaran',
    'group'     => 'bank',
    'no_rek'    => '1234567890',
    'atas_nama' => 'PT HikeGear Indonesia',
];

function rp(int $n): string { return 'Rp '.number_format($n,0,',','.'); }
function eg(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function prodImg(?string $f): string {
    return ($f && file_exists("uploads/products/$f"))
        ? "uploads/products/$f"
        : "assets/img/no-image.png";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Pembayaran <?=eg($pesanan['kode_pesanan'])?> – HikeGear</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

<style>
/* ── Tokens ──────────────────────────────────────────────── */
:root {
  --green:      #1e7e3e;
  --green-dark: #155d2c;
  --green-light:#e8f5ee;
  --green-bg:   #f0faf3;
  --gold:       #f59e0b;
  --red:        #dc3545;
  --gray-50:    #f9fafb;
  --gray-100:   #f3f4f6;
  --gray-200:   #e5e7eb;
  --gray-600:   #4b5563;
  --gray-800:   #1f2937;
  --shadow-sm:  0 1px 3px rgba(0,0,0,.08);
  --shadow-md:  0 4px 16px rgba(0,0,0,.10);
  --shadow-lg:  0 10px 32px rgba(0,0,0,.13);
  --radius:     12px;
  --radius-sm:  8px;
}
* { box-sizing: border-box; }
body {
  font-family: 'Inter', sans-serif;
  background: var(--gray-50);
  color: var(--gray-800);
  font-size: 14px;
}

/* ── Navbar ──────────────────────────────────────────────── */
.navbar-brand strong { font-size: 1.15rem; font-weight: 900; letter-spacing: -.3px; }
.navbar-brand strong span { color: #4caf63; }
.navbar-brand small { font-size: .58rem; letter-spacing: 1.8px; text-transform: uppercase; color: rgba(255,255,255,.6); display: block; }
.secure-badge { display: flex; align-items: center; gap: 6px; font-size: .75rem; color: rgba(255,255,255,.8); }

/* ── Step Progress ───────────────────────────────────────── */
.steps-bar { background: #fff; border-bottom: 1px solid var(--gray-200); padding: 14px 0; }
.step-list { display: flex; align-items: center; justify-content: center; gap: 0; }
.step-item { display: flex; align-items: center; gap: 8px; }
.step-circle {
  width: 30px; height: 30px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .75rem; font-weight: 700; flex-shrink: 0;
}
.step-circle.done  { background: var(--green); color: #fff; }
.step-circle.active{ background: #fff; border: 2px solid var(--green); color: var(--green); }
.step-circle.idle  { background: var(--gray-100); border: 2px solid var(--gray-200); color: var(--gray-600); }
.step-label { font-size: .78rem; font-weight: 600; }
.step-label.done   { color: var(--green); }
.step-label.active { color: var(--green); }
.step-label.idle   { color: var(--gray-600); }
.step-line { width: 56px; height: 2px; background: var(--gray-200); margin: 0 8px; }
.step-line.done { background: var(--green); }

/* ── Countdown ───────────────────────────────────────────── */
.countdown-wrap {
  background: linear-gradient(135deg, #fff3cd, #fff8e8);
  border: 1.5px solid #ffc107;
  border-radius: var(--radius);
  padding: 14px 20px;
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 10px;
}
.countdown-wrap.danger { background: linear-gradient(135deg,#fdecea,#fff0ef); border-color: var(--red); }
.timer-boxes { display: flex; align-items: center; gap: 6px; }
.timer-box {
  background: #fff;
  border: 1.5px solid #ffc107;
  border-radius: 7px;
  padding: 6px 12px;
  text-align: center;
  min-width: 52px;
}
.timer-box .num { font-size: 1.3rem; font-weight: 800; color: var(--gray-800); line-height: 1; font-variant-numeric: tabular-nums; }
.timer-box .lbl { font-size: .6rem; color: var(--gray-600); text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }
.timer-sep { font-size: 1.2rem; font-weight: 700; color: #ffc107; }

/* ── Card ────────────────────────────────────────────────── */
.pay-card {
  background: #fff;
  border: 1.5px solid var(--gray-200);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
}
.pay-card-head {
  padding: 14px 20px;
  border-bottom: 1px solid var(--gray-100);
  display: flex; align-items: center; gap: 10px;
  background: var(--gray-50);
}
.pay-card-head .num {
  width: 26px; height: 26px; border-radius: 50%;
  background: var(--green); color: #fff;
  font-size: .72rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.pay-card-head h6 { margin: 0; font-size: .9rem; font-weight: 700; }
.pay-card-body { padding: 20px; }

/* ── Metode Tabs ─────────────────────────────────────────── */
.metode-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; }
.metode-tab {
  display: flex; align-items: center; gap: 7px;
  padding: 8px 14px; border-radius: 8px;
  border: 1.5px solid var(--gray-200);
  background: #fff; cursor: pointer; font-size: .8rem; font-weight: 600;
  color: var(--gray-600); transition: all .18s;
}
.metode-tab:hover { border-color: var(--green); color: var(--green); }
.metode-tab.active { border-color: var(--green); background: var(--green-light); color: var(--green); }
.metode-tab i { font-size: .9rem; }

/* ── Bank Transfer Card ──────────────────────────────────── */
.bank-card {
  border: 1.5px solid var(--gray-200);
  border-radius: var(--radius-sm);
  padding: 16px;
  transition: border-color .18s, box-shadow .18s;
  cursor: pointer;
}
.bank-card:hover, .bank-card.selected {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(30,126,62,.1);
}
.bank-card.selected { background: var(--green-bg); }
.bank-logo {
  width: 64px; height: 30px; object-fit: contain;
  border-radius: 4px; border: 1px solid var(--gray-200);
  padding: 3px; background: #fff;
}
.bank-logo-text {
  width: 64px; height: 30px; border-radius: 4px;
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem; font-weight: 800; color: #fff; letter-spacing: .5px;
}
.no-rek-box {
  background: var(--gray-100);
  border: 1px dashed var(--gray-200);
  border-radius: 8px;
  padding: 10px 14px;
  display: flex; align-items: center; justify-content: space-between;
  margin-top: 10px;
}
.no-rek-box span { font-size: 1.1rem; font-weight: 800; letter-spacing: 1px; color: var(--gray-800); }
.btn-copy {
  padding: 5px 12px; border-radius: 6px;
  background: var(--green); color: #fff; border: none;
  font-size: .72rem; font-weight: 700; cursor: pointer;
  transition: background .15s;
  display: flex; align-items: center; gap: 5px;
}
.btn-copy:hover { background: var(--green-dark); }
.btn-copy.copied { background: #28a745; }

/* ── Instruksi ───────────────────────────────────────────── */
.instruksi-list { padding: 0; margin: 0; list-style: none; }
.instruksi-list li {
  display: flex; gap: 12px; padding: 10px 0;
  border-bottom: 1px solid var(--gray-100); font-size: .82rem;
}
.instruksi-list li:last-child { border-bottom: none; }
.instr-num {
  width: 22px; height: 22px; border-radius: 50%;
  background: var(--green-light); color: var(--green);
  font-size: .7rem; font-weight: 800;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

/* ── Upload Bukti ────────────────────────────────────────── */
.upload-area {
  border: 2px dashed var(--gray-200);
  border-radius: var(--radius);
  padding: 32px 20px;
  text-align: center;
  transition: border-color .2s, background .2s;
  cursor: pointer;
  position: relative;
}
.upload-area.drag-over { border-color: var(--green); background: var(--green-bg); }
.upload-area.has-file  { border-color: var(--green); border-style: solid; background: var(--green-bg); }
.upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; }
.upload-icon { width: 52px; height: 52px; border-radius: 50%; background: var(--green-light); color: var(--green); display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; font-size: 1.3rem; }
.upload-preview { display: none; position: relative; }
.upload-preview img { max-height: 180px; border-radius: 8px; border: 2px solid var(--green); margin: 0 auto; display: block; }
.btn-remove-file { position: absolute; top: -8px; right: -8px; width: 24px; height: 24px; border-radius: 50%; background: var(--red); color: #fff; border: none; font-size: .7rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }

/* ── Progress Upload ─────────────────────────────────────── */
.upload-progress { display: none; }
.progress-bar-custom { height: 6px; border-radius: 3px; background: var(--green); transition: width .3s; }

/* ── Ringkasan ───────────────────────────────────────────── */
.summary-card { position: sticky; top: 80px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: .83rem; color: var(--gray-600); border-bottom: 1px solid var(--gray-100); }
.summary-row:last-child { border-bottom: none; }
.summary-row.total { font-size: 1rem; font-weight: 800; color: var(--gray-800); padding-top: 12px; border-top: 2px solid var(--gray-200); }
.prod-item { display: flex; gap: 10px; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--gray-100); }
.prod-item:last-child { border-bottom: none; }
.prod-thumb { width: 50px; height: 50px; border-radius: 7px; object-fit: cover; background: var(--gray-100); flex-shrink: 0; }
.prod-name { font-size: .78rem; font-weight: 600; color: var(--gray-800); }
.prod-var  { font-size: .7rem; color: var(--gray-600); }
.prod-price{ font-size: .8rem; font-weight: 700; color: var(--gray-800); white-space: nowrap; }

/* ── Tombol Submit ───────────────────────────────────────── */
.btn-bayar {
  width: 100%; padding: 14px;
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  color: #fff; border: none; border-radius: var(--radius);
  font-size: .95rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: opacity .18s, transform .12s;
  box-shadow: 0 4px 14px rgba(30,126,62,.35);
  cursor: pointer;
}
.btn-bayar:hover { opacity: .92; transform: translateY(-1px); }
.btn-bayar:active { transform: translateY(0); }
.btn-bayar:disabled { background: #9ca3af; box-shadow: none; cursor: not-allowed; }

/* ── COD Box ─────────────────────────────────────────────── */
.cod-box {
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border: 2px solid #86efac;
  border-radius: var(--radius);
  padding: 24px; text-align: center;
}

/* ── QRIS Box ────────────────────────────────────────────── */
.qris-box { text-align: center; }
.qris-img-wrap {
  width: 200px; height: 200px;
  background: var(--gray-100);
  border: 2px solid var(--gray-200);
  border-radius: var(--radius);
  margin: 0 auto 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 3rem; color: var(--gray-600);
}

/* ── Toast ───────────────────────────────────────────────── */
.toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 9999; }
.toast-custom {
  min-width: 260px; padding: 13px 18px;
  border-radius: 10px; font-size: .82rem; font-weight: 600;
  display: flex; align-items: center; gap: 9px;
  box-shadow: var(--shadow-lg);
  transform: translateY(80px); opacity: 0;
  transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s;
}
.toast-custom.show { transform: translateY(0); opacity: 1; }
.toast-success { background: var(--green); color: #fff; }
.toast-error   { background: var(--red);   color: #fff; }
.toast-info    { background: #3b82f6;      color: #fff; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
  .step-label { display: none; }
  .step-line  { width: 28px; }
  .timer-box  { min-width: 42px; padding: 5px 8px; }
  .timer-box .num { font-size: 1.1rem; }
  .summary-card { position: static; }
}
</style>
</head>
<body>

<!-- ── TOAST ────────────────────────────────────────────── -->
<div class="toast-container">
  <div class="toast-custom" id="toast">
    <i class="fas fa-check-circle" id="toastIcon"></i>
    <span id="toastMsg"></span>
  </div>
</div>

<!-- ── NAVBAR ────────────────────────────────────────────── -->
<nav class="navbar navbar-dark sticky-top" style="background:linear-gradient(135deg,#1a5c2a,#2d7a3a);border-bottom:1px solid rgba(255,255,255,.1)">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <div style="width:38px;height:38px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center">
        <i class="fas fa-mountain" style="color:#4caf63;font-size:1.1rem"></i>
      </div>
      <div>
        <strong>HIKE<span>GEAR</span></strong>
        <small>Adventure Starts Here</small>
      </div>
    </a>
    <div class="secure-badge">
      <i class="fas fa-lock" style="color:#4caf63"></i>
      Transaksi Aman & Terenkripsi
    </div>
  </div>
</nav>

<!-- ── PROGRESS STEPS ────────────────────────────────────── -->
<div class="steps-bar">
  <div class="container">
    <div class="step-list">
      <div class="step-item">
        <div class="step-circle done"><i class="fas fa-check" style="font-size:.65rem"></i></div>
        <span class="step-label done">Keranjang</span>
      </div>
      <div class="step-line done"></div>
      <div class="step-item">
        <div class="step-circle done"><i class="fas fa-check" style="font-size:.65rem"></i></div>
        <span class="step-label done">Pesanan</span>
      </div>
      <div class="step-line done"></div>
      <div class="step-item">
        <div class="step-circle active">3</div>
        <span class="step-label active">Pembayaran</span>
      </div>
      <div class="step-line"></div>
      <div class="step-item">
        <div class="step-circle idle">4</div>
        <span class="step-label idle">Selesai</span>
      </div>
    </div>
  </div>
</div>

<!-- ── BREADCRUMB ────────────────────────────────────────── -->
<div class="container mt-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb" style="font-size:.78rem">
      <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none" style="color:var(--green)">Beranda</a></li>
      <li class="breadcrumb-item"><a href="pesanan.php" class="text-decoration-none" style="color:var(--green)">Pesanan</a></li>
      <li class="breadcrumb-item active">Pembayaran</li>
    </ol>
  </nav>
</div>

<!-- ── MAIN ──────────────────────────────────────────────── -->
<div class="container pb-5">
  <div class="row g-4">

    <!-- ══════════════ LEFT COLUMN ══════════════ -->
    <div class="col-lg-8">

      <!-- COUNTDOWN TIMER -->
      <div class="countdown-wrap mb-3" id="countdownWrap">
        <div class="d-flex align-items-center gap-2">
          <i class="fas fa-clock text-warning"></i>
          <div>
            <div style="font-size:.78rem;font-weight:700;color:var(--gray-800)">Selesaikan pembayaran sebelum:</div>
            <div style="font-size:.72rem;color:var(--gray-600)"><?=date('d M Y, H:i', strtotime($pesanan['bayar_sebelum']))?> WIB</div>
          </div>
        </div>
        <div class="timer-boxes">
          <div class="timer-box"><div class="num" id="th">00</div><div class="lbl">Jam</div></div>
          <span class="timer-sep">:</span>
          <div class="timer-box"><div class="num" id="tm">00</div><div class="lbl">Mnt</div></div>
          <span class="timer-sep">:</span>
          <div class="timer-box"><div class="num" id="ts">00</div><div class="lbl">Dtk</div></div>
        </div>
      </div>

      <!-- KODE PESANAN BANNER -->
      <div class="pay-card mb-3">
        <div class="pay-card-body d-flex align-items-center justify-content-between flex-wrap gap-2" style="padding:14px 20px">
          <div>
            <div style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.8px;color:var(--gray-600)">Kode Pesanan</div>
            <div style="font-size:1.2rem;font-weight:900;color:var(--gray-800);letter-spacing:.5px"><?=eg($pesanan['kode_pesanan'])?></div>
          </div>
          <button class="btn-copy" onclick="copyText('<?=eg($pesanan['kode_pesanan'])?>', this)">
            <i class="fas fa-copy"></i> Salin Kode
          </button>
        </div>
      </div>

      <!-- FORM PEMBAYARAN -->
      <form id="payForm" method="POST" action="pembayaran-proses.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token"    value="<?=eg($csrf)?>">
        <input type="hidden" name="kode_pesanan"  value="<?=eg($pesanan['kode_pesanan'])?>">
        <input type="hidden" name="metode_bayar"  id="metodeTerpilih" value="<?=eg($pesanan['metode_bayar'])?>">

        <?php
$grup = match($pesanan['metode_bayar']) {
    'transfer' => 'bank',
    'ewallet'  => 'ewallet',
    'qris'     => 'qris',
    'cod'      => 'cod',
    default    => 'bank',
};

        // ══════════════════════════════════════
        // INSTRUKSI TRANSFER BANK
        // ══════════════════════════════════════
        if ($grup === 'bank'):

        $banks = [
            'bca_transfer'     => ['label'=>'BCA',     'color'=>'#005BAC','no_rek'=>'1234567890'],
            'bni_transfer'     => ['label'=>'BNI',     'color'=>'#FF6600','no_rek'=>'0987654321'],
            'bri_transfer'     => ['label'=>'BRI',     'color'=>'#00529B','no_rek'=>'1122334455'],
            'mandiri_transfer' => ['label'=>'MANDIRI', 'color'=>'#003A70','no_rek'=>'9988776655'],
        ];
        $bankAktif = $banks[$pesanan['metode_bayar']] ?? $banks['bca_transfer'];
        ?>

        <!-- Pilih Bank -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">1</div>
            <h6>Pilih Bank Tujuan Transfer</h6>
          </div>
          <div class="pay-card-body">
            <div class="row g-2">
              <?php foreach($banks as $key => $b): ?>
              <div class="col-6 col-md-3">
                <div class="bank-card <?=$key===$pesanan['metode_bayar']?'selected':''?>"
                     onclick="pilihBank('<?=$key?>',this)"
                     id="bank-<?=$key?>">
                  <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bank-logo-text" style="background:<?=$b['color']?>;font-size:.65rem">
                      <?=$b['label']?>
                    </div>
                    <span style="font-size:.78rem;font-weight:700"><?=$b['label']?></span>
                  </div>
                  <div class="no-rek-box">
                    <span id="norek-<?=$key?>"><?=$b['no_rek']?></span>
                    <button type="button" class="btn-copy" onclick="copyText('<?=$b['no_rek']?>',this);event.stopPropagation()">
                      <i class="fas fa-copy"></i>
                    </button>
                  </div>
                  <div style="font-size:.7rem;color:var(--gray-600);margin-top:6px">a.n. PT HikeGear Indonesia</div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Total yang harus ditransfer -->
            <div class="mt-3 p-3 rounded-3" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1.5px solid #86efac">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <div style="font-size:.72rem;color:var(--gray-600);font-weight:600">Transfer tepat senilai:</div>
                  <div style="font-size:1.5rem;font-weight:900;color:var(--green)" id="totalDisplay">
                    <?=rp((int)$pesanan['total'])?>
                  </div>
                  <div style="font-size:.7rem;color:#e53e3e;font-weight:600">
                    <i class="fas fa-exclamation-circle"></i>
                    Transfer sesuai nominal di atas agar mudah diverifikasi
                  </div>
                </div>
                <button type="button" class="btn-copy" onclick="copyText('<?=(int)$pesanan['total']?>',this)">
                  <i class="fas fa-copy"></i> Salin
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Cara Transfer -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">2</div>
            <h6>Cara Transfer</h6>
          </div>
          <div class="pay-card-body">
            <div class="d-flex gap-2 mb-3">
              <button type="button" class="metode-tab active" onclick="switchInstruksi('atm',this)">
                <i class="fas fa-university"></i> ATM
              </button>
              <button type="button" class="metode-tab" onclick="switchInstruksi('mobile',this)">
                <i class="fas fa-mobile-alt"></i> Mobile Banking
              </button>
              <button type="button" class="metode-tab" onclick="switchInstruksi('internet',this)">
                <i class="fas fa-globe"></i> Internet Banking
              </button>
            </div>

            <!-- ATM -->
            <div id="instr-atm">
              <ul class="instruksi-list">
                <li><span class="instr-num">1</span><span>Masukkan kartu ATM dan PIN kamu</span></li>
                <li><span class="instr-num">2</span><span>Pilih menu <strong>Transfer</strong> → <strong>Transfer ke Bank Lain</strong> (jika beda bank) atau <strong>Antar Rekening</strong></span></li>
                <li><span class="instr-num">3</span><span>Masukkan nomor rekening tujuan: <strong id="norekAtm"><?=$bankAktif['no_rek']?></strong></span></li>
                <li><span class="instr-num">4</span><span>Masukkan nominal tepat: <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
                <li><span class="instr-num">5</span><span>Periksa detail transfer dan konfirmasi</span></li>
                <li><span class="instr-num">6</span><span>Simpan struk sebagai bukti pembayaran</span></li>
              </ul>
            </div>

            <!-- Mobile Banking -->
            <div id="instr-mobile" style="display:none">
              <ul class="instruksi-list">
                <li><span class="instr-num">1</span><span>Buka aplikasi mobile banking bank kamu</span></li>
                <li><span class="instr-num">2</span><span>Login dengan username dan password / biometrik</span></li>
                <li><span class="instr-num">3</span><span>Pilih <strong>Transfer</strong> → <strong>Transfer ke Rekening Lain</strong></span></li>
                <li><span class="instr-num">4</span><span>Masukkan nomor rekening: <strong id="norekMobile"><?=$bankAktif['no_rek']?></strong></span></li>
                <li><span class="instr-num">5</span><span>Masukkan nominal: <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
                <li><span class="instr-num">6</span><span>Konfirmasi dengan PIN / OTP</span></li>
                <li><span class="instr-num">7</span><span>Screenshot bukti transfer untuk diunggah</span></li>
              </ul>
            </div>

            <!-- Internet Banking -->
            <div id="instr-internet" style="display:none">
              <ul class="instruksi-list">
                <li><span class="instr-num">1</span><span>Buka website internet banking bank kamu</span></li>
                <li><span class="instr-num">2</span><span>Login dan pilih menu <strong>Transfer Dana</strong></span></li>
                <li><span class="instr-num">3</span><span>Pilih rekening tujuan atau tambah baru dengan nomor: <strong id="norekInternet"><?=$bankAktif['no_rek']?></strong></span></li>
                <li><span class="instr-num">4</span><span>Masukkan jumlah: <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
                <li><span class="instr-num">5</span><span>Masukkan token / OTP untuk konfirmasi</span></li>
                <li><span class="instr-num">6</span><span>Simpan/screenshot halaman konfirmasi</span></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Upload Bukti Transfer -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">3</div>
            <h6>Upload Bukti Transfer</h6>
            <span class="ms-auto badge bg-warning text-dark" style="font-size:.65rem">Wajib</span>
          </div>
          <div class="pay-card-body">
            <p style="font-size:.8rem;color:var(--gray-600);margin-bottom:14px">
              Upload foto/screenshot bukti transfer agar pembayaran lebih cepat dikonfirmasi. Format: JPG, PNG, atau PDF. Maks 3MB.
            </p>
            <div class="upload-area" id="uploadArea">
              <input type="file" name="bukti" id="buktiFile"
                     accept=".jpg,.jpeg,.png,.pdf"
                     onchange="previewFile(this)"/>
              <div id="uploadPlaceholder">
                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div style="font-weight:700;font-size:.88rem;color:var(--gray-800)">Klik atau seret file ke sini</div>
                <div style="font-size:.75rem;color:var(--gray-600);margin-top:4px">JPG, PNG, PDF • Maks 3MB</div>
              </div>
              <div class="upload-preview" id="uploadPreview">
                <img src="" id="previewImg" alt="Preview"/>
                <div id="pdfIcon" style="display:none;font-size:3rem;color:var(--red)">
                  <i class="fas fa-file-pdf"></i>
                </div>
                <div style="font-size:.78rem;color:var(--green);font-weight:600;margin-top:8px" id="fileName"></div>
                <button type="button" class="btn-remove-file" onclick="removeFile()">
                  <i class="fas fa-times" style="font-size:.55rem"></i>
                </button>
              </div>
            </div>

            <!-- Progress bar -->
            <div class="upload-progress mt-3" id="uploadProgress">
              <div class="d-flex justify-content-between mb-1" style="font-size:.72rem;color:var(--gray-600)">
                <span>Mengunggah...</span>
                <span id="progressPct">0%</span>
              </div>
              <div class="progress" style="height:6px">
                <div class="progress-bar bg-success" id="progressBar" style="width:0%"></div>
              </div>
            </div>
          </div>
        </div>

        <?php elseif($grup === 'ewallet'): ?>

        <!-- ══ E-WALLET ══ -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">1</div>
            <h6>Instruksi Pembayaran E-Wallet</h6>
          </div>
          <div class="pay-card-body">
            <?php
            $ewallets = [
                'gopay'     => ['label'=>'GoPay',      'color'=>'#00AED6','app'=>'Gojek'],
                'ovo'       => ['label'=>'OVO',         'color'=>'#4C3494','app'=>'OVO'],
                'dana'      => ['label'=>'DANA',        'color'=>'#118EEA','app'=>'DANA'],
                'shopeepay' => ['label'=>'ShopeePay',   'color'=>'#EE4D2D','app'=>'Shopee'],
            ];
            $ew = $ewallets[$pesanan['metode_bayar']] ?? $ewallets['gopay'];
            ?>
            <div class="text-center mb-4">
              <div style="width:64px;height:64px;border-radius:16px;background:<?=$ew['color']?>;
                          display:flex;align-items:center;justify-content:center;margin:0 auto 10px">
                <i class="fas fa-wallet" style="color:#fff;font-size:1.6rem"></i>
              </div>
              <div style="font-size:1.1rem;font-weight:800;color:var(--gray-800)"><?=$ew['label']?></div>
              <div style="font-size:.78rem;color:var(--gray-600)">Bayar melalui aplikasi <?=$ew['app']?></div>
            </div>

            <div class="p-3 rounded-3 text-center mb-3" style="background:var(--green-bg);border:1.5px solid #86efac">
              <div style="font-size:.72rem;color:var(--gray-600);font-weight:600">Nominal Pembayaran</div>
              <div style="font-size:1.6rem;font-weight:900;color:var(--green)"><?=rp((int)$pesanan['total'])?></div>
              <div style="font-size:.72rem;color:var(--gray-600)">Nomor HP Tujuan: <strong>08119876543</strong></div>
            </div>

            <ul class="instruksi-list">
              <li><span class="instr-num">1</span><span>Buka aplikasi <strong><?=$ew['app']?></strong> di smartphone kamu</span></li>
              <li><span class="instr-num">2</span><span>Pilih menu <strong>Transfer / Bayar</strong></span></li>
              <li><span class="instr-num">3</span><span>Masukkan nomor HP: <strong>08119876543</strong> (HikeGear Official)</span></li>
              <li><span class="instr-num">4</span><span>Masukkan nominal tepat: <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
              <li><span class="instr-num">5</span><span>Isi keterangan/berita: <strong><?=eg($pesanan['kode_pesanan'])?></strong></span></li>
              <li><span class="instr-num">6</span><span>Konfirmasi dan selesaikan pembayaran</span></li>
              <li><span class="instr-num">7</span><span>Screenshot bukti lalu upload di bawah</span></li>
            </ul>
          </div>
        </div>

        <!-- Upload untuk e-wallet -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">2</div>
            <h6>Upload Bukti Pembayaran</h6>
          </div>
          <div class="pay-card-body">
            <div class="upload-area" id="uploadArea">
              <input type="file" name="bukti" id="buktiFile" accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(this)"/>
              <div id="uploadPlaceholder">
                <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                <div style="font-weight:700;font-size:.88rem">Klik atau seret file ke sini</div>
                <div style="font-size:.75rem;color:var(--gray-600);margin-top:4px">JPG, PNG, PDF • Maks 3MB</div>
              </div>
              <div class="upload-preview" id="uploadPreview">
                <img src="" id="previewImg" alt="Preview"/>
                <div id="pdfIcon" style="display:none;font-size:3rem;color:var(--red)"><i class="fas fa-file-pdf"></i></div>
                <div style="font-size:.78rem;color:var(--green);font-weight:600;margin-top:8px" id="fileName"></div>
                <button type="button" class="btn-remove-file" onclick="removeFile()"><i class="fas fa-times" style="font-size:.55rem"></i></button>
              </div>
            </div>
          </div>
        </div>

        <?php elseif($grup === 'qris'): ?>

        <!-- ══ QRIS ══ -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">1</div>
            <h6>Bayar dengan QRIS</h6>
          </div>
          <div class="pay-card-body">
            <div class="qris-box">
              <div class="qris-img-wrap">
                <i class="fas fa-qrcode"></i>
              </div>
              <div style="font-size:.75rem;color:var(--gray-600);margin-bottom:16px">
                Scan QR di atas dengan aplikasi apapun yang mendukung QRIS
              </div>
              <div class="p-3 rounded-3 d-inline-block" style="background:var(--green-bg);border:1.5px solid #86efac">
                <div style="font-size:.72rem;color:var(--gray-600)">Total Pembayaran</div>
                <div style="font-size:1.4rem;font-weight:900;color:var(--green)"><?=rp((int)$pesanan['total'])?></div>
              </div>
            </div>
            <hr>
            <ul class="instruksi-list">
              <li><span class="instr-num">1</span><span>Buka aplikasi e-wallet / mobile banking yang mendukung QRIS</span></li>
              <li><span class="instr-num">2</span><span>Pilih <strong>Scan QR / QRIS</strong></span></li>
              <li><span class="instr-num">3</span><span>Arahkan kamera ke QR code di atas</span></li>
              <li><span class="instr-num">4</span><span>Pastikan nominal: <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
              <li><span class="instr-num">5</span><span>Konfirmasi dan selesaikan pembayaran</span></li>
            </ul>
          </div>
        </div>

        <?php elseif($grup === 'cod'): ?>

        <!-- ══ COD ══ -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <div class="num">1</div>
            <h6>Bayar di Tempat (COD)</h6>
          </div>
          <div class="pay-card-body">
            <div class="cod-box">
              <div style="font-size:2.5rem;margin-bottom:12px">💵</div>
              <div style="font-size:1rem;font-weight:800;color:#166534;margin-bottom:6px">Tidak Perlu Bayar Sekarang!</div>
              <div style="font-size:.84rem;color:#166534;margin-bottom:16px">
                Bayar saat paket tiba di tangan kamu.<br>
                Siapkan uang pas senilai:
              </div>
              <div style="font-size:1.6rem;font-weight:900;color:var(--green)"><?=rp((int)$pesanan['total'])?></div>
            </div>
            <ul class="instruksi-list mt-3">
              <li><span class="instr-num">1</span><span>Pastikan kamu berada di alamat pengiriman saat paket tiba</span></li>
              <li><span class="instr-num">2</span><span>Siapkan uang pas senilai <strong><?=rp((int)$pesanan['total'])?></strong></span></li>
              <li><span class="instr-num">3</span><span>Bayar ke kurir saat menerima paket</span></li>
              <li><span class="instr-num">4</span><span>Kurir akan memberikan struk sebagai tanda terima</span></li>
            </ul>
          </div>
        </div>

        <?php endif; ?>

        <!-- ── TOMBOL SUBMIT ─── -->
        <button type="submit" class="btn-bayar" id="btnBayar">
          <i class="fas fa-paper-plane"></i>
          <?php if ($grup === 'cod'): ?>
            Konfirmasi Pesanan COD
          <?php elseif ($grup === 'qris'): ?>
            Saya Sudah Membayar via QRIS
          <?php else: ?>
            Kirim Bukti Pembayaran
          <?php endif; ?>
        </button>

        <div class="text-center mt-3" style="font-size:.72rem;color:var(--gray-600)">
          <i class="fas fa-shield-alt" style="color:var(--green)"></i>
          Data kamu dilindungi enkripsi SSL 256-bit
        </div>
      </form>

    </div><!-- /col-lg-8 -->

    <!-- ══════════════ RIGHT COLUMN: RINGKASAN ══════════════ -->
    <div class="col-lg-4">
      <div class="summary-card">

        <!-- Ringkasan Produk -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <i class="fas fa-shopping-bag" style="color:var(--green)"></i>
            <h6>Produk (<?=count($items)?> item)</h6>
            <a href="pesanan.php" class="ms-auto" style="font-size:.75rem;color:var(--green)">Ubah</a>
          </div>
          <div class="pay-card-body" style="padding:14px 20px">
            <?php foreach($items as $it): ?>
            <div class="prod-item">
              <img class="prod-thumb"
                   src="<?=prodImg($it['gambar_produk'] ?? null)?>"
                   alt="<?=eg($it['nama_produk'])?>"/>
              <div class="flex-1">
                <div class="prod-name"><?=eg($it['nama_produk'])?></div>
                <?php if($it['varian']): ?>
                <div class="prod-var"><?=eg($it['varian'])?></div>
                <?php endif; ?>
                <div class="prod-var">Qty: <?=(int)$it['qty']?></div>
              </div>
              <div class="prod-price"><?=rp((int)$it['harga'] * (int)$it['qty'])?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Rincian Biaya -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <i class="fas fa-receipt" style="color:var(--green)"></i>
            <h6>Rincian Pembayaran</h6>
          </div>
          <div class="pay-card-body" style="padding:14px 20px">
            <div class="summary-row">
              <span>Subtotal</span>
              <span><?=rp((int)$pesanan['subtotal'])?></span>
            </div>
            <div class="summary-row">
              <span>Ongkos Kirim</span>
              <span><?=$pesanan['ongkir'] > 0 ? rp((int)$pesanan['ongkir']) : '<span style="color:var(--green);font-weight:700">GRATIS</span>'?></span>
            </div>
            <?php if($pesanan['diskon'] > 0): ?>
            <div class="summary-row" style="color:#16a34a">
              <span>Diskon Voucher</span>
              <span style="font-weight:700">-<?=rp((int)$pesanan['diskon'])?></span>
            </div>
            <?php endif; ?>
            <div class="summary-row total">
              <span>Total Bayar</span>
              <span style="color:var(--green)"><?=rp((int)$pesanan['total'])?></span>
            </div>
          </div>
        </div>

        <!-- Info Pengiriman -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <i class="fas fa-truck" style="color:var(--green)"></i>
            <h6>Info Pengiriman</h6>
          </div>
          <div class="pay-card-body" style="padding:14px 20px">
            <div style="font-size:.78rem">
              <div class="d-flex gap-2 mb-2">
                <i class="fas fa-user" style="color:var(--gray-600);width:16px;margin-top:2px"></i>
                <span><?=eg($pesanan['nama_penerima'])?></span>
              </div>
              <div class="d-flex gap-2 mb-2">
                <i class="fas fa-map-marker-alt" style="color:var(--gray-600);width:16px;margin-top:2px"></i>
                <span><?=eg($pesanan['kota'])?></span>
              </div>
              <div class="d-flex gap-2">
                <i class="fas fa-box" style="color:var(--gray-600);width:16px;margin-top:2px"></i>
                <span><?=eg($pesanan['ekspedisi'])?> · <?=eg($pesanan['estimasi'])?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="pay-card mb-3">
          <div class="pay-card-head">
            <i class="fas fa-credit-card" style="color:var(--green)"></i>
            <h6>Metode Pembayaran</h6>
          </div>
          <div class="pay-card-body" style="padding:14px 20px">
            <div class="d-flex align-items-center gap-2">
              <i class="fas fa-<?=$grup==='bank'?'university':($grup==='ewallet'?'wallet':($grup==='qris'?'qrcode':'money-bill'))?>"
                 style="color:var(--green);font-size:1.1rem"></i>
              <div>
                <div style="font-size:.82rem;font-weight:700"><?=eg($metode['label'] ?? ucfirst($pesanan['metode_bayar']))?></div>
                <div style="font-size:.7rem;color:var(--gray-600)">
                  <?=$grup==='bank'?'Transfer rekening':'Bayar digital'?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Butuh bantuan? -->
        <div class="pay-card">
          <div class="pay-card-body text-center" style="padding:16px">
            <i class="fas fa-headset" style="color:var(--green);font-size:1.4rem;margin-bottom:8px"></i>
            <div style="font-size:.8rem;font-weight:700;margin-bottom:4px">Butuh Bantuan?</div>
            <div style="font-size:.73rem;color:var(--gray-600);margin-bottom:12px">CS kami siap membantu 24/7</div>
            <a href="https://wa.me/628119876543" target="_blank"
               class="btn btn-sm w-100"
               style="background:#25d366;color:#fff;border-radius:8px;font-weight:600;font-size:.78rem">
              <i class="fab fa-whatsapp"></i> Chat WhatsApp
            </a>
          </div>
        </div>

      </div><!-- /summary-card -->
    </div><!-- /col-lg-4 -->

  </div><!-- /row -->
</div><!-- /container -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ══════════════════════════════════════════════════════════
//  COUNTDOWN TIMER
// ══════════════════════════════════════════════════════════
const deadline = <?=strtotime($pesanan['bayar_sebelum'])?> * 1000;

function updateTimer() {
  const diff = Math.max(0, deadline - Date.now());
  const h    = String(Math.floor(diff / 3600000)).padStart(2, '0');
  const m    = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
  const s    = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');

  document.getElementById('th').textContent = h;
  document.getElementById('tm').textContent = m;
  document.getElementById('ts').textContent = s;

  // Merah jika < 30 menit
  if (diff < 1800000) {
    document.getElementById('countdownWrap').classList.add('danger');
  }

  // Kadaluarsa
  if (diff === 0) {
    clearInterval(timerInterval);
    showToast('Waktu pembayaran habis. Silakan buat pesanan baru.', 'error');
    setTimeout(() => location.href = 'pesanan.php', 3000);
  }
}
const timerInterval = setInterval(updateTimer, 1000);
updateTimer();

// ══════════════════════════════════════════════════════════
//  PILIH BANK
// ══════════════════════════════════════════════════════════
function pilihBank(kode, el) {
  document.querySelectorAll('.bank-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('metodeTerpilih').value = kode;
}

// ══════════════════════════════════════════════════════════
//  SWITCH INSTRUKSI
// ══════════════════════════════════════════════════════════
function switchInstruksi(tipe, btn) {
  ['atm','mobile','internet'].forEach(t => {
    const el = document.getElementById('instr-' + t);
    if (el) el.style.display = t === tipe ? 'block' : 'none';
  });
  document.querySelectorAll('.metode-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// ══════════════════════════════════════════════════════════
//  COPY TO CLIPBOARD
// ══════════════════════════════════════════════════════════
function copyText(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Tersalin!';
    btn.classList.add('copied');
    showToast('Berhasil disalin!', 'success');
    setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('copied'); }, 2000);
  });
}

// ══════════════════════════════════════════════════════════
//  UPLOAD PREVIEW
// ══════════════════════════════════════════════════════════
function previewFile(input) {
  const file = input.files[0];
  if (!file) return;

  const maxSize = 3 * 1024 * 1024;
  if (file.size > maxSize) {
    showToast('Ukuran file maksimal 3MB!', 'error');
    input.value = '';
    return;
  }

  const ext = file.name.split('.').pop().toLowerCase();
  if (!['jpg','jpeg','png','pdf'].includes(ext)) {
    showToast('Format file harus JPG, PNG, atau PDF!', 'error');
    input.value = '';
    return;
  }

  document.getElementById('uploadPlaceholder').style.display = 'none';
  document.getElementById('uploadPreview').style.display     = 'block';
  document.getElementById('uploadArea').classList.add('has-file');
  document.getElementById('fileName').textContent = file.name;

  if (ext === 'pdf') {
    document.getElementById('previewImg').style.display = 'none';
    document.getElementById('pdfIcon').style.display    = 'block';
  } else {
    document.getElementById('pdfIcon').style.display    = 'none';
    document.getElementById('previewImg').style.display = 'block';
    const reader = new FileReader();
    reader.onload = e => document.getElementById('previewImg').src = e.target.result;
    reader.readAsDataURL(file);
  }
}

function removeFile() {
  document.getElementById('buktiFile').value          = '';
  document.getElementById('uploadPlaceholder').style.display = 'block';
  document.getElementById('uploadPreview').style.display     = 'none';
  document.getElementById('uploadArea').classList.remove('has-file');
}

// ── Drag & Drop ──
const uploadArea = document.getElementById('uploadArea');
if (uploadArea) {
  ['dragenter','dragover'].forEach(e => uploadArea.addEventListener(e, ev => {
    ev.preventDefault();
    uploadArea.classList.add('drag-over');
  }));
  ['dragleave','drop'].forEach(e => uploadArea.addEventListener(e, ev => {
    ev.preventDefault();
    uploadArea.classList.remove('drag-over');
    if (ev.type === 'drop' && ev.dataTransfer.files.length) {
      const input = document.getElementById('buktiFile');
      input.files = ev.dataTransfer.files;
      previewFile(input);
    }
  }));
}

// ══════════════════════════════════════════════════════════
//  FORM SUBMIT (AJAX)
// ══════════════════════════════════════════════════════════
document.getElementById('payForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const metode = document.getElementById('metodeTerpilih').value;
  const isCod  = metode === 'cod';
  const isQris = metode === 'qris';
  const bukti  = document.getElementById('buktiFile');

  // COD & QRIS tidak butuh bukti upload
  if (!isCod && !isQris && bukti && bukti.files.length === 0) {
    showToast('Harap upload bukti pembayaran terlebih dahulu!', 'error');
    document.getElementById('uploadArea').scrollIntoView({behavior:'smooth', block:'center'});
    return;
  }

  const btn = document.getElementById('btnBayar');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

  // Show progress bar
  const prog = document.getElementById('uploadProgress');
  if (prog) prog.style.display = 'block';

  const formData = new FormData(this);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'pembayaran-proses.php', true);

  // Upload progress
  xhr.upload.onprogress = e => {
    if (e.lengthComputable) {
      const pct = Math.round((e.loaded / e.total) * 100);
      const bar = document.getElementById('progressBar');
      const txt = document.getElementById('progressPct');
      if (bar) bar.style.width = pct + '%';
      if (txt) txt.textContent  = pct + '%';
    }
  };

  xhr.onload = function() {
    if (xhr.status === 200) {
      try {
        const res = JSON.parse(xhr.responseText);
        if (res.success) {
          showToast(res.message || 'Bukti berhasil dikirim!', 'success');
          setTimeout(() => location.href = res.redirect || 'pembayaran-menunggu.php?kode=<?=eg($pesanan['kode_pesanan'])?>', 1500);
        } else {
          showToast(res.message || 'Terjadi kesalahan.', 'error');
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran';
          if (prog) prog.style.display = 'none';
        }
      } catch {
        showToast('Respons server tidak valid.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran';
      }
    } else {
      showToast('Gagal menghubungi server (HTTP ' + xhr.status + ').', 'error');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran';
    }
  };

  xhr.onerror = () => {
    showToast('Koneksi bermasalah. Coba lagi.', 'error');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran';
  };

  xhr.send(formData);
});

// ══════════════════════════════════════════════════════════
//  POLLING STATUS (cek tiap 30 detik)
// ══════════════════════════════════════════════════════════
setInterval(() => {
  fetch('pembayaran-status.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'kode=<?=eg($pesanan['kode_pesanan'])?>&csrf_token=<?=eg($csrf)?>'
  })
  .then(r => r.json())
  .then(data => {
    if (data.paid) {
      showToast('Pembayaran dikonfirmasi! Mengalihkan...', 'success');
      setTimeout(() => location.href = data.redirect, 1500);
    }
  })
  .catch(() => {}); // silent fail
}, 30000);

// ══════════════════════════════════════════════════════════
//  TOAST
// ══════════════════════════════════════════════════════════
let toastTimer;
function showToast(msg, type = 'success') {
  const el   = document.getElementById('toast');
  const icon = document.getElementById('toastIcon');
  el.className = 'toast-custom toast-' + type;
  icon.className = 'fas fa-' + (type==='success'?'check-circle':type==='error'?'times-circle':'info-circle');
  document.getElementById('toastMsg').textContent = msg;
  el.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), 4000);
}
</script>
</body>
</html>