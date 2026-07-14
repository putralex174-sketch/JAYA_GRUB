<?php
// buat-pesanan.php — HikeGear Halaman Buat Pesanan
session_start();

// ── Data keranjang simulasi (ganti dengan query MySQL) ──
$items = [
  ['id'=>1,'nama'=>'Eiger Mountaineer Jacket','variasi'=>'Hitam / XL','harga'=>1250000,'qty'=>1,'img'=>'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=200&q=80'],
  ['id'=>2,'nama'=>'Consina Tas Carrier 45L', 'variasi'=>'Merah',     'harga'=>895000, 'qty'=>1,'img'=>'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80'],
  ['id'=>3,'nama'=>'Eiger Hiking Sock',        'variasi'=>'Abu / L',   'harga'=>85000,  'qty'=>2,'img'=>'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=200&q=80'],
];

$subtotal   = array_sum(array_map(fn($i)=>$i['harga']*$i['qty'], $items));
$ongkir     = 0;       // dihitung setelah user pilih ekspedisi
$diskon     = 0;
$total      = $subtotal + $ongkir - $diskon;

function rupiah(int $n): string { return 'Rp'.number_format($n,0,',','.'); }

$ekspedisi = [
  ['kode'=>'jne_reg','nama'=>'JNE REG',     'logo'=>'JNE',    'estimasi'=>'2-3 Hari', 'ongkir'=>18000],
  ['kode'=>'jne_yes','nama'=>'JNE YES',     'logo'=>'JNE',    'estimasi'=>'1 Hari',   'ongkir'=>28000],
  ['kode'=>'jnt_reg','nama'=>'J&T Express', 'logo'=>'J&T',    'estimasi'=>'2-3 Hari', 'ongkir'=>15000],
  ['kode'=>'sicepat', 'nama'=>'SiCepat REG','logo'=>'SiCepat','estimasi'=>'2-3 Hari', 'ongkir'=>16000],
  ['kode'=>'anteraja','nama'=>'AnterAja',   'logo'=>'AnterAja','estimasi'=>'3-5 Hari','ongkir'=>12000],
];

$bayar = [
  ['kode'=>'transfer','label'=>'Transfer Bank','icon'=>'bank','list'=>['BCA','BNI','BRI','Mandiri']],
  ['kode'=>'ewallet', 'label'=>'E-Wallet',     'icon'=>'wallet','list'=>['GoPay','OVO','DANA','ShopeePay']],
  ['kode'=>'cod',     'label'=>'Bayar di Tempat (COD)','icon'=>'cash','list'=>[]],
  ['kode'=>'cc',      'label'=>'Kartu Kredit / Debit',  'icon'=>'card','list'=>['Visa','Mastercard','JCB']],
  ['kode'=>'qris',    'label'=>'QRIS','icon'=>'qr','list'=>[]],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Buat Pesanan – HikeGear</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --g8:#1a5c2a;--g7:#2d7a3a;--g6:#3d9e4e;--g5:#4caf63;--g1:#e8f5ea;--g0:#f0faf1;
  --w:#fff;--s0:#f9fafb;--s1:#f3f4f6;--s2:#e5e7eb;--s3:#d1d5db;
  --s4:#9ca3af;--s5:#6b7280;--s6:#4b5563;--s8:#1f2937;--s9:#111827;
  --red:#ef4444;--red0:#fef2f2;--gold:#f59e0b;
  --sh0:0 1px 3px rgba(0,0,0,.07);--sh1:0 4px 12px rgba(0,0,0,.10);--sh2:0 8px 24px rgba(0,0,0,.13);
  --r:10px;--rl:14px;
}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;font-size:14px;color:var(--s8);background:var(--s0)}
a{text-decoration:none;color:inherit}img{max-width:100%;display:block}
ul{list-style:none}button{cursor:pointer;font-family:inherit;border:none}
input,select,textarea{font-family:inherit}

/* ── ANNOUNCEMENT ─────────────────────────────── */
.ann{background:var(--g8);color:#fff;font-size:.75rem;font-weight:500;
  padding:7px 32px;display:flex;align-items:center;justify-content:space-between}
.ann-left{display:flex;align-items:center;gap:6px}
.ann-right{display:flex;gap:20px}
.ann-right a{color:rgba(255,255,255,.82);display:flex;align-items:center;gap:5px}

/* ── NAVBAR ──────────────────────────────────── */
.navbar{background:var(--w);border-bottom:1px solid var(--s2);
  box-shadow:var(--sh0);position:sticky;top:0;z-index:200}
.nb{max-width:1340px;margin:0 auto;padding:11px 24px;
  display:flex;align-items:center;gap:18px}
.nb-logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
.nb-logo svg{width:40px;height:40px}
.nb-brand b{display:block;font-size:1.05rem;font-weight:800}
.nb-brand b span{color:var(--g7)}
.nb-brand small{font-size:.57rem;letter-spacing:2px;text-transform:uppercase;color:var(--s4)}
.nb-title{font-size:.9rem;font-weight:700;color:var(--s6);margin-left:4px;
  padding-left:18px;border-left:1px solid var(--s2)}
.nb-secure{margin-left:auto;display:flex;align-items:center;gap:6px;
  font-size:.75rem;color:var(--g7);font-weight:600}
.nb-secure svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2}

/* ── PROGRESS STEPS ──────────────────────────── */
.steps-wrap{background:var(--w);border-bottom:1px solid var(--s2);padding:16px 0}
.steps{max-width:1340px;margin:0 auto;padding:0 24px;
  display:flex;align-items:center;justify-content:center;gap:0}
.step{display:flex;align-items:center;gap:10px}
.step-num{width:30px;height:30px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:.8rem;font-weight:700;flex-shrink:0;transition:all .2s}
.step-lbl{font-size:.8rem;font-weight:600;white-space:nowrap}
.step.done .step-num{background:var(--g7);color:#fff}
.step.done .step-lbl{color:var(--g7)}
.step.active .step-num{background:var(--g7);color:#fff;box-shadow:0 0 0 4px rgba(45,122,58,.2)}
.step.active .step-lbl{color:var(--g7)}
.step.idle .step-num{background:var(--s1);color:var(--s4)}
.step.idle .step-lbl{color:var(--s4)}
.step-line{width:60px;height:2px;background:var(--s2);margin:0 6px;flex-shrink:0}
.step-line.done{background:var(--g7)}

/* ── BREADCRUMB ──────────────────────────────── */
.breadcrumb{max-width:1340px;margin:0 auto;padding:14px 24px;
  display:flex;align-items:center;gap:6px;font-size:.75rem;color:var(--s5)}
.breadcrumb a{color:var(--s5)}
.breadcrumb a:hover{color:var(--g7)}
.breadcrumb svg{width:11px;height:11px;stroke:var(--s4);fill:none;stroke-width:2}

/* ── PAGE LAYOUT ─────────────────────────────── */
.page{max-width:1340px;margin:0 auto;padding:0 24px 48px;
  display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start}

/* ── SECTION CARD ────────────────────────────── */
.card{background:var(--w);border:1.5px solid var(--s2);border-radius:var(--rl);
  overflow:hidden;margin-bottom:16px}
.card-head{padding:16px 20px;border-bottom:1px solid var(--s2);
  display:flex;align-items:center;gap:10px}
.card-head-num{width:26px;height:26px;border-radius:50%;background:var(--g7);color:#fff;
  font-size:.75rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.card-head-title{font-size:.95rem;font-weight:700;color:var(--s9)}
.card-head-sub{font-size:.75rem;color:var(--s5);margin-left:auto}
.card-body{padding:20px}

/* ── FORM ELEMENTS ───────────────────────────── */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.form-row.full{grid-template-columns:1fr}
.form-row.tri{grid-template-columns:1fr 1fr 1fr}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-group label{font-size:.78rem;font-weight:600;color:var(--s6)}
.form-group label span{color:var(--red)}
.form-input,.form-select,.form-textarea{
  padding:11px 14px;border:1.5px solid var(--s2);border-radius:var(--r);
  font-size:.875rem;color:var(--s8);background:var(--w);outline:none;
  transition:border-color .18s,box-shadow .18s;width:100%}
.form-input:focus,.form-select:focus,.form-textarea:focus{
  border-color:var(--g6);box-shadow:0 0 0 3px rgba(61,158,78,.12)}
.form-input.err{border-color:var(--red)}
.form-err{font-size:.7rem;color:var(--red);display:none}
.form-err.show{display:block}
.form-textarea{resize:vertical;min-height:80px}

/* Checkbox custom */
.chk-label{display:flex;align-items:flex-start;gap:9px;cursor:pointer;font-size:.82rem;color:var(--s6)}
.chk-label input{display:none}
.chk-box{width:18px;height:18px;border:2px solid var(--s3);border-radius:5px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;transition:background .15s,border-color .15s;margin-top:1px}
.chk-label input:checked ~ .chk-box{background:var(--g7);border-color:var(--g7)}
.chk-label input:checked ~ .chk-box::after{content:'';display:block;
  width:5px;height:9px;border:2px solid #fff;border-top:none;border-left:none;
  transform:rotate(43deg) translateY(-1px)}

/* Radio card */
.radio-group{display:flex;flex-direction:column;gap:10px}
.radio-card{display:flex;align-items:center;gap:14px;padding:13px 16px;
  border:1.5px solid var(--s2);border-radius:var(--r);cursor:pointer;
  transition:border-color .18s,background .18s}
.radio-card:hover{border-color:var(--g5);background:var(--g0)}
.radio-card.selected{border-color:var(--g7);background:var(--g0)}
.radio-dot{width:18px;height:18px;border-radius:50%;border:2px solid var(--s3);
  flex-shrink:0;display:flex;align-items:center;justify-content:center;
  transition:border-color .15s}
.radio-card.selected .radio-dot{border-color:var(--g7)}
.radio-card.selected .radio-dot::after{content:'';width:8px;height:8px;
  border-radius:50%;background:var(--g7);display:block}
.radio-info{flex:1}
.radio-name{font-size:.85rem;font-weight:600;color:var(--s8)}
.radio-sub{font-size:.72rem;color:var(--s5);margin-top:2px}
.radio-price{font-size:.85rem;font-weight:700;color:var(--g7);margin-left:auto}
.radio-badge{font-size:.67rem;font-weight:700;padding:2px 8px;border-radius:4px;
  background:var(--g1);color:var(--g7);margin-left:8px}

/* Ekspedisi logo pill */
.eksp-logo{font-size:.65rem;font-weight:800;padding:3px 8px;border-radius:5px;
  background:var(--s1);color:var(--s6);flex-shrink:0;min-width:52px;text-align:center}

/* Metode bayar icon */
.pay-icon{width:36px;height:36px;border-radius:8px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  background:var(--s1);color:var(--s5)}
.pay-icon svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:1.8}

/* Voucher */
.voucher-row{display:flex;gap:10px}
.voucher-row input{flex:1}
.btn-apply{padding:11px 20px;background:var(--g7);color:#fff;
  border-radius:var(--r);font-size:.83rem;font-weight:700;
  transition:background .18s;white-space:nowrap}
.btn-apply:hover{background:var(--g8)}

/* Catatan */
.catatan-toggle{display:flex;align-items:center;gap:7px;
  font-size:.8rem;font-weight:600;color:var(--g7);cursor:pointer;
  background:none;border:none;padding:0;margin-bottom:10px}
.catatan-toggle svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5}

/* ── PRODUK LIST ─────────────────────────────── */
.prod-list{display:flex;flex-direction:column;gap:0}
.prod-item{display:flex;align-items:center;gap:14px;padding:14px 0;
  border-bottom:1px solid var(--s2)}
.prod-item:last-child{border-bottom:none}
.prod-img{width:68px;height:68px;border-radius:9px;overflow:hidden;
  background:var(--s1);flex-shrink:0}
.prod-img img{width:100%;height:100%;object-fit:cover}
.prod-info{flex:1}
.prod-nama{font-size:.83rem;font-weight:600;color:var(--s8);margin-bottom:3px}
.prod-variasi{font-size:.72rem;color:var(--s5);margin-bottom:4px}
.prod-qty{font-size:.72rem;color:var(--s5)}
.prod-harga{font-size:.88rem;font-weight:700;color:var(--s9);text-align:right;flex-shrink:0}

/* ── ORDER SUMMARY (sticky) ──────────────────── */
.summary{position:sticky;top:86px}
.summary-card{background:var(--w);border:1.5px solid var(--s2);border-radius:var(--rl);overflow:hidden}
.summary-head{background:var(--g7);color:#fff;padding:15px 20px;font-size:.9rem;font-weight:700}
.summary-body{padding:20px}
.sum-row{display:flex;align-items:center;justify-content:space-between;
  font-size:.82rem;margin-bottom:11px;color:var(--s6)}
.sum-row:last-of-type{margin-bottom:0}
.sum-row.total{font-size:1rem;font-weight:800;color:var(--s9);
  padding-top:14px;border-top:1.5px solid var(--s2);margin-top:14px}
.sum-row .green{color:var(--g7)}
.sum-row b{font-weight:700;color:var(--s8)}
.sum-divider{height:1px;background:var(--s2);margin:14px 0}

/* Promo notice */
.promo-notice{background:var(--g0);border:1px solid #c3e8c9;border-radius:8px;
  padding:10px 14px;display:flex;align-items:center;gap:9px;
  font-size:.76rem;color:var(--g7);font-weight:600;margin-bottom:16px}
.promo-notice svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2;flex-shrink:0}

/* Submit button */
.btn-order{width:100%;padding:15px;background:var(--g7);color:#fff;
  border-radius:var(--r);font-size:.95rem;font-weight:700;
  display:flex;align-items:center;justify-content:center;gap:9px;
  transition:background .18s,transform .12s,box-shadow .18s;
  box-shadow:0 4px 14px rgba(45,122,58,.35)}
.btn-order:hover{background:var(--g8);transform:translateY(-1px);box-shadow:0 6px 20px rgba(45,122,58,.45)}
.btn-order:active{transform:translateY(0)}
.btn-order svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2.5}

.secure-note{display:flex;align-items:center;justify-content:center;gap:6px;
  margin-top:12px;font-size:.72rem;color:var(--s4)}
.secure-note svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2}

/* ── LOADING OVERLAY ─────────────────────────── */
#overlay{position:fixed;inset:0;z-index:9999;
  background:rgba(255,255,255,.7);backdrop-filter:blur(3px);
  display:none;align-items:center;justify-content:center;flex-direction:column;gap:14px}
#overlay.show{display:flex}
.spinner{width:44px;height:44px;border:4px solid var(--s2);
  border-top-color:var(--g7);border-radius:50%;animation:spin .7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.overlay-txt{font-size:.85rem;font-weight:600;color:var(--g7)}

/* ── TOAST ───────────────────────────────────── */
#toast{position:fixed;bottom:26px;right:26px;z-index:9998;
  background:var(--g8);color:#fff;padding:13px 20px;border-radius:var(--r);
  font-size:.82rem;font-weight:600;box-shadow:var(--sh2);
  display:flex;align-items:center;gap:8px;
  transform:translateY(80px);opacity:0;
  transition:transform .3s cubic-bezier(.34,1.56,.64,1),opacity .25s}
#toast.show{transform:translateY(0);opacity:1}
#toast svg{width:15px;height:15px;stroke:#fff;fill:none;stroke-width:2.5}

/* ── RESPONSIVE ──────────────────────────────── */
@media(max-width:1050px){
  .page{grid-template-columns:1fr}
  .summary{position:static}
}
@media(max-width:640px){
  .ann-right,.step-lbl{display:none}
  .form-row{grid-template-columns:1fr}
  .form-row.tri{grid-template-columns:1fr 1fr}
  .page{padding:0 14px 40px}
}
</style>
</head>
<body>

<div id="overlay">
  <div class="spinner"></div>
  <div class="overlay-txt">Memproses pesanan Anda...</div>
</div>
<div id="toast"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg><span id="toastTxt"></span></div>

<!-- ── ANNOUNCEMENT ───────────────────────────────── -->
<div class="ann">
  <div class="ann-left">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Gratis Ongkir untuk semua pesanan di Indonesia
  </div>
  <div class="ann-right">
    <a href="#"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Lacak Pesanan</a>
    <span style="color:rgba(255,255,255,.25)">|</span>
    <a href="#"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Kontak Kami</a>
  </div>
</div>

<!-- ── NAVBAR ─────────────────────────────────────── -->
<header class="navbar">
  <div class="nb">
    <a href="beranda.php" class="nb-logo">
      <svg viewBox="0 0 44 44" fill="none"><rect width="44" height="44" rx="9" fill="#1a5c2a"/><path d="M7 34L18 13l7 10 4-7 8 18Z" fill="#4caf63"/></svg>
      <div class="nb-brand"><b>HIKE<span>GEAR</span></b><small>Adventure Starts Here</small></div>
    </a>
    <span class="nb-title">Buat Pesanan</span>
    <div class="nb-secure">
      <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Transaksi Aman & Terenkripsi
    </div>
  </div>
</header>

<!-- ── PROGRESS STEPS ────────────────────────────── -->
<div class="steps-wrap">
  <div class="steps">
    <div class="step done">
      <div class="step-num">✓</div>
      <div class="step-lbl">Keranjang</div>
    </div>
    <div class="step-line done"></div>
    <div class="step active">
      <div class="step-num">2</div>
      <div class="step-lbl">Buat Pesanan</div>
    </div>
    <div class="step-line"></div>
    <div class="step idle">
      <div class="step-num">3</div>
      <div class="step-lbl">Pembayaran</div>
    </div>
    <div class="step-line"></div>
    <div class="step idle">
      <div class="step-num">4</div>
      <div class="step-lbl">Selesai</div>
    </div>
  </div>
</div>

<!-- ── BREADCRUMB ─────────────────────────────────── -->
<div class="breadcrumb">
  <a href="beranda.php">Beranda</a>
  <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
  <a href="keranjang.php">Keranjang</a>
  <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
  <span>Buat Pesanan</span>
</div>

<!-- ── MAIN ──────────────────────────────────────── -->
<form id="orderForm" method="POST" action="proses-pesanan.php" novalidate>
<div class="page">

  <!-- ════════ LEFT COLUMN ════════ -->
  <div class="left-col">

    <!-- 1. Alamat Pengiriman -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-num">1</div>
        <div class="card-head-title">Alamat Pengiriman</div>
        <button type="button" class="card-head-sub" onclick="pilihAlamat()" style="background:none;border:none;color:var(--g7);font-size:.78rem;font-weight:600;cursor:pointer">
          + Pilih Alamat Tersimpan
        </button>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group">
            <label>Nama Penerima <span>*</span></label>
            <input class="form-input" type="text" name="nama_penerima" id="nama_penerima"
              placeholder="Nama lengkap penerima" value="<?= $_SESSION['user_name'] ?? '' ?>"/>
            <div class="form-err" id="err-nama">Nama wajib diisi</div>
          </div>
          <div class="form-group">
            <label>Nomor HP <span>*</span></label>
            <input class="form-input" type="tel" name="hp" id="hp"
              placeholder="08xxxxxxxxxx"/>
            <div class="form-err" id="err-hp">Nomor HP wajib diisi</div>
          </div>
        </div>

        <div class="form-row full">
          <div class="form-group">
            <label>Alamat Lengkap <span>*</span></label>
            <textarea class="form-textarea" name="alamat" id="alamat"
              placeholder="Nama jalan, nomor rumah, RT/RW, kompleks, dll."></textarea>
            <div class="form-err" id="err-alamat">Alamat wajib diisi</div>
          </div>
        </div>

        <div class="form-row tri">
          <div class="form-group">
            <label>Provinsi <span>*</span></label>
            <select class="form-select" name="provinsi" id="provinsi" onchange="loadKota(this.value)">
              <option value="">Pilih Provinsi</option>
              <option value="jatim">Jawa Timur</option>
              <option value="jateng">Jawa Tengah</option>
              <option value="jabar">Jawa Barat</option>
              <option value="dki">DKI Jakarta</option>
              <option value="bali">Bali</option>
              <option value="ntb">Nusa Tenggara Barat</option>
              <option value="sulsel">Sulawesi Selatan</option>
            </select>
            <div class="form-err" id="err-provinsi">Provinsi wajib dipilih</div>
          </div>
          <div class="form-group">
            <label>Kota / Kabupaten <span>*</span></label>
            <select class="form-select" name="kota" id="kota">
              <option value="">Pilih Kota</option>
            </select>
            <div class="form-err" id="err-kota">Kota wajib dipilih</div>
          </div>
          <div class="form-group">
            <label>Kecamatan <span>*</span></label>
            <input class="form-input" type="text" name="kecamatan" id="kecamatan"
              placeholder="Nama kecamatan"/>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Kelurahan / Desa</label>
            <input class="form-input" type="text" name="kelurahan" placeholder="Nama kelurahan"/>
          </div>
          <div class="form-group">
            <label>Kode Pos <span>*</span></label>
            <input class="form-input" type="text" name="kodepos" id="kodepos"
              placeholder="5 digit kode pos" maxlength="5"/>
            <div class="form-err" id="err-kodepos">Kode pos wajib diisi</div>
          </div>
        </div>

        <label class="chk-label" style="margin-top:4px">
          <input type="checkbox" name="simpan_alamat" value="1" checked/>
          <span class="chk-box"></span>
          Simpan alamat ini untuk pesanan berikutnya
        </label>
      </div>
    </div>

    <!-- 2. Ekspedisi & Pengiriman -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-num">2</div>
        <div class="card-head-title">Ekspedisi &amp; Pengiriman</div>
      </div>
      <div class="card-body">
        <div class="radio-group" id="ekspedisiGroup">
          <?php foreach($ekspedisi as $e): ?>
          <label class="radio-card" onclick="pilihEkspedisi(this, <?=$e['ongkir']?>, '<?=$e['nama']?>')">
            <div class="radio-dot"></div>
            <div class="eksp-logo"><?=$e['logo']?></div>
            <div class="radio-info">
              <div class="radio-name"><?=$e['nama']?> <span class="radio-badge">~<?=$e['estimasi']?></span></div>
              <div class="radio-sub">Estimasi tiba <?=$e['estimasi']?></div>
            </div>
            <div class="radio-price"><?=rupiah($e['ongkir'])?></div>
            <input type="radio" name="ekspedisi" value="<?=$e['kode']?>" style="display:none"/>
          </label>
          <?php endforeach; ?>
        </div>

        <div id="noEkspedisi" style="display:none;text-align:center;padding:20px;color:var(--s4);font-size:.82rem">
          Pilih alamat terlebih dahulu untuk melihat opsi pengiriman.
        </div>
      </div>
    </div>

    <!-- 3. Metode Pembayaran -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-num">3</div>
        <div class="card-head-title">Metode Pembayaran</div>
      </div>
      <div class="card-body">
        <div class="radio-group">
          <?php
          $icons = [
            'bank'   => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
            'wallet' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
            'cash'   => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>',
            'card'   => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
            'qr'     => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 17h.01"/>',
          ];
          foreach($bayar as $b): ?>
          <label class="radio-card" onclick="pilihBayar(this)">
            <div class="radio-dot"></div>
            <div class="pay-icon">
              <svg viewBox="0 0 24 24"><?=$icons[$b['icon']]?></svg>
            </div>
            <div class="radio-info">
              <div class="radio-name"><?=htmlspecialchars($b['label'])?></div>
              <?php if(!empty($b['list'])): ?>
              <div class="radio-sub"><?=implode(' · ',$b['list'])?></div>
              <?php endif; ?>
            </div>
            <input type="radio" name="metode_bayar" value="<?=$b['kode']?>" style="display:none"/>
          </label>
          <?php endforeach; ?>
        </div>
        <div class="form-err" id="err-bayar" style="margin-top:8px">Pilih metode pembayaran</div>
      </div>
    </div>

    <!-- 4. Kode Voucher -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-num">4</div>
        <div class="card-head-title">Kode Voucher / Promo</div>
      </div>
      <div class="card-body">
        <div class="voucher-row">
          <input class="form-input" type="text" id="kodeVoucher" name="voucher"
            placeholder="Masukkan kode voucher" style="text-transform:uppercase"/>
          <button type="button" class="btn-apply" onclick="applyVoucher()">Terapkan</button>
        </div>
        <div id="voucherMsg" style="font-size:.75rem;margin-top:8px;display:none"></div>
        <div style="margin-top:12px;display:flex;flex-wrap:wrap;gap:8px" id="voucherList">
          <?php
          $vouchers = ['HIKE10'=>'Diskon 10%','ONGKIR0'=>'Gratis Ongkir','NEWMEMBER'=>'Diskon Rp50.000'];
          foreach($vouchers as $kode=>$label): ?>
          <button type="button" onclick="pilihVoucher('<?=$kode?>')"
            style="padding:5px 12px;border:1.5px dashed var(--g5);border-radius:6px;
                   font-size:.72rem;font-weight:700;color:var(--g7);background:var(--g0);cursor:pointer">
            <?=$kode?> — <?=$label?>
          </button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- 5. Catatan Pesanan -->
    <div class="card">
      <div class="card-head">
        <div class="card-head-num">5</div>
        <div class="card-head-title">Catatan Pesanan</div>
        <span class="card-head-sub">Opsional</span>
      </div>
      <div class="card-body">
        <textarea class="form-textarea" name="catatan" id="catatan"
          placeholder="Contoh: tolong dibungkus rapi, hadiah ulang tahun..."
          style="min-height:70px"></textarea>
        <div style="font-size:.7rem;color:var(--s4);margin-top:5px">
          Catatan akan diteruskan ke penjual.
        </div>
      </div>
    </div>

  </div><!-- /.left-col -->

  <!-- ════════ RIGHT COLUMN — Summary ════════ -->
  <div class="summary">

    <!-- Ringkasan Produk -->
    <div class="summary-card" style="margin-bottom:16px">
      <div class="card-head" style="padding:15px 20px;border-bottom:1px solid var(--s2);display:flex;align-items:center;gap:10px">
        <svg width="16" height="16" fill="none" stroke="var(--g7)" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span style="font-size:.9rem;font-weight:700;color:var(--s9)">Produk Dipesan (<?=count($items)?>)</span>
        <a href="keranjang.php" style="margin-left:auto;font-size:.75rem;color:var(--g7);font-weight:600">Edit</a>
      </div>
      <div style="padding:14px 20px">
        <div class="prod-list">
          <?php foreach($items as $item): ?>
          <div class="prod-item">
            <div class="prod-img">
              <img src="<?=$item['img']?>" alt="<?=htmlspecialchars($item['nama'])?>"/>
            </div>
            <div class="prod-info">
              <div class="prod-nama"><?=htmlspecialchars($item['nama'])?></div>
              <div class="prod-variasi"><?=$item['variasi']?></div>
              <div class="prod-qty">Qty: <?=$item['qty']?></div>
            </div>
            <div class="prod-harga"><?=rupiah($item['harga']*$item['qty'])?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Ringkasan Biaya -->
    <div class="summary-card">
      <div class="summary-head">Ringkasan Pembayaran</div>
      <div class="summary-body">

        <div class="promo-notice">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          Hemat Rp<?=number_format($diskon,0,',','.')?> dari promo hari ini!
        </div>

        <div class="sum-row">
          <span>Subtotal (<?=count($items)?> produk)</span>
          <b><?=rupiah($subtotal)?></b>
        </div>
        <div class="sum-row">
          <span>Ongkos Kirim</span>
          <b id="ongkirVal">— pilih ekspedisi</b>
        </div>
        <div class="sum-row" id="diskonRow" style="display:none">
          <span>Diskon Voucher</span>
          <b class="green" id="diskonVal">-Rp0</b>
        </div>
        <div class="sum-row" id="gratiRow" style="display:none">
          <span>Gratis Ongkir</span>
          <b class="green" id="gratiVal">-Rp0</b>
        </div>

        <div class="sum-row total">
          <span>Total Pembayaran</span>
          <span id="totalVal"><?=rupiah($subtotal)?></span>
        </div>

        <button type="submit" class="btn-order" id="btnOrder" onclick="submitOrder(event)">
          <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Buat Pesanan Sekarang
        </button>

        <div class="secure-note">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          Transaksi dilindungi enkripsi SSL 256-bit
        </div>

        <div style="text-align:center;margin-top:14px;font-size:.73rem;color:var(--s4);line-height:1.6">
          Dengan menekan tombol di atas, kamu menyetujui<br/>
          <a href="syarat.php" style="color:var(--g7)">Syarat & Ketentuan</a> serta
          <a href="privasi.php" style="color:var(--g7)">Kebijakan Privasi</a> HikeGear.
        </div>
      </div>
    </div>

  </div><!-- /.summary -->
</div>
</form>

<script>
/* ────────────────────────────────────────────
   DATA
──────────────────────────────────────────── */
const SUBTOTAL = <?=$subtotal?>;
let ongkir = 0, diskon = 0, gratiOngkir = false;

const kotaData = {
  jatim  :['Surabaya','Malang','Sidoarjo','Gresik','Mojokerto','Pasuruan','Batu'],
  jateng :['Semarang','Solo','Yogyakarta','Magelang','Purwokerto','Tegal'],
  jabar  :['Bandung','Bekasi','Depok','Bogor','Cimahi','Tasikmalaya'],
  dki    :['Jakarta Pusat','Jakarta Selatan','Jakarta Utara','Jakarta Barat','Jakarta Timur'],
  bali   :['Denpasar','Badung','Gianyar','Tabanan','Buleleng'],
  ntb    :['Mataram','Lombok Barat','Lombok Timur','Sumbawa'],
  sulsel :['Makassar','Gowa','Maros','Bone'],
};

/* ────────────────────────────────────────────
   LOAD KOTA
──────────────────────────────────────────── */
function loadKota(prov){
  const sel = document.getElementById('kota');
  sel.innerHTML = '<option value="">Pilih Kota</option>';
  if(!prov || !kotaData[prov]) return;
  kotaData[prov].forEach(k=>{
    const o=document.createElement('option');
    o.value=k.toLowerCase().replace(/ /g,'-');
    o.textContent=k;
    sel.appendChild(o);
  });
  updateTotal();
}

/* ────────────────────────────────────────────
   EKSPEDISI
──────────────────────────────────────────── */
function pilihEkspedisi(el, harga, nama){
  document.querySelectorAll('#ekspedisiGroup .radio-card').forEach(c=>c.classList.remove('selected'));
  el.classList.add('selected');
  el.querySelector('input[type=radio]').checked = true;
  ongkir = gratiOngkir ? 0 : harga;
  document.getElementById('ongkirVal').textContent =
    gratiOngkir ? 'GRATIS' : 'Rp'+harga.toLocaleString('id-ID');
  updateTotal();
  toast('✔ Ekspedisi '+nama+' dipilih');
}

/* ────────────────────────────────────────────
   METODE BAYAR
──────────────────────────────────────────── */
function pilihBayar(el){
  document.querySelectorAll('.radio-group .radio-card').forEach(c=>{
    if(c.querySelector('input[name=metode_bayar]')) c.classList.remove('selected');
  });
  el.classList.add('selected');
  el.querySelector('input[type=radio]').checked = true;
  document.getElementById('err-bayar').classList.remove('show');
}

/* ────────────────────────────────────────────
   VOUCHER
──────────────────────────────────────────── */
const VOUCHERS = {
  'HIKE10'    : {tipe:'persen', nilai:10,    label:'Diskon 10%'},
  'ONGKIR0'   : {tipe:'ongkir',nilai:0,     label:'Gratis Ongkir'},
  'NEWMEMBER' : {tipe:'nominal',nilai:50000, label:'Diskon Rp50.000'},
};

function pilihVoucher(kode){
  document.getElementById('kodeVoucher').value = kode;
  applyVoucher();
}

function applyVoucher(){
  const kode = document.getElementById('kodeVoucher').value.trim().toUpperCase();
  const msg  = document.getElementById('voucherMsg');
  msg.style.display = 'block';

  if(!kode){ msg.textContent='Masukkan kode voucher terlebih dahulu.'; msg.style.color='var(--red)'; return; }

  const v = VOUCHERS[kode];
  if(!v){ msg.textContent='Kode voucher tidak valid atau sudah kadaluarsa.'; msg.style.color='var(--red)'; return; }

  gratiOngkir = false; diskon = 0;

  if(v.tipe==='persen'){
    diskon = Math.round(SUBTOTAL * v.nilai / 100);
    showDiskon('diskonRow','diskonVal','-Rp'+diskon.toLocaleString('id-ID'));
  } else if(v.tipe==='nominal'){
    diskon = v.nilai;
    showDiskon('diskonRow','diskonVal','-Rp'+diskon.toLocaleString('id-ID'));
  } else if(v.tipe==='ongkir'){
    gratiOngkir = true;
    ongkir = 0;
    document.getElementById('ongkirVal').textContent = 'GRATIS';
    showDiskon('gratiRow','gratiVal','GRATIS');
    document.getElementById('diskonRow').style.display = 'none';
  }

  msg.textContent = '✔ Voucher '+v.label+' berhasil diterapkan!';
  msg.style.color = 'var(--g7)';
  updateTotal();
}

function showDiskon(rowId, valId, txt){
  document.getElementById(rowId).style.display = 'flex';
  document.getElementById(valId).textContent = txt;
}

/* ────────────────────────────────────────────
   UPDATE TOTAL
──────────────────────────────────────────── */
function updateTotal(){
  const total = SUBTOTAL + ongkir - diskon;
  document.getElementById('totalVal').textContent = 'Rp'+Math.max(0,total).toLocaleString('id-ID');
}

/* ────────────────────────────────────────────
   PILIH ALAMAT TERSIMPAN (modal sederhana)
──────────────────────────────────────────── */
function pilihAlamat(){
  toast('📍 Fitur alamat tersimpan segera hadir!');
}

/* ────────────────────────────────────────────
   SUBMIT VALIDASI
──────────────────────────────────────────── */
function submitOrder(e){
  e.preventDefault();
  let valid = true;

  const required = [
    ['nama_penerima','err-nama'],
    ['hp','err-hp'],
    ['alamat','err-alamat'],
    ['provinsi','err-provinsi'],
    ['kota','err-kota'],
    ['kodepos','err-kodepos'],
  ];

  required.forEach(([id, errId])=>{
    const el  = document.getElementById(id);
    const err = document.getElementById(errId);
    if(!el||!err) return;
    if(!el.value.trim()){
      el.classList.add('err');
      err.classList.add('show');
      valid = false;
    } else {
      el.classList.remove('err');
      err.classList.remove('show');
    }
  });

  const bayar = document.querySelector('input[name=metode_bayar]:checked');
  if(!bayar){
    document.getElementById('err-bayar').classList.add('show');
    valid = false;
  }

  const eksp = document.querySelector('input[name=ekspedisi]:checked');
  if(!eksp){
    toast('⚠ Pilih ekspedisi pengiriman terlebih dahulu!');
    valid = false;
  }

  if(!valid){
    toast('⚠ Lengkapi semua data yang diperlukan!');
    const firstErr = document.querySelector('.form-input.err, .form-select.err');
    if(firstErr) firstErr.scrollIntoView({behavior:'smooth',block:'center'});
    return;
  }

  // Show loading & submit
  document.getElementById('overlay').classList.add('show');
  document.getElementById('btnOrder').disabled = true;

  // Simulasi proses — ganti dengan form submit nyata
  setTimeout(()=>{
    // document.getElementById('orderForm').submit();
    window.location.href = 'konfirmasi-pesanan.php';
  }, 2000);
}

/* ────────────────────────────────────────────
   TOAST
──────────────────────────────────────────── */
let tTimer;
function toast(msg){
  const el=document.getElementById('toast');
  document.getElementById('toastTxt').textContent=msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer=setTimeout(()=>el.classList.remove('show'),3000);
}

/* Live input clear error */
document.querySelectorAll('.form-input, .form-select').forEach(el=>{
  el.addEventListener('input',()=>{
    el.classList.remove('err');
    const errEl=document.getElementById('err-'+el.id);
    if(errEl) errEl.classList.remove('show');
  });
});
</script>
</body>
</html>