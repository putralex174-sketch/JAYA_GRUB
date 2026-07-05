<?php
// ============================================================
//  selesai.php — HikeGear | Halaman Pesanan Selesai / Sukses
//  Tampil setelah pembayaran dikonfirmasi
// ============================================================

if (!isset($pesanan)) {
    $pesanan = [
        'kode_pesanan' => 'HG-20250701-AB1C2',
        'total'        => 2163000,
        'metode_bayar' => 'bca_transfer',
        'status_bayar' => 'paid',
        'status'       => 'diproses',
        'dibayar_at'   => date('Y-m-d H:i:s'),
        'ekspedisi'    => 'JNE REG',
        'estimasi'     => '2-3 Hari',
        'nama_penerima'=> 'Budi Santoso',
        'hp_penerima'  => '081234567890',
        'alamat_detail'=> 'Jl. Mawar No. 12, RT 01/02',
        'kecamatan'    => 'Sukolilo',
        'kota'         => 'Surabaya',
        'provinsi'     => 'Jawa Timur',
        'kode_pos'     => '60119',
        'catatan'      => 'Tolong dibungkus rapi.',
        'created_at'   => date('Y-m-d H:i:s'),
    ];
    $items = [
        ['nama_produk'=>'Eiger Mountaineer Jacket','varian'=>'Hitam / L','harga'=>1250000,'qty'=>1,'gambar_produk'=>null,'produk_slug'=>'eiger-mountaineer-jacket'],
        ['nama_produk'=>'Consina Amago 45L',        'varian'=>'Merah',   'harga'=>895000, 'qty'=>1,'gambar_produk'=>null,'produk_slug'=>'consina-amago-45l'],
    ];
    $user = ['nama'=>'Budi Santoso', 'email'=>'budi@email.com'];
}

function rp(int $n): string { return 'Rp '.number_format($n,0,',','.'); }
function eg(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function prodImg(?string $f): string {
    return ($f && file_exists("uploads/products/$f"))
        ? "uploads/products/$f"
        : "assets/img/no-image.png";
}

$metodLabel = [
    'bca_transfer'     => 'Transfer BCA',
    'bni_transfer'     => 'Transfer BNI',
    'bri_transfer'     => 'Transfer BRI',
    'mandiri_transfer' => 'Transfer Mandiri',
    'gopay'            => 'GoPay',
    'ovo'              => 'OVO',
    'dana'             => 'DANA',
    'shopeepay'        => 'ShopeePay',
    'qris'             => 'QRIS',
    'cod'              => 'Bayar di Tempat (COD)',
];
$statusLabel = [
    'pending'      => ['Menunggu',   'warning', 'clock'],
    'dikonfirmasi' => ['Dikonfirmasi','info',   'check-circle'],
    'diproses'     => ['Diproses',   'primary', 'cog'],
    'dikirim'      => ['Dikirim',    'success', 'truck'],
    'selesai'      => ['Selesai',    'success', 'check-double'],
    'dibatalkan'   => ['Dibatalkan', 'danger',  'times-circle'],
];
$sts = $statusLabel[$pesanan['status']] ?? ['Pending','secondary','question'];

// Estimasi tiba
$tiba = date('d M Y', strtotime('+' . (str_contains($pesanan['estimasi']??'','1-2')?'2':'3') . ' days'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Pesanan Selesai – HikeGear</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>

<style>
:root {
  --green:      #1e7e3e;
  --green-dark: #155d2c;
  --green-light:#e8f5ee;
  --green-bg:   #f0faf3;
  --gray-50:    #f9fafb;
  --gray-100:   #f3f4f6;
  --gray-200:   #e5e7eb;
  --gray-600:   #4b5563;
  --gray-800:   #1f2937;
  --shadow-sm:  0 1px 3px rgba(0,0,0,.08);
  --shadow-md:  0 4px 16px rgba(0,0,0,.10);
  --shadow-lg:  0 10px 32px rgba(0,0,0,.13);
  --radius:     14px;
  --radius-sm:  8px;
}
*  { box-sizing: border-box; }
body { font-family: 'Inter', sans-serif; background: var(--gray-50); color: var(--gray-800); font-size: 14px; }

/* ── Navbar ── */
.navbar-brand strong { font-size: 1.15rem; font-weight: 900; }
.navbar-brand strong span { color: #4caf63; }
.navbar-brand small  { font-size: .58rem; letter-spacing: 1.8px; text-transform: uppercase; color: rgba(255,255,255,.6); display: block; }

/* ── Hero Sukses ── */
.success-hero {
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d0 100%);
  border-bottom: 1px solid #86efac;
  padding: 48px 0 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.success-hero::before {
  content: '';
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 30% 50%, rgba(76,175,99,.12) 0%, transparent 60%),
              radial-gradient(ellipse at 70% 50%, rgba(30,126,62,.08) 0%, transparent 60%);
}
.success-hero > * { position: relative; z-index: 1; }

.checkmark-wrap {
  width: 100px; height: 100px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 20px;
  box-shadow: 0 8px 30px rgba(30,126,62,.35);
  animation: popIn .5s cubic-bezier(.34,1.56,.64,1);
}
@keyframes popIn { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.checkmark-wrap i { color: #fff; font-size: 2.6rem; }

.confetti {
  position: absolute; top: 0; left: 0; right: 0; bottom: 0;
  overflow: hidden; pointer-events: none;
}
.confetti-piece {
  position: absolute; width: 8px; height: 8px; border-radius: 2px;
  animation: fall linear infinite;
  opacity: 0;
}
@keyframes fall {
  0%   { transform: translateY(-20px) rotate(0deg); opacity: 1; }
  100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
}

/* ── Cards ── */
.info-card {
  background: #fff;
  border: 1.5px solid var(--gray-200);
  border-radius: var(--radius);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  margin-bottom: 16px;
}
.info-card-head {
  padding: 13px 20px;
  border-bottom: 1px solid var(--gray-100);
  background: var(--gray-50);
  display: flex; align-items: center; gap: 10px;
}
.info-card-head h6 { margin: 0; font-size: .9rem; font-weight: 700; }
.info-card-body { padding: 18px 20px; }

/* ── Timeline ── */
.timeline { position: relative; padding-left: 28px; }
.timeline::before {
  content: ''; position: absolute;
  left: 10px; top: 4px; bottom: 4px;
  width: 2px; background: var(--gray-200);
  border-radius: 1px;
}
.tl-item {
  position: relative; margin-bottom: 20px;
  animation: slideIn .4s ease both;
}
.tl-item:last-child { margin-bottom: 0; }
@keyframes slideIn { from { opacity:0; transform: translateX(-10px); } to { opacity:1; transform:none; } }
.tl-dot {
  position: absolute; left: -22px; top: 2px;
  width: 18px; height: 18px; border-radius: 50%;
  border: 2.5px solid var(--gray-200);
  background: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .55rem;
}
.tl-dot.done  { background: var(--green); border-color: var(--green); color: #fff; }
.tl-dot.active{ background: #fff; border-color: var(--green); color: var(--green); }
.tl-title { font-size: .82rem; font-weight: 700; color: var(--gray-800); }
.tl-sub   { font-size: .72rem; color: var(--gray-600); margin-top: 2px; }

/* ── Detail Rows ── */
.detail-row {
  display: flex; justify-content: space-between; align-items: flex-start;
  padding: 8px 0; border-bottom: 1px solid var(--gray-100);
  font-size: .82rem; gap: 12px;
}
.detail-row:last-child { border-bottom: none; }
.detail-row .lbl { color: var(--gray-600); flex-shrink: 0; min-width: 110px; }
.detail-row .val { font-weight: 600; text-align: right; }

/* ── Produk Item ── */
.prod-item { display: flex; gap: 12px; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid var(--gray-100); }
.prod-item:last-child { border-bottom: none; }
.prod-thumb { width: 56px; height: 56px; border-radius: 8px; object-fit: cover; background: var(--gray-100); flex-shrink: 0; }
.prod-name  { font-size: .82rem; font-weight: 600; margin-bottom: 2px; }
.prod-var   { font-size: .72rem; color: var(--gray-600); }
.prod-price { font-size: .82rem; font-weight: 700; white-space: nowrap; }

/* ── Ringkasan Biaya ── */
.sum-row { display: flex; justify-content: space-between; padding: 7px 0; font-size: .82rem; color: var(--gray-600); border-bottom: 1px solid var(--gray-100); }
.sum-row:last-child { border-bottom: none; }
.sum-row.total { font-size: 1rem; font-weight: 800; color: var(--gray-800); padding-top: 12px; border-top: 2px solid var(--gray-200); margin-top: 8px; }

/* ── Action Buttons ── */
.btn-lacak {
  width: 100%; padding: 13px;
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  color: #fff; border: none; border-radius: var(--radius-sm);
  font-size: .9rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: opacity .18s, transform .12s;
  box-shadow: 0 4px 14px rgba(30,126,62,.35);
  text-decoration: none;
}
.btn-lacak:hover { opacity: .92; transform: translateY(-1px); color: #fff; }
.btn-outline-green {
  width: 100%; padding: 11px;
  background: #fff; color: var(--green);
  border: 2px solid var(--green); border-radius: var(--radius-sm);
  font-size: .85rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: all .18s; text-decoration: none;
}
.btn-outline-green:hover { background: var(--green-light); color: var(--green); }

/* ── Invoice ── */
.invoice-banner {
  background: linear-gradient(135deg, #eff6ff, #dbeafe);
  border: 1.5px solid #93c5fd;
  border-radius: var(--radius-sm);
  padding: 14px 16px;
  display: flex; align-items: center; gap: 10px;
}

/* ── Rekomendasi ── */
.rek-card {
  background: #fff; border: 1.5px solid var(--gray-200);
  border-radius: 10px; overflow: hidden;
  transition: transform .18s, box-shadow .18s;
}
.rek-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
.rek-img { width: 100%; height: 130px; object-fit: cover; background: var(--gray-100); }
.rek-body { padding: 10px 12px; }
.rek-name { font-size: .78rem; font-weight: 600; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.rek-price { font-size: .82rem; font-weight: 800; color: var(--green); }

/* ── WhatsApp Float ── */
.wa-float {
  position: fixed; bottom: 24px; right: 24px; z-index: 999;
  width: 52px; height: 52px; border-radius: 50%;
  background: #25d366; color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; box-shadow: 0 4px 16px rgba(0,0,0,.2);
  text-decoration: none;
  animation: bounce 2s ease-in-out infinite;
}
@keyframes bounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

/* ── Print ── */
@media print {
  .no-print, nav, .wa-float { display: none !important; }
  .info-card { box-shadow: none; border: 1px solid #ccc; }
  body { font-size: 12px; }
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .success-hero { padding: 32px 0 28px; }
  .checkmark-wrap { width: 80px; height: 80px; }
  .checkmark-wrap i { font-size: 2rem; }
}
</style>
</head>
<body>

<!-- ── NAVBAR ─────────────────────────────────────────────── -->
<nav class="navbar navbar-dark sticky-top no-print"
     style="background:linear-gradient(135deg,#1a5c2a,#2d7a3a);border-bottom:1px solid rgba(255,255,255,.1)">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <div style="width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center">
        <i class="fas fa-mountain" style="color:#4caf63;font-size:1rem"></i>
      </div>
      <div>
        <strong>HIKE<span>GEAR</span></strong>
        <small>Adventure Starts Here</small>
      </div>
    </a>
    <div class="d-flex align-items-center gap-3">
      <a href="pesanan.php" class="text-white-50 text-decoration-none" style="font-size:.78rem">
        <i class="fas fa-box me-1"></i>Pesananku
      </a>
      <a href="index.php" class="text-white-50 text-decoration-none" style="font-size:.78rem">
        <i class="fas fa-home me-1"></i>Beranda
      </a>
    </div>
  </div>
</nav>

<!-- ── HERO SUKSES ─────────────────────────────────────────── -->
<div class="success-hero">
  <!-- Confetti -->
  <div class="confetti" id="confetti"></div>

  <div class="container">
    <div class="checkmark-wrap">
      <i class="fas fa-check"></i>
    </div>
    <h1 style="font-size:1.8rem;font-weight:900;color:#14532d;margin-bottom:6px">
      Pembayaran Berhasil! 🎉
    </h1>
    <p style="color:#166534;font-size:.9rem;margin-bottom:20px">
      Terima kasih <?=eg($user['nama'] ?? 'Kamu')?>, pesananmu sedang diproses
    </p>
    <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill"
         style="background:rgba(255,255,255,.7);backdrop-filter:blur(6px);border:1.5px solid #86efac">
      <i class="fas fa-hashtag" style="color:var(--green);font-size:.8rem"></i>
      <span style="font-size:.85rem;font-weight:800;color:var(--gray-800);letter-spacing:.5px">
        <?=eg($pesanan['kode_pesanan'])?>
      </span>
      <button class="btn-copy-inline" onclick="copyKode(this)" style="background:none;border:none;color:var(--green);cursor:pointer;padding:0;font-size:.75rem;font-weight:700">
        <i class="fas fa-copy"></i> Salin
      </button>
    </div>
  </div>
</div>

<!-- ── MAIN ────────────────────────────────────────────────── -->
<div class="container py-4 pb-5">
  <div class="row g-4">

    <!-- ════════ LEFT ════════ -->
    <div class="col-lg-8">

      <!-- STATUS TRACKER -->
      <div class="info-card">
        <div class="info-card-head">
          <i class="fas fa-route" style="color:var(--green)"></i>
          <h6>Status Pesanan</h6>
          <span class="ms-auto badge bg-<?=$sts[1]?>" style="font-size:.7rem">
            <i class="fas fa-<?=$sts[2]?> me-1"></i><?=$sts[0]?>
          </span>
        </div>
        <div class="info-card-body">
          <div class="timeline">

            <?php
            $steps = [
              ['label'=>'Pesanan Dibuat',        'sub'=>'Pesanan ' . $pesanan['kode_pesanan'] . ' berhasil dibuat', 'done'=>true, 'time'=> date('d M Y, H:i', strtotime($pesanan['created_at']))],
              ['label'=>'Pembayaran Dikonfirmasi','sub'=>'Pembayaran sebesar ' . rp((int)$pesanan['total']) . ' telah diterima', 'done'=>$pesanan['status_bayar']==='paid', 'time'=> $pesanan['dibayar_at'] ? date('d M Y, H:i', strtotime($pesanan['dibayar_at'])) : '-'],
              ['label'=>'Pesanan Diproses',       'sub'=>'Penjual sedang menyiapkan paketmu', 'done'=>in_array($pesanan['status'],['diproses','dikirim','selesai']), 'time'=>in_array($pesanan['status'],['diproses','dikirim','selesai'])?'Sedang diproses':'-'],
              ['label'=>'Paket Dikirim',          'sub'=>'Paket dalam perjalanan ke ' . $pesanan['kota'], 'done'=>in_array($pesanan['status'],['dikirim','selesai']), 'time'=>in_array($pesanan['status'],['dikirim','selesai'])?'Dalam pengiriman':'-'],
              ['label'=>'Pesanan Selesai',        'sub'=>'Paket telah diterima, terima kasih!', 'done'=>$pesanan['status']==='selesai', 'time'=>$pesanan['status']==='selesai'?'Selesai':('Est. ' . $tiba)],
            ];
            $currentStep = array_search(true, array_reverse(array_column($steps, 'done')));
            foreach ($steps as $i => $s):
              $isDone   = $s['done'];
              $isActive = !$isDone && ($i === (count(array_filter(array_column($steps,'done'))));
            ?>
            <div class="tl-item" style="animation-delay: <?=$i*.08?>s">
              <div class="tl-dot <?=$isDone?'done':($isActive?'active':'')?>">
                <?php if($isDone): ?><i class="fas fa-check" style="font-size:.5rem"></i><?php else: ?><?=$i+1?><?php endif; ?>
              </div>
              <div class="tl-title"><?=eg($s['label'])?></div>
              <div class="tl-sub"><?=eg($s['sub'])?></div>
              <?php if($s['time'] && $s['time'] !== '-'): ?>
              <div style="font-size:.68rem;color:var(--green);font-weight:600;margin-top:2px">
                <i class="fas fa-clock me-1"></i><?=eg($s['time'])?>
              </div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- DETAIL PRODUK -->
      <div class="info-card">
        <div class="info-card-head">
          <i class="fas fa-shopping-bag" style="color:var(--green)"></i>
          <h6>Produk yang Dipesan (<?=count($items)?> item)</h6>
        </div>
        <div class="info-card-body">
          <?php foreach($items as $it): ?>
          <div class="prod-item">
            <img class="prod-thumb" src="<?=prodImg($it['gambar_produk']??null)?>" alt="<?=eg($it['nama_produk'])?>"/>
            <div class="flex-fill">
              <div class="prod-name"><?=eg($it['nama_produk'])?></div>
              <?php if(!empty($it['varian'])): ?>
              <div class="prod-var"><i class="fas fa-tag me-1"></i><?=eg($it['varian'])?></div>
              <?php endif; ?>
              <div class="prod-var">Qty: <?=(int)$it['qty']?></div>
            </div>
            <div class="prod-price"><?=rp((int)$it['harga'] * (int)$it['qty'])?></div>
          </div>
          <?php endforeach; ?>

          <!-- Ringkasan Biaya -->
          <div class="mt-3 pt-2">
            <div class="sum-row"><span>Subtotal</span><span><?=rp((int)($pesanan['subtotal']??array_sum(array_map(fn($i)=>$i['harga']*$i['qty'],$items))))?></span></div>
            <div class="sum-row">
              <span>Ongkos Kirim (<?=eg($pesanan['ekspedisi'])?>)</span>
              <span><?=$pesanan['ongkir']>0 ? rp((int)$pesanan['ongkir']) : '<span style="color:var(--green);font-weight:700">GRATIS</span>'?></span>
            </div>
            <?php if(!empty($pesanan['diskon']) && $pesanan['diskon']>0): ?>
            <div class="sum-row" style="color:#16a34a"><span>Diskon</span><span>-<?=rp((int)$pesanan['diskon'])?></span></div>
            <?php endif; ?>
            <div class="sum-row total"><span>Total Dibayar</span><span style="color:var(--green)"><?=rp((int)$pesanan['total'])?></span></div>
          </div>
        </div>
      </div>

      <!-- INFO PENGIRIMAN & PEMBAYARAN -->
      <div class="row g-3">
        <div class="col-md-6">
          <div class="info-card">
            <div class="info-card-head">
              <i class="fas fa-map-marker-alt" style="color:var(--green)"></i>
              <h6>Alamat Pengiriman</h6>
            </div>
            <div class="info-card-body">
              <div class="detail-row">
                <span class="lbl"><i class="fas fa-user me-1"></i>Penerima</span>
                <span class="val"><?=eg($pesanan['nama_penerima'])?></span>
              </div>
              <div class="detail-row">
                <span class="lbl"><i class="fas fa-phone me-1"></i>Nomor HP</span>
                <span class="val"><?=eg($pesanan['hp_penerima']??'-')?></span>
              </div>
              <div class="detail-row">
                <span class="lbl"><i class="fas fa-home me-1"></i>Alamat</span>
                <span class="val"><?=eg($pesanan['alamat_detail']??'-')?></span>
              </div>
              <div class="detail-row">
                <span class="lbl"><i class="fas fa-city me-1"></i>Kota</span>
                <span class="val"><?=eg($pesanan['kecamatan']??'')?>, <?=eg($pesanan['kota'])?></span>
              </div>
              <div class="detail-row">
                <span class="lbl"><i class="fas fa-map me-1"></i>Provinsi</span>
                <span class="val"><?=eg($pesanan['provinsi']??'-')?>, <?=eg($pesanan['kode_pos']??'-')?></span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-card">
            <div class="info-card-head">
              <i class="fas fa-credit-card" style="color:var(--green)"></i>
              <h6>Detail Pembayaran</h6>
            </div>
            <div class="info-card-body">
              <div class="detail-row">
                <span class="lbl">Metode</span>
                <span class="val"><?=eg($metodLabel[$pesanan['metode_bayar']]??$pesanan['metode_bayar'])?></span>
              </div>
              <div class="detail-row">
                <span class="lbl">Status</span>
                <span class="val">
                  <span class="badge bg-<?=$pesanan['status_bayar']==='paid'?'success':'warning'?> text-<?=$pesanan['status_bayar']==='paid'?'white':'dark'?>" style="font-size:.7rem">
                    <?=$pesanan['status_bayar']==='paid'?'Lunas':'Menunggu'?>
                  </span>
                </span>
              </div>
              <?php if($pesanan['dibayar_at']): ?>
              <div class="detail-row">
                <span class="lbl">Dibayar</span>
                <span class="val"><?=date('d M Y, H:i', strtotime($pesanan['dibayar_at']))?></span>
              </div>
              <?php endif; ?>
              <div class="detail-row">
                <span class="lbl">Ekspedisi</span>
                <span class="val"><?=eg($pesanan['ekspedisi'])?></span>
              </div>
              <div class="detail-row">
                <span class="lbl">Estimasi Tiba</span>
                <span class="val" style="color:var(--green);font-weight:700"><?=$tiba?></span>
              </div>
              <?php if(!empty($pesanan['catatan'])): ?>
              <div class="detail-row">
                <span class="lbl">Catatan</span>
                <span class="val"><?=eg($pesanan['catatan'])?></span>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- INVOICE / DOWNLOAD -->
      <div class="invoice-banner mt-3">
        <i class="fas fa-file-invoice" style="color:#3b82f6;font-size:1.3rem"></i>
        <div class="flex-fill">
          <div style="font-size:.82rem;font-weight:700;color:#1e3a8a">Invoice Pesanan</div>
          <div style="font-size:.72rem;color:#3b82f6">Invoice akan tersedia setelah pesanan selesai</div>
        </div>
        <button onclick="window.print()"
                class="btn btn-sm"
                style="background:#3b82f6;color:#fff;border-radius:7px;font-size:.75rem;font-weight:700;border:none;padding:7px 14px;cursor:pointer">
          <i class="fas fa-print me-1"></i>Cetak
        </button>
      </div>

    </div><!-- /col-lg-8 -->

    <!-- ════════ RIGHT ════════ -->
    <div class="col-lg-4">
      <div style="position:sticky;top:80px">

        <!-- AKSI UTAMA -->
        <div class="info-card mb-3">
          <div class="info-card-body d-flex flex-column gap-2">

            <a href="lacak-pesanan.php?kode=<?=eg($pesanan['kode_pesanan'])?>"
               class="btn-lacak">
              <i class="fas fa-search-location"></i>
              Lacak Pesanan
            </a>

            <a href="pesanan.php" class="btn-outline-green">
              <i class="fas fa-box-open"></i>
              Semua Pesananku
            </a>

            <a href="index.php" class="btn-outline-green" style="border-color:var(--gray-200);color:var(--gray-600)">
              <i class="fas fa-home"></i>
              Kembali ke Beranda
            </a>
          </div>
        </div>

        <!-- RINGKASAN SINGKAT -->
        <div class="info-card mb-3">
          <div class="info-card-head">
            <i class="fas fa-receipt" style="color:var(--green)"></i>
            <h6>Ringkasan</h6>
          </div>
          <div class="info-card-body">
            <div class="detail-row">
              <span class="lbl">Kode Pesanan</span>
              <span class="val d-flex align-items-center gap-1">
                <span id="kodeText"><?=eg($pesanan['kode_pesanan'])?></span>
                <button onclick="copyKode(this)" style="background:none;border:none;color:var(--green);cursor:pointer;padding:0;font-size:.75rem">
                  <i class="fas fa-copy"></i>
                </button>
              </span>
            </div>
            <div class="detail-row">
              <span class="lbl">Tanggal</span>
              <span class="val"><?=date('d M Y', strtotime($pesanan['created_at']))?></span>
            </div>
            <div class="detail-row">
              <span class="lbl">Total</span>
              <span class="val" style="color:var(--green);font-size:.95rem"><?=rp((int)$pesanan['total'])?></span>
            </div>
            <div class="detail-row">
              <span class="lbl">Status</span>
              <span class="val">
                <span class="badge bg-<?=$sts[1]?>" style="font-size:.68rem"><?=$sts[0]?></span>
              </span>
            </div>
          </div>
        </div>

        <!-- BANTUAN -->
        <div class="info-card mb-3">
          <div class="info-card-body text-center" style="padding:18px">
            <i class="fas fa-headset" style="color:var(--green);font-size:1.6rem;margin-bottom:8px"></i>
            <div style="font-size:.85rem;font-weight:700;margin-bottom:4px">Ada kendala?</div>
            <div style="font-size:.75rem;color:var(--gray-600);margin-bottom:14px">
              Tim CS kami siap membantu kamu
            </div>
            <a href="https://wa.me/628119876543?text=Halo+HikeGear,+saya+ingin+menanyakan+pesanan+<?=eg($pesanan['kode_pesanan'])?>"
               target="_blank"
               class="btn btn-sm w-100 mb-2"
               style="background:#25d366;color:#fff;border-radius:8px;font-weight:700;font-size:.8rem;border:none;padding:10px">
              <i class="fab fa-whatsapp me-1"></i> WhatsApp CS
            </a>
            <a href="mailto:cs@hikegear.com?subject=Pesanan%20<?=eg($pesanan['kode_pesanan'])?>"
               class="btn btn-sm w-100"
               style="background:var(--gray-100);color:var(--gray-800);border-radius:8px;font-weight:600;font-size:.78rem;border:1px solid var(--gray-200);padding:9px">
              <i class="fas fa-envelope me-1"></i> Email CS
            </a>
          </div>
        </div>

        <!-- SHARE -->
        <div class="info-card">
          <div class="info-card-body" style="padding:14px 18px">
            <div style="font-size:.78rem;font-weight:700;color:var(--gray-800);margin-bottom:10px;text-align:center">
              Bagikan ke teman pendaki 🏔️
            </div>
            <div class="d-flex gap-2">
              <a href="https://wa.me/?text=Aku+baru+beli+perlengkapan+pendakian+di+HikeGear!+Cek+di+https://hikegear.com"
                 target="_blank"
                 class="btn flex-fill"
                 style="background:#25d366;color:#fff;border-radius:8px;font-size:.75rem;font-weight:700;border:none;padding:8px">
                <i class="fab fa-whatsapp"></i>
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u=https://hikegear.com"
                 target="_blank"
                 class="btn flex-fill"
                 style="background:#1877f2;color:#fff;border-radius:8px;font-size:.75rem;font-weight:700;border:none;padding:8px">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="https://twitter.com/intent/tweet?text=Baru+beli+perlengkapan+pendakian+di+HikeGear!&url=https://hikegear.com"
                 target="_blank"
                 class="btn flex-fill"
                 style="background:#1da1f2;color:#fff;border-radius:8px;font-size:.75rem;font-weight:700;border:none;padding:8px">
                <i class="fab fa-twitter"></i>
              </a>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /col-lg-4 -->

  </div><!-- /row -->

  <!-- PRODUK REKOMENDASI -->
  <div class="mt-5 no-print">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 style="font-weight:800;margin:0">
        <span style="display:inline-block;width:4px;height:18px;background:var(--green);border-radius:2px;margin-right:8px;vertical-align:middle"></span>
        Lengkapi Perlengkapanmu
      </h5>
      <a href="produk.php" style="color:var(--green);font-size:.8rem;font-weight:600;text-decoration:none">
        Lihat Semua <i class="fas fa-chevron-right"></i>
      </a>
    </div>
    <div class="row g-3">
      <?php
      $reks = [
        ['nama'=>'Petzl Tikka Core 450lm','harga'=>520000,'img'=>null,'slug'=>'petzl-tikka-core'],
        ['nama'=>'MSR PocketRocket Deluxe','harga'=>750000,'img'=>null,'slug'=>'msr-pocketrocket'],
        ['nama'=>'Eiger Mummy Bag -5°C',   'harga'=>650000,'img'=>null,'slug'=>'eiger-mummy-bag'],
        ['nama'=>'Consina Trekking Pole',   'harga'=>380000,'img'=>null,'slug'=>'consina-trekking-pole'],
      ];
      foreach($reks as $r):
      ?>
      <div class="col-6 col-md-3">
        <div class="rek-card">
          <img class="rek-img" src="<?=prodImg($r['img'])?>" alt="<?=eg($r['nama'])?>"/>
          <div class="rek-body">
            <div class="rek-name"><?=eg($r['nama'])?></div>
            <div class="rek-price"><?=rp($r['harga'])?></div>
            <a href="produk/<?=eg($r['slug'])?>"
               class="btn btn-sm mt-2 w-100"
               style="background:var(--green-light);color:var(--green);border-radius:7px;font-size:.72rem;font-weight:700;border:1px solid #86efac">
              <i class="fas fa-cart-plus me-1"></i>Tambah
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div><!-- /container -->

<!-- WHATSAPP FLOAT -->
<a href="https://wa.me/628119876543" target="_blank" class="wa-float no-print" title="Chat CS">
  <i class="fab fa-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ── KONFETTI ANIMASI ─────────────────────────────────────────
(function(){
  const colors = ['#4caf63','#1e7e3e','#fbbf24','#34d399','#60a5fa','#f472b6'];
  const container = document.getElementById('confetti');
  if (!container) return;
  for (let i = 0; i < 40; i++) {
    const el  = document.createElement('div');
    el.className = 'confetti-piece';
    el.style.cssText = `
      left: ${Math.random()*100}%;
      background: ${colors[i%colors.length]};
      width: ${6 + Math.random()*6}px;
      height: ${6 + Math.random()*6}px;
      border-radius: ${Math.random()>.5?'50%':'2px'};
      animation-duration: ${2.5 + Math.random()*3}s;
      animation-delay: ${Math.random()*3}s;
    `;
    container.appendChild(el);
  }
})();

// ── COPY KODE PESANAN ────────────────────────────────────────
function copyKode(btn) {
  const kode = '<?=eg($pesanan['kode_pesanan'])?>';
  navigator.clipboard.writeText(kode).then(() => {
    const orig = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i>';
    btn.style.color = '#16a34a';
    setTimeout(() => { btn.innerHTML = orig; btn.style.color = ''; }, 2000);

    // Toast mini
    const t = document.createElement('div');
    t.textContent = '✓ Kode disalin!';
    t.style.cssText = 'position:fixed;bottom:80px;right:24px;background:#1e7e3e;color:#fff;padding:10px 18px;border-radius:8px;font-size:.8rem;font-weight:700;z-index:9999;box-shadow:0 4px 16px rgba(0,0,0,.2);animation:fadeIn .3s ease';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2500);
  });
}

// ── POLLING STATUS (tiap 30 dtk) ─────────────────────────────
let pollCount = 0;
const pollMax = 20; // max 10 menit

function pollStatus() {
  if (pollCount++ >= pollMax) return;
  fetch('pembayaran-status.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'kode=<?=eg($pesanan['kode_pesanan'])?>'
  })
  .then(r => r.json())
  .then(data => {
    if (data.status_order && data.status_order !== '<?=eg($pesanan['status'])?>') {
      location.reload();
    }
  })
  .catch(() => {});
}

// Hanya poll jika status belum selesai
<?php if (!in_array($pesanan['status'], ['selesai','dibatalkan'])): ?>
setInterval(pollStatus, 30000);
<?php endif; ?>

// ── ANIMASI SLIDE IN TIMELINE ─────────────────────────────────
const tlItems = document.querySelectorAll('.tl-item');
tlItems.forEach((el, i) => { el.style.animationDelay = (i * 0.1) + 's'; });
</script>
</body>
</html>