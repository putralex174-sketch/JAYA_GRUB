<?php
// ============================================================
//  selesai.php — HikeGear | Halaman Pesanan Selesai / Sukses
//  Tampil setelah pembayaran dikonfirmasi
// ============================================================
session_start();
require_once __DIR__ . '/KONEKSI.PHP';
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/helpers.php';

$user_id = (int)$_SESSION['user_id'];

$kode_pesanan = trim($_GET['kode'] ?? '');
if (!$kode_pesanan) { header('Location: pesanan.php'); exit; }

$kode_esc = mysqli_real_escape_string($conn, $kode_pesanan);
$pq = mysqli_query($conn, "SELECT * FROM pesanan WHERE kode_pesanan = '$kode_esc' AND user_id = $user_id");
$pesanan = mysqli_fetch_assoc($pq);
if (!$pesanan) { header('Location: pesanan.php'); exit; }

$uq = mysqli_query($conn, "SELECT nama_lengkap, email FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($uq);

$items = [];
$diq = mysqli_query($conn, "SELECT pd.*, p.gambar_utama, p.slug as produk_slug 
                             FROM pesanan_detail pd 
                             LEFT JOIN produk p ON pd.produk_id = p.id 
                             WHERE pd.pesanan_id = {$pesanan['id']}");
while ($d = mysqli_fetch_assoc($diq)) $items[] = $d;

function rp(int $n): string { return 'Rp ' . number_format($n, 0, ',', '.'); }
function eg(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function prodImg(?string $f): string {
    return ($f && file_exists(__DIR__ . '/uploads/products/' . $f))
        ? 'uploads/products/' . $f : 'https://via.placeholder.com/56x56?text=HG';
}

$metodLabel = [
    'bca_transfer'=>'Transfer BCA','bni_transfer'=>'Transfer BNI','bri_transfer'=>'Transfer BRI',
    'mandiri_transfer'=>'Transfer Mandiri','gopay'=>'GoPay','ovo'=>'OVO','dana'=>'DANA',
    'shopeepay'=>'ShopeePay','qris'=>'QRIS','cod'=>'Bayar di Tempat (COD)',
];
$statusLabel = [
    'pending'=>['Menunggu','warning'],'diproses'=>['Diproses','primary'],
    'dikirim'=>['Dikirim','info'],'selesai'=>['Selesai','success'],'dibatalkan'=>['Dibatalkan','danger'],
];
$sts = $statusLabel[$pesanan['status']] ?? ['Pending','secondary'];
$tiba = date('d M Y', strtotime('+3 days'));
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
:root{--green:#1e7e3e;--green-dark:#155d2c;--green-light:#e8f5ee;--green-bg:#f0faf3;--gray-50:#f9fafb;--gray-100:#f3f4f6;--gray-200:#e5e7eb;--gray-600:#4b5563;--gray-800:#1f2937;--shadow-sm:0 1px 3px rgba(0,0,0,.08);--shadow-md:0 4px 16px rgba(0,0,0,.10);--shadow-lg:0 10px 32px rgba(0,0,0,.13);--radius:14px;--radius-sm:8px}
*{box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:var(--gray-50);color:var(--gray-800);font-size:14px}
.navbar-dark{background:linear-gradient(135deg,#1a5c2a,#2d7a3a)!important}
.success-hero{background:linear-gradient(135deg,#f0fdf4,#dcfce7,#bbf7d0);border-bottom:1px solid #86efac;padding:48px 0 40px;text-align:center;position:relative;overflow:hidden}
.checkmark-wrap{width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,var(--green),var(--green-dark));display:flex;align-items:center;justify-content:center;margin:0 auto 18px;box-shadow:0 8px 30px rgba(30,126,62,.35);animation:popIn .5s cubic-bezier(.34,1.56,.64,1)}
@keyframes popIn{from{transform:scale(0);opacity:0}to{transform:scale(1);opacity:1}}
.info-card{background:#fff;border:1.5px solid var(--gray-200);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:16px}
.info-card-head{padding:13px 20px;border-bottom:1px solid var(--gray-100);background:var(--gray-50);display:flex;align-items:center;gap:10px}
.info-card-head h6{margin:0;font-size:.9rem;font-weight:700}
.info-card-body{padding:18px 20px}
.detail-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);font-size:.82rem;gap:12px}
.detail-row:last-child{border-bottom:none}
.detail-row .lbl{color:var(--gray-600);flex-shrink:0;min-width:110px}
.detail-row .val{font-weight:600;text-align:right}
.prod-item{display:flex;gap:12px;align-items:flex-start;padding:10px 0;border-bottom:1px solid var(--gray-100)}
.prod-item:last-child{border-bottom:none}
.prod-thumb{width:52px;height:52px;border-radius:8px;object-fit:cover;background:var(--gray-100);flex-shrink:0}
.prod-name{font-size:.82rem;font-weight:600;margin-bottom:2px}
.prod-var{font-size:.72rem;color:var(--gray-600)}
.prod-price{font-size:.82rem;font-weight:700;white-space:nowrap}
.sum-row{display:flex;justify-content:space-between;padding:7px 0;font-size:.82rem;color:var(--gray-600);border-bottom:1px solid var(--gray-100)}
.sum-row:last-child{border-bottom:none}
.sum-row.total{font-size:1rem;font-weight:800;color:var(--gray-800);padding-top:12px;border-top:2px solid var(--gray-200);margin-top:8px}
.btn-lacak{width:100%;padding:13px;background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;border:none;border-radius:var(--radius-sm);font-size:.9rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(30,126,62,.35);text-decoration:none;transition:opacity .18s}
.btn-lacak:hover{opacity:.92;color:#fff}
.btn-outline-green{width:100%;padding:11px;background:#fff;color:var(--green);border:2px solid var(--green);border-radius:var(--radius-sm);font-size:.85rem;font-weight:700;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .18s;text-decoration:none}
.btn-outline-green:hover{background:var(--green-light);color:var(--green)}
.timeline{position:relative;padding-left:28px}
.timeline::before{content:'';position:absolute;left:10px;top:4px;bottom:4px;width:2px;background:var(--gray-200);border-radius:1px}
.tl-item{position:relative;margin-bottom:18px;animation:slideIn .4s ease both}
.tl-item:last-child{margin-bottom:0}
@keyframes slideIn{from{opacity:0;transform:translateX(-10px)}to{opacity:1;transform:none}}
.tl-dot{position:absolute;left:-22px;top:2px;width:18px;height:18px;border-radius:50%;border:2.5px solid var(--gray-200);background:#fff;display:flex;align-items:center;justify-content:center;font-size:.55rem}
.tl-dot.done{background:var(--green);border-color:var(--green);color:#fff}
.tl-dot.active{background:#fff;border-color:var(--green);color:var(--green)}
.tl-title{font-size:.82rem;font-weight:700;color:var(--gray-800)}
.tl-sub{font-size:.72rem;color:var(--gray-600);margin-top:2px}
.wa-float{position:fixed;bottom:24px;right:24px;z-index:999;width:52px;height:52px;border-radius:50%;background:#25d366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;box-shadow:0 4px 16px rgba(0,0,0,.2);text-decoration:none;animation:bounce 2s ease-in-out infinite}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@media print{.no-print,nav,.wa-float{display:none!important}}
</style>
</head>
<body>

<nav class="navbar navbar-dark sticky-top no-print">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php">
      <i class="fas fa-mountain" style="color:#4caf63;font-size:1.2rem"></i>
      HIKE<span style="color:#4caf63">GEAR</span>
      <small style="font-size:.6rem;display:block;color:rgba(255,255,255,.6);letter-spacing:1.5px">Adventure Starts Here</small>
    </a>
    <a href="pesanan.php" class="btn btn-sm btn-outline-light"><i class="fas fa-box me-1"></i>Pesananku</a>
  </div>
</nav>

<div class="success-hero">
  <div class="container">
    <div class="checkmark-wrap"><i class="fas fa-check" style="color:#fff;font-size:2.2rem"></i></div>
    <h1 style="font-size:1.6rem;font-weight:900;color:#14532d;margin-bottom:6px">Pembayaran Berhasil! 🎉</h1>
    <p style="color:#166534;font-size:.88rem;margin-bottom:16px">Terima kasih <?=eg($user_data['nama_lengkap'] ?? 'Kamu')?>, pesananmu sedang diproses</p>
    <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill"
         style="background:rgba(255,255,255,.7);backdrop-filter:blur(6px);border:1.5px solid #86efac">
      <i class="fas fa-hashtag" style="color:var(--green)"></i>
      <span style="font-size:.85rem;font-weight:800;letter-spacing:.5px"><?=eg($pesanan['kode_pesanan'])?></span>
    </div>
  </div>
</div>

<div class="container py-4 pb-5">
  <div class="row g-4">
    <div class="col-lg-8">
      <!-- Status Tracker -->
      <div class="info-card">
        <div class="info-card-head">
          <i class="fas fa-route" style="color:var(--green)"></i>
          <h6>Status Pesanan</h6>
          <span class="ms-auto badge bg-<?=$sts[1]?>" style="font-size:.7rem"><?=$sts[0]?></span>
        </div>
        <div class="info-card-body">
          <div class="timeline">
            <?php
            $stepInfo = [
                ['Pesanan Dibuat','Pesanan ' . $pesanan['kode_pesanan'] . ' berhasil dibuat',true,date('d M Y, H:i',strtotime($pesanan['created_at']))],
                ['Pembayaran Dikonfirmasi','Pembayaran sebesar ' . rp((int)$pesanan['total']) . ' telah diterima',$pesanan['status_bayar']==='paid',$pesanan['dibayar_at']?date('d M Y, H:i',strtotime($pesanan['dibayar_at'])):'-'],
                ['Pesanan Diproses','Penjual sedang menyiapkan paketmu',in_array($pesanan['status'],['diproses','dikirim','selesai']),in_array($pesanan['status'],['diproses','dikirim','selesai'])?'Sedang diproses':'-'],
                ['Paket Dikirim','Paket dalam perjalanan ke ' . $pesanan['kota'],in_array($pesanan['status'],['dikirim','selesai']),in_array($pesanan['status'],['dikirim','selesai'])?'Dalam pengiriman':'-'],
                ['Pesanan Selesai','Paket telah diterima, terima kasih!',$pesanan['status']==='selesai',$pesanan['status']==='selesai'?'Selesai':'Est. ' . $tiba],
            ];
            foreach($stepInfo as $i=>$s):
                $isDone = $s[2];
                $doneBefore = array_slice($stepInfo,0,$i);
                $isActive = !$isDone && count(array_filter(array_column($doneBefore,2))) === $i;
            ?>
            <div class="tl-item" style="animation-delay:<?=$i*.08?>s">
              <div class="tl-dot <?=$isDone?'done':($isActive?'active':'')?>">
                <?=$isDone?'<i class="fas fa-check" style="font-size:.5rem"></i>':($i+1)?>
              </div>
              <div class="tl-title"><?=eg($s[0])?></div>
              <div class="tl-sub"><?=eg($s[1])?></div>
              <?php if($s[3] && $s[3]!=='-'): ?>
              <div style="font-size:.68rem;color:var(--green);font-weight:600;margin-top:2px">
                <i class="fas fa-clock me-1"></i><?=eg($s[3])?>
              </div>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Produk Dipesan -->
      <div class="info-card">
        <div class="info-card-head">
          <i class="fas fa-shopping-bag" style="color:var(--green)"></i>
          <h6>Produk Dipesan (<?=count($items)?> item)</h6>
        </div>
        <div class="info-card-body">
          <?php foreach($items as $it): ?>
          <div class="prod-item">
            <img class="prod-thumb" src="<?=prodImg($it['gambar_utama']??null)?>" alt="<?=eg($it['nama_produk'])?>"/>
            <div class="flex-fill">
              <div class="prod-name"><?=eg($it['nama_produk'])?></div>
              <?php if(!empty($it['variasi'])): ?><div class="prod-var"><i class="fas fa-tag me-1"></i><?=eg($it['variasi'])?></div><?php endif; ?>
              <div class="prod-var">Qty: <?=(int)$it['qty']?></div>
            </div>
            <div class="prod-price"><?=rp((int)$it['harga']*(int)$it['qty'])?></div>
          </div>
          <?php endforeach; ?>
          <div class="mt-3 pt-2">
            <div class="sum-row"><span>Subtotal</span><span><?=rp((int)$pesanan['subtotal'])?></span></div>
            <div class="sum-row"><span>Ongkos Kirim (<?=eg($pesanan['ekspedisi'])?>)</span><span><?=$pesanan['ongkir']>0?rp((int)$pesanan['ongkir']):'<span style="color:var(--green);font-weight:700">GRATIS</span>'?></span></div>
            <?php if(!empty($pesanan['diskon'])&&$pesanan['diskon']>0): ?>
            <div class="sum-row" style="color:#16a34a"><span>Diskon</span><span>-<?=rp((int)$pesanan['diskon'])?></span></div>
            <?php endif; ?>
            <div class="sum-row total"><span>Total Dibayar</span><span style="color:var(--green)"><?=rp((int)$pesanan['total'])?></span></div>
          </div>
        </div>
      </div>

      <!-- Info Pengiriman & Pembayaran -->
      <div class="row g-3">
        <div class="col-md-6">
          <div class="info-card">
            <div class="info-card-head"><i class="fas fa-map-marker-alt" style="color:var(--green)"></i><h6>Alamat Pengiriman</h6></div>
            <div class="info-card-body">
              <div class="detail-row"><span class="lbl"><i class="fas fa-user me-1"></i>Penerima</span><span class="val"><?=eg($pesanan['nama_penerima'])?></span></div>
              <div class="detail-row"><span class="lbl"><i class="fas fa-phone me-1"></i>HP</span><span class="val"><?=eg($pesanan['hp_penerima']??'-')?></span></div>
              <div class="detail-row"><span class="lbl"><i class="fas fa-home me-1"></i>Alamat</span><span class="val"><?=eg($pesanan['alamat_lengkap']??'-')?></span></div>
              <div class="detail-row"><span class="lbl"><i class="fas fa-city me-1"></i>Kota</span><span class="val"><?=eg($pesanan['kota'])?></span></div>
              <div class="detail-row"><span class="lbl"><i class="fas fa-map me-1"></i>Prov/KodePos</span><span class="val"><?=eg($pesanan['provinsi']??'-')?>, <?=eg($pesanan['kode_pos']??'-')?></span></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-card">
            <div class="info-card-head"><i class="fas fa-credit-card" style="color:var(--green)"></i><h6>Detail Pembayaran</h6></div>
            <div class="info-card-body">
              <div class="detail-row"><span class="lbl">Metode</span><span class="val"><?=eg($metodLabel[$pesanan['metode_bayar']]??$pesanan['metode_bayar'])?></span></div>
              <div class="detail-row"><span class="lbl">Status</span><span class="val"><span class="badge bg-<?=$pesanan['status_bayar']==='paid'?'success':'warning'?>"><?=$pesanan['status_bayar']==='paid'?'Lunas':'Menunggu'?></span></span></div>
              <?php if($pesanan['dibayar_at']): ?>
              <div class="detail-row"><span class="lbl">Dibayar</span><span class="val"><?=date('d M Y, H:i',strtotime($pesanan['dibayar_at']))?></span></div>
              <?php endif; ?>
              <div class="detail-row"><span class="lbl">Ekspedisi</span><span class="val"><?=eg($pesanan['ekspedisi'])?></span></div>
              <div class="detail-row"><span class="lbl">Estimasi Tiba</span><span class="val" style="color:var(--green);font-weight:700"><?=$tiba?></span></div>
              <?php if(!empty($pesanan['catatan'])): ?>
              <div class="detail-row"><span class="lbl">Catatan</span><span class="val"><?=eg($pesanan['catatan'])?></span></div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div style="position:sticky;top:80px">
        <div class="info-card mb-3">
          <div class="info-card-body d-flex flex-column gap-2">
            <a href="lacak_pesanan.php?kode=<?=eg($pesanan['kode_pesanan'])?>" class="btn-lacak">
              <i class="fas fa-search-location"></i> Lacak Pesanan
            </a>
            <a href="pesanan.php" class="btn-outline-green"><i class="fas fa-box-open"></i> Semua Pesananku</a>
            <a href="index.php" class="btn-outline-green" style="border-color:var(--gray-200);color:var(--gray-600)">
              <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
          </div>
        </div>
        <div class="info-card mb-3">
          <div class="info-card-head"><i class="fas fa-receipt" style="color:var(--green)"></i><h6>Ringkasan</h6></div>
          <div class="info-card-body">
            <div class="detail-row"><span class="lbl">Kode</span><span class="val"><?=eg($pesanan['kode_pesanan'])?></span></div>
            <div class="detail-row"><span class="lbl">Tanggal</span><span class="val"><?=date('d M Y',strtotime($pesanan['created_at']))?></span></div>
            <div class="detail-row"><span class="lbl">Total</span><span class="val" style="color:var(--green);font-size:.95rem"><?=rp((int)$pesanan['total'])?></span></div>
            <div class="detail-row"><span class="lbl">Status</span><span class="val"><span class="badge bg-<?=$sts[1]?>"><?=$sts[0]?></span></span></div>
          </div>
        </div>
        <div class="info-card text-center">
          <div class="info-card-body" style="padding:16px">
            <i class="fas fa-headset" style="color:var(--green);font-size:1.4rem;margin-bottom:8px"></i>
            <div style="font-size:.8rem;font-weight:700;margin-bottom:4px">Ada kendala?</div>
            <div style="font-size:.73rem;color:var(--gray-600);margin-bottom:12px">CS kami siap membantu 24/7</div>
            <a href="https://wa.me/628119876543?text=Halo+HikeGear,+saya+ingin+menanyakan+pesanan+<?=eg($pesanan['kode_pesanan'])?>" target="_blank"
               class="btn btn-sm w-100" style="background:#25d366;color:#fff;border-radius:8px;font-weight:600;font-size:.78rem;border:none;padding:10px">
              <i class="fab fa-whatsapp me-1"></i> WhatsApp CS
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<a href="https://wa.me/628119876543" target="_blank" class="wa-float no-print"><i class="fab fa-whatsapp"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
