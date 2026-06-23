<?php
session_start();

// require_once __DIR__.'/config/db.php';
// require_once __DIR__.'/config/helpers.php';
// require_once __DIR__.'/config/model_keranjang.php';
// require_once __DIR__.'/config/model_pesanan.php';

// ── Data dummy keranjang ─────────────────────────────────────────────────────
$cart_items = [
  ["id"=>1,"produk_id"=>1,"nama"=>"Eiger Mountaineer Jacket","brand"=>"Eiger",
   "harga"=>1250000,"qty"=>1,"foto"=>"https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=150&q=80","varian"=>"Hitam / L","berat"=>800],
  ["id"=>2,"produk_id"=>5,"nama"=>"Consina Carrier 60L","brand"=>"Consina",
   "harga"=>1275000,"qty"=>1,"foto"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=150&q=80","varian"=>"Hijau Army","berat"=>1200],
  ["id"=>3,"produk_id"=>7,"nama"=>"Rei Sleeping Bag Polar","brand"=>"Rei",
   "harga"=>650000,"qty"=>2,"foto"=>"https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=150&q=80","varian"=>"Biru Navy","berat"=>900],
];

$subtotal    = array_sum(array_map(fn($i)=>$i['harga']*$i['qty'], $cart_items));
$total_item  = array_sum(array_column($cart_items,'qty'));
$total_berat = array_sum(array_map(fn($i)=>$i['berat']*$i['qty'], $cart_items));
$ongkir      = 15000;
$diskon      = 0;
$total       = $subtotal - $diskon + $ongkir;

function fmt($n){ return 'Rp'.number_format($n,0,',','.'); }

// ── Kurir dummy ───────────────────────────────────────────────────────────────
$kurir_list = [
  ["kode"=>"jne",    "nama"=>"JNE",       "layanan"=>"REG",      "estimasi"=>"2-3 hari",  "harga"=>15000],
  ["kode"=>"jne2",   "nama"=>"JNE",       "layanan"=>"YES",      "estimasi"=>"1-2 hari",  "harga"=>25000],
  ["kode"=>"jnt",    "nama"=>"J&T",       "layanan"=>"Express",  "estimasi"=>"2-3 hari",  "harga"=>12000],
  ["kode"=>"sicepat","nama"=>"SiCepat",   "layanan"=>"HALU",     "estimasi"=>"1-2 hari",  "harga"=>18000],
  ["kode"=>"gosend", "nama"=>"GoSend",    "layanan"=>"Same Day",  "estimasi"=>"Hari ini",  "harga"=>35000],
  ["kode"=>"free",   "nama"=>"Gratis",    "layanan"=>"Hemat",    "estimasi"=>"3-5 hari",  "harga"=>0],
];

// ── Metode bayar ─────────────────────────────────────────────────────────────
$bayar_list = [
  ["kode"=>"transfer_bca",  "nama"=>"Transfer BCA",      "grup"=>"Transfer Bank",  "logo"=>"🏦","detail"=>"8370-XXXX-XXXX a.n HikeGear Indonesia"],
  ["kode"=>"transfer_bni",  "nama"=>"Transfer BNI",      "grup"=>"Transfer Bank",  "logo"=>"🏦","detail"=>"1234-5678-9012 a.n HikeGear Indonesia"],
  ["kode"=>"transfer_bri",  "nama"=>"Transfer BRI",      "grup"=>"Transfer Bank",  "logo"=>"🏦","detail"=>"0123-4567-8901 a.n HikeGear Indonesia"],
  ["kode"=>"gopay",         "nama"=>"GoPay",             "grup"=>"E-Wallet",       "logo"=>"💚","detail"=>"0812-3456-7890 a.n HikeGear"],
  ["kode"=>"ovo",           "nama"=>"OVO",               "grup"=>"E-Wallet",       "logo"=>"💜","detail"=>"0812-3456-7890 a.n HikeGear"],
  ["kode"=>"dana",          "nama"=>"DANA",              "grup"=>"E-Wallet",       "logo"=>"🔵","detail"=>"0812-3456-7890 a.n HikeGear"],
  ["kode"=>"qris",          "nama"=>"QRIS",              "grup"=>"E-Wallet",       "logo"=>"📱","detail"=>"Scan QR code saat pembayaran"],
  ["kode"=>"cod",           "nama"=>"Bayar di Tempat",   "grup"=>"COD",            "logo"=>"💵","detail"=>"Bayar tunai saat paket tiba (maks. Rp500.000)"],
];

// ── Handle submit pesanan ─────────────────────────────────────────────────────
$success = false;
$kode_pesanan = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['place_order'])) {
  // Validasi & simpan ke DB (di sini masih dummy)
  $kode_pesanan = 'ORD-'.strtoupper(substr(uniqid(),-6));
  $success = true;
  // buat_pesanan($pdo, [...]);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-300:#cdd9d2;--gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;
  --red:#e74c3c;--red-light:#fdecea;--orange:#e67e22;--yellow:#f39c12;
  --radius:10px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-md:0 4px 16px rgba(0,0,0,.10);--shadow-lg:0 12px 36px rgba(0,0,0,.14);
  --tr:.2s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
img{display:block;object-fit:cover}
button{cursor:pointer;border:none;font-family:inherit}
input,select,textarea{font-family:inherit}

/* ── Topbar ── */
.topbar{background:var(--green-dark);color:#c8e6d0;font-size:12.5px;padding:7px 32px;display:flex;align-items:center;justify-content:space-between}
.topbar-left{display:flex;align-items:center;gap:6px}
.topbar-right{display:flex;align-items:center;gap:20px}
.topbar-right a{display:flex;align-items:center;gap:5px;color:#c8e6d0;transition:color var(--tr)}
.topbar-right a:hover{color:#fff}

/* ── Header ── */
header{background:var(--white);border-bottom:1px solid var(--gray-200);padding:14px 32px;display:flex;align-items:center;gap:24px;position:sticky;top:0;z-index:200;box-shadow:var(--shadow-sm)}
.logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
.logo-icon{width:42px;height:42px;background:var(--green);border-radius:8px;display:flex;align-items:center;justify-content:center}
.logo-icon svg{width:23px;height:23px;fill:none;stroke:white;stroke-width:2}
.logo-text strong{font-size:18px;font-weight:800;color:var(--green-dark)}
.logo-text small{font-size:9px;color:var(--gray-600);letter-spacing:1.5px;text-transform:uppercase;display:block}
.header-secure{margin-left:auto;display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--gray-600)}
.header-secure svg{width:15px;height:15px;stroke:var(--accent);fill:none;stroke-width:2}
.header-steps{display:flex;align-items:center;gap:0;margin:0 auto}
.hstep{display:flex;align-items:center;gap:7px;font-size:12.5px;font-weight:600;color:var(--gray-400);padding:0 16px}
.hstep.done{color:var(--accent)}
.hstep.active{color:var(--green-dark)}
.hstep-num{width:24px;height:24px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;transition:all var(--tr)}
.hstep.done .hstep-num{background:var(--accent);border-color:var(--accent);color:white}
.hstep.active .hstep-num{background:var(--green-dark);border-color:var(--green-dark);color:white}
.hstep-line{width:40px;height:2px;background:var(--gray-200)}
.hstep-line.done{background:var(--accent)}

/* ── Page ── */
.page-wrap{max-width:1280px;margin:0 auto;padding:26px 32px}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--gray-600);margin-bottom:20px}
.breadcrumb a{color:var(--gray-600);transition:color var(--tr)}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb .sep{color:var(--gray-400)}
.breadcrumb .current{color:var(--gray-800);font-weight:600}

/* ── Layout ── */
.checkout-layout{display:grid;grid-template-columns:1fr 380px;gap:22px;align-items:start}

/* ── CARD ── */
.co-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);overflow:hidden;margin-bottom:16px}
.co-card:last-child{margin-bottom:0}
.co-card-header{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--gray-100)}
.co-card-header h3{font-size:15px;font-weight:800;color:var(--green-dark);display:flex;align-items:center;gap:9px}
.co-card-header h3 .num{width:24px;height:24px;border-radius:50%;background:var(--green);color:white;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.co-card-body{padding:20px}
.btn-ubah{font-size:12.5px;font-weight:600;color:var(--green);padding:5px 11px;border-radius:7px;background:var(--green-light);transition:all var(--tr)}
.btn-ubah:hover{background:var(--accent);color:white}

/* Form */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-group{margin-bottom:16px}
.form-group:last-child{margin-bottom:0}
.form-group label{display:block;font-size:12.5px;font-weight:700;color:var(--gray-800);margin-bottom:6px}
.form-group label .req{color:var(--red)}
.input-wrap{position:relative}
.input-wrap svg.ico{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;stroke:var(--gray-400);fill:none;stroke-width:1.8;pointer-events:none}
.form-control{width:100%;padding:10px 12px 10px 38px;border:1.5px solid var(--gray-200);border-radius:9px;font-size:13.5px;outline:none;transition:border-color var(--tr);background:var(--gray-50)}
.form-control:focus{border-color:var(--accent);background:white}
.form-control.no-icon{padding-left:12px}
select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%239ab0a4' stroke-width='3'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;cursor:pointer;padding-left:12px}
textarea.form-control{resize:vertical;min-height:70px;padding-top:10px;padding-left:12px}
.field-hint{font-size:11.5px;color:var(--gray-400);margin-top:4px}

/* Alamat saved */
.alamat-saved{display:flex;align-items:flex-start;gap:14px;padding:14px;background:var(--green-light);border-radius:9px;border:1.5px solid rgba(39,174,96,.25);margin-bottom:16px}
.alamat-saved svg{width:20px;height:20px;stroke:var(--green);fill:none;stroke-width:2;flex-shrink:0;margin-top:1px}
.alamat-saved-text .label{font-size:12px;font-weight:700;color:var(--green);text-transform:uppercase;letter-spacing:.4px;margin-bottom:3px}
.alamat-saved-text .name{font-size:14px;font-weight:700;color:var(--gray-800)}
.alamat-saved-text .detail{font-size:13px;color:var(--gray-600);line-height:1.6;margin-top:2px}
.btn-ganti-alamat{margin-left:auto;font-size:12px;font-weight:600;color:var(--green);background:none;white-space:nowrap;flex-shrink:0;padding:4px 10px;border-radius:6px;border:1.5px solid var(--green-light);transition:all var(--tr)}
.btn-ganti-alamat:hover{background:var(--accent);border-color:var(--accent);color:white}

/* Kurir options */
.kurir-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.kurir-option{position:relative}
.kurir-option input{position:absolute;opacity:0;width:0;height:0}
.kurir-label{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1.5px solid var(--gray-200);border-radius:9px;cursor:pointer;transition:all var(--tr);background:white}
.kurir-option input:checked + .kurir-label{border-color:var(--accent);background:var(--green-light)}
.kurir-label:hover{border-color:var(--gray-300);background:var(--gray-50)}
.kurir-icon{width:36px;height:36px;border-radius:7px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--gray-600);flex-shrink:0;transition:all var(--tr)}
.kurir-option input:checked + .kurir-label .kurir-icon{background:var(--accent);color:white}
.kurir-info{flex:1;min-width:0}
.kurir-nama{font-size:13px;font-weight:700;color:var(--gray-800)}
.kurir-layanan{font-size:11.5px;color:var(--gray-400)}
.kurir-harga{font-size:13px;font-weight:800;color:var(--green-dark);text-align:right;flex-shrink:0}
.kurir-est{font-size:10.5px;color:var(--gray-400);text-align:right}
.kurir-check{position:absolute;top:10px;right:10px;width:16px;height:16px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;transition:all var(--tr)}
.kurir-option input:checked + .kurir-label .kurir-check{background:var(--accent);border-color:var(--accent)}
.kurir-option input:checked + .kurir-label .kurir-check::after{content:'';width:6px;height:6px;border-radius:50%;background:white}

/* Metode Bayar */
.bayar-grup-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.7px;color:var(--gray-400);padding:10px 0 6px;display:flex;align-items:center;gap:8px}
.bayar-grup-title::after{content:'';flex:1;height:1px;background:var(--gray-100)}
.bayar-option{position:relative;margin-bottom:8px}
.bayar-option input{position:absolute;opacity:0;width:0;height:0}
.bayar-label{display:flex;align-items:center;gap:12px;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:9px;cursor:pointer;transition:all var(--tr);background:white}
.bayar-option input:checked + .bayar-label{border-color:var(--accent);background:var(--green-light)}
.bayar-label:hover{border-color:var(--gray-300)}
.bayar-logo{font-size:20px;width:32px;text-align:center}
.bayar-nama{font-size:13.5px;font-weight:700;color:var(--gray-800);flex:1}
.bayar-detail{font-size:11.5px;color:var(--gray-400);margin-top:2px}
.bayar-radio{width:17px;height:17px;border-radius:50%;border:2px solid var(--gray-300);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all var(--tr)}
.bayar-option input:checked + .bayar-label .bayar-radio{border-color:var(--accent);background:var(--accent)}
.bayar-option input:checked + .bayar-label .bayar-radio::after{content:'';width:6px;height:6px;border-radius:50%;background:white}
.bayar-info-box{display:none;background:var(--green-light);border:1.5px solid rgba(39,174,96,.25);border-radius:8px;padding:12px 14px;margin-top:8px;font-size:12.5px;color:var(--green-dark);line-height:1.6}
.bayar-option.selected .bayar-info-box{display:block}

/* Catatan */
.catatan-area{padding:16px 20px}

/* ── SIDEBAR RINGKASAN ── */
.sidebar-sticky{position:sticky;top:86px;display:flex;flex-direction:column;gap:14px}
.order-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);overflow:hidden}
.order-title{padding:14px 18px;font-size:14px;font-weight:800;color:var(--green-dark);border-bottom:1px solid var(--gray-100)}

/* item list */
.order-items{padding:0 18px}
.order-item{display:flex;align-items:center;gap:12px;padding:13px 0;border-bottom:1px solid var(--gray-100)}
.order-item:last-child{border:none}
.oi-img{width:56px;height:56px;border-radius:8px;overflow:hidden;background:var(--gray-100);flex-shrink:0;position:relative}
.oi-img img{width:56px;height:56px;object-fit:cover}
.oi-qty-badge{position:absolute;top:-5px;right:-5px;background:var(--gray-800);color:white;font-size:10px;font-weight:700;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center}
.oi-info{flex:1;min-width:0}
.oi-name{font-size:12.5px;font-weight:700;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.oi-varian{font-size:11px;color:var(--gray-400);margin-top:1px}
.oi-sub{font-size:13px;font-weight:800;color:var(--green-dark);text-align:right;flex-shrink:0}

/* harga breakdown */
.price-breakdown{padding:14px 18px;border-top:1px solid var(--gray-100)}
.pb-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:9px;font-size:13.5px}
.pb-row .l{color:var(--gray-600)}
.pb-row .v{color:var(--gray-800);font-weight:600}
.pb-row .v.green{color:var(--accent)}
.pb-row .v.red{color:var(--red)}
.pb-divider{height:1px;background:var(--gray-100);margin:10px 0}
.pb-total{display:flex;align-items:center;justify-content:space-between;font-size:16.5px;font-weight:800}
.pb-total .l{color:var(--gray-800)}
.pb-total .v{color:var(--green-dark);font-size:20px}

/* place order */
.order-action{padding:16px 18px;border-top:1px solid var(--gray-100)}
.btn-order{display:flex;align-items:center;justify-content:center;gap:9px;width:100%;padding:14px;background:var(--green);color:white;border-radius:var(--radius);font-size:15px;font-weight:700;transition:background var(--tr);border:none;cursor:pointer}
.btn-order:hover{background:var(--green-mid)}
.btn-order svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:2.3}
.order-note{font-size:11.5px;color:var(--gray-400);text-align:center;margin-top:10px;line-height:1.6}
.order-note a{color:var(--green)}

/* guarantee */
.order-guarantee{padding:12px 18px;border-top:1px solid var(--gray-100);display:flex;flex-direction:column;gap:7px}
.guar-item{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--gray-600)}
.guar-item svg{width:14px;height:14px;stroke:var(--green);fill:none;stroke-width:2;flex-shrink:0}

/* ── SUCCESS MODAL ── */
.success-overlay{position:fixed;inset:0;background:rgba(10,25,15,.6);display:none;align-items:center;justify-content:center;z-index:500;padding:20px;backdrop-filter:blur(4px)}
.success-overlay.show{display:flex}
.success-box{background:white;border-radius:16px;padding:44px 40px;max-width:480px;width:100%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.22);animation:popIn .3s ease}
@keyframes popIn{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
.success-icon{width:80px;height:80px;border-radius:50%;background:var(--green-light);display:flex;align-items:center;justify-content:center;margin:0 auto 20px}
.success-icon svg{width:40px;height:40px;stroke:var(--accent);fill:none;stroke-width:2.2}
.success-box h2{font-size:22px;font-weight:900;color:var(--green-dark);margin-bottom:8px}
.success-box p{font-size:14px;color:var(--gray-600);line-height:1.7;margin-bottom:8px}
.success-kode{display:inline-block;background:var(--green-light);color:var(--green);font-size:18px;font-weight:800;padding:8px 24px;border-radius:8px;letter-spacing:1px;margin-bottom:20px}
.success-steps{background:var(--gray-50);border-radius:10px;padding:16px 20px;text-align:left;margin-bottom:24px}
.success-steps .ss-item{display:flex;align-items:flex-start;gap:10px;font-size:13px;color:var(--gray-600);padding:6px 0;border-bottom:1px solid var(--gray-100);line-height:1.5}
.success-steps .ss-item:last-child{border:none}
.success-steps .ss-num{width:22px;height:22px;border-radius:50%;background:var(--accent);color:white;font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.success-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.btn-lacak{padding:11px 22px;background:var(--accent);color:white;border-radius:9px;font-size:14px;font-weight:700;transition:background var(--tr)}
.btn-lacak:hover{background:var(--accent-hover)}
.btn-home{padding:11px 22px;background:var(--green-light);color:var(--green);border-radius:9px;font-size:14px;font-weight:700;transition:all var(--tr)}
.btn-home:hover{background:var(--green);color:white}

/* footer */
footer{background:var(--green-dark);color:#aecfbe;padding:22px 32px;margin-top:36px}
.footer-bottom{max-width:1280px;margin:0 auto;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;font-size:12px;color:#5a8a72}

/* responsive */
@media(max-width:1000px){.checkout-layout{grid-template-columns:1fr}.sidebar-sticky{position:static}.kurir-grid{grid-template-columns:1fr}}
@media(max-width:600px){
  header{flex-wrap:wrap;padding:12px 16px}.header-steps{order:3;width:100%;justify-content:center;margin:8px 0 0;overflow-x:auto}
  .topbar-right{display:none}.page-wrap{padding:14px 14px}
  .co-card-body{padding:14px}.form-row{grid-template-columns:1fr}
  .success-box{padding:28px 20px}
}
</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
  <div class="topbar-left">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    Gratis Ongkir untuk semua pesanan di Indonesia
  </div>
  <div class="topbar-right">
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Transaksi Aman</a>
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Bantuan 24/7</a>
  </div>
</div>

<!-- Header -->
<header>
  <a href="index.php" class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg></div>
    <div class="logo-text"><strong>HIKEGEAR</strong><small>Adventure Starts Here</small></div>
  </a>

  <!-- Steps -->
  <div class="header-steps">
    <div class="hstep done">
      <div class="hstep-num"><svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
      Keranjang
    </div>
    <div class="hstep-line done"></div>
    <div class="hstep active">
      <div class="hstep-num">2</div>
      Pengiriman
    </div>
    <div class="hstep-line"></div>
    <div class="hstep">
      <div class="hstep-num">3</div>
      Pembayaran
    </div>
    <div class="hstep-line"></div>
    <div class="hstep">
      <div class="hstep-num">4</div>
      Selesai
    </div>
  </div>

  <div class="header-secure">
    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    Checkout Aman
  </div>
</header>

<!-- Page -->
<div class="page-wrap">
  <div class="breadcrumb">
    <a href="index.php">Beranda</a><span class="sep">›</span>
    <a href="keranjang.php">Keranjang</a><span class="sep">›</span>
    <span class="current">Checkout</span>
  </div>

  <form method="POST" id="checkoutForm" onsubmit="return submitOrder(event)">
  <div class="checkout-layout">

    <!-- LEFT -->
    <div>

      <!-- 1. ALAMAT PENGIRIMAN -->
      <div class="co-card">
        <div class="co-card-header">
          <h3><span class="num">1</span>Alamat Pengiriman</h3>
          <button type="button" class="btn-ubah" onclick="toggleAlamat()">Ganti Alamat</button>
        </div>
        <div class="co-card-body">

          <!-- Alamat tersimpan -->
          <div class="alamat-saved" id="alamatSaved">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <div class="alamat-saved-text">
              <div class="label">🏠 Rumah Utama</div>
              <div class="name">Budi Santoso — 0812-3456-7890</div>
              <div class="detail">Jl. Pendaki Sejati No. 12, RT 03/RW 05,<br>Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</div>
            </div>
          </div>

          <!-- Form Alamat Baru (toggle) -->
          <div id="alamatForm" style="display:none">
            <div class="form-row">
              <div class="form-group">
                <label>Nama Penerima <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="ico" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                  <input type="text" name="nama_penerima" class="form-control" placeholder="Nama lengkap penerima" required>
                </div>
              </div>
              <div class="form-group">
                <label>No. HP Penerima <span class="req">*</span></label>
                <div class="input-wrap">
                  <svg class="ico" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07"/></svg>
                  <input type="tel" name="hp_penerima" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Provinsi <span class="req">*</span></label>
                <select name="provinsi" class="form-control no-icon" required onchange="updateKota(this)">
                  <option value="">Pilih Provinsi</option>
                  <option value="jatim">Jawa Timur</option>
                  <option value="jateng">Jawa Tengah</option>
                  <option value="jabar">Jawa Barat</option>
                  <option value="dki">DKI Jakarta</option>
                  <option value="diy">DI Yogyakarta</option>
                  <option value="bali">Bali</option>
                  <option value="sulsel">Sulawesi Selatan</option>
                  <option value="sumut">Sumatera Utara</option>
                </select>
              </div>
              <div class="form-group">
                <label>Kota / Kabupaten <span class="req">*</span></label>
                <select name="kota" id="kotaSelect" class="form-control no-icon" required>
                  <option value="">Pilih Kota</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label>Kecamatan <span class="req">*</span></label>
                <input type="text" name="kecamatan" class="form-control no-icon" placeholder="Nama kecamatan" required>
              </div>
              <div class="form-group">
                <label>Kode Pos</label>
                <input type="text" name="kode_pos" class="form-control no-icon" placeholder="Contoh: 65141" maxlength="5">
              </div>
            </div>

            <div class="form-group">
              <label>Alamat Lengkap <span class="req">*</span></label>
              <textarea name="alamat_lengkap" class="form-control" placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan..." required></textarea>
              <div class="field-hint">Tulis nama jalan, nomor rumah, RT/RW, dan kelurahan dengan lengkap</div>
            </div>

            <div class="form-group">
              <label>Label Alamat</label>
              <div style="display:flex;gap:10px">
                <?php foreach(['Rumah','Kantor','Kos'] as $l): ?>
                <label style="display:flex;align-items:center;gap:6px;padding:7px 14px;border:1.5px solid var(--gray-200);border-radius:8px;cursor:pointer;font-size:13px;transition:all var(--tr)" class="label-tag">
                  <input type="radio" name="label_alamat" value="<?= $l ?>" style="accent-color:var(--accent)"> <?= $l ?>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px">
              <button type="button" onclick="simpanAlamat()" style="padding:9px 20px;background:var(--accent);color:white;border-radius:8px;font-size:13px;font-weight:700;transition:background var(--tr)">Simpan Alamat</button>
              <button type="button" onclick="toggleAlamat()" style="padding:9px 16px;background:var(--gray-100);color:var(--gray-800);border-radius:8px;font-size:13px;font-weight:600">Batal</button>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. PILIH KURIR -->
      <div class="co-card">
        <div class="co-card-header">
          <h3><span class="num">2</span>Pilih Kurir Pengiriman</h3>
        </div>
        <div class="co-card-body">
          <div style="font-size:12.5px;color:var(--gray-600);margin-bottom:14px;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M21 10H7"/><path d="M21 6H3"/><path d="M21 14H3"/><path d="M21 18H7"/></svg>
            Total berat: <strong><?= number_format($total_berat/1000,2) ?> kg</strong> &nbsp;|&nbsp; Kota tujuan: <strong>Kota Malang</strong>
          </div>
          <div class="kurir-grid">
            <?php foreach ($kurir_list as $k): ?>
            <div class="kurir-option">
              <input type="radio" name="kurir" id="kurir_<?= $k['kode'] ?>" value="<?= $k['kode'] ?>"
                     data-harga="<?= $k['harga'] ?>" <?= $k['kode']==='jne'?'checked':'' ?>
                     onchange="updateOngkir(<?= $k['harga'] ?>)">
              <label for="kurir_<?= $k['kode'] ?>" class="kurir-label">
                <div class="kurir-icon"><?= strtoupper(substr($k['nama'],0,3)) ?></div>
                <div class="kurir-info">
                  <div class="kurir-nama"><?= $k['nama'] ?> <span style="font-weight:400;font-size:11px;color:var(--gray-400)"><?= $k['layanan'] ?></span></div>
                  <div class="kurir-layanan"><?= $k['estimasi'] ?></div>
                </div>
                <div>
                  <div class="kurir-harga"><?= $k['harga']==0?'Gratis':fmt($k['harga']) ?></div>
                </div>
                <div class="kurir-check"></div>
              </label>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- 3. METODE PEMBAYARAN -->
      <div class="co-card">
        <div class="co-card-header">
          <h3><span class="num">3</span>Metode Pembayaran</h3>
        </div>
        <div class="co-card-body">
          <?php
          $current_grup = '';
          foreach ($bayar_list as $b):
            if ($b['grup'] !== $current_grup):
              $current_grup = $b['grup'];
          ?>
          <div class="bayar-grup-title"><?= $current_grup ?></div>
          <?php endif; ?>
          <div class="bayar-option" id="bayar_wrap_<?= $b['kode'] ?>">
            <input type="radio" name="metode_bayar" id="bayar_<?= $b['kode'] ?>" value="<?= $b['kode'] ?>"
                   <?= $b['kode']==='transfer_bca'?'checked':'' ?>
                   onchange="selectBayar('<?= $b['kode'] ?>')">
            <label for="bayar_<?= $b['kode'] ?>" class="bayar-label">
              <span class="bayar-logo"><?= $b['logo'] ?></span>
              <div>
                <div class="bayar-nama"><?= $b['nama'] ?></div>
                <div class="bayar-detail"><?= $b['detail'] ?></div>
              </div>
              <div class="bayar-radio"></div>
            </label>
            <div class="bayar-info-box">
              ℹ️ <?= $b['detail'] ?>
              <?php if ($b['kode']==='cod'): ?>
              <br><span style="color:var(--orange);font-weight:600">⚠️ Pastikan tersedia uang pas saat kurir tiba.</span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- 4. CATATAN -->
      <div class="co-card">
        <div class="co-card-header">
          <h3><span class="num">4</span>Catatan untuk Penjual <span style="font-size:12px;color:var(--gray-400);font-weight:400">(opsional)</span></h3>
        </div>
        <div class="catatan-area">
          <textarea name="catatan" class="form-control" placeholder="Contoh: Tolong dikemas ekstra bubble wrap, warna hitam size L ya... "></textarea>
        </div>
      </div>

    </div><!-- /left -->

    <!-- RIGHT SIDEBAR -->
    <div class="sidebar-sticky">
      <div class="order-card">
        <div class="order-title">🛒 Ringkasan Pesanan (<?= $total_item ?> item)</div>

        <!-- Items -->
        <div class="order-items">
          <?php foreach ($cart_items as $item): ?>
          <div class="order-item">
            <div class="oi-img">
              <img src="<?= $item['foto'] ?>" alt="<?= htmlspecialchars($item['nama']) ?>">
              <div class="oi-qty-badge"><?= $item['qty'] ?></div>
            </div>
            <div class="oi-info">
              <div class="oi-name"><?= htmlspecialchars($item['nama']) ?></div>
              <div class="oi-varian"><?= htmlspecialchars($item['varian']) ?></div>
            </div>
            <div class="oi-sub"><?= fmt($item['harga']*$item['qty']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Breakdown harga -->
        <div class="price-breakdown">
          <div class="pb-row"><span class="l">Subtotal</span><span class="v" id="pbSubtotal"><?= fmt($subtotal) ?></span></div>
          <div class="pb-row"><span class="l">Ongkos Kirim</span><span class="v green" id="pbOngkir"><?= fmt($ongkir) ?></span></div>
          <?php if ($diskon>0): ?>
          <div class="pb-row"><span class="l">Diskon</span><span class="v red">− <?= fmt($diskon) ?></span></div>
          <?php endif; ?>
          <div class="pb-divider"></div>
          <div class="pb-total">
            <span class="l">Total</span>
            <span class="v" id="pbTotal"><?= fmt($total) ?></span>
          </div>
        </div>

        <!-- Place order -->
        <div class="order-action">
          <input type="hidden" name="place_order" value="1">
          <button type="submit" class="btn-order">
            <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Buat Pesanan Sekarang
          </button>
          <div class="order-note">
            Dengan menekan tombol di atas, kamu menyetujui
            <a href="#">Syarat & Ketentuan</a> HikeGear
          </div>
        </div>

        <div class="order-guarantee">
          <div class="guar-item"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Transaksi 100% aman & terenkripsi</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>Garansi pengembalian 7 hari</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Pengiriman 1–3 hari kerja</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07"/></svg>CS siap membantu 24/7</div>
        </div>
      </div>

      <!-- Kupon info -->
      <?php if ($diskon>0): ?>
      <div style="background:var(--green-light);border:1.5px solid rgba(39,174,96,.25);border-radius:var(--radius);padding:13px 16px;font-size:12.5px;color:var(--green);display:flex;align-items:center;gap:8px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
        Kupon diterapkan — hemat <?= fmt($diskon) ?>!
      </div>
      <?php else: ?>
      <a href="keranjang.php" style="display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--green);padding:11px 14px;border-radius:var(--radius);background:var(--green-light);transition:all var(--tr)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali & tambah kupon di keranjang
      </a>
      <?php endif; ?>
    </div>

  </div><!-- /checkout-layout -->
  </form>
</div><!-- /page-wrap -->

<!-- Footer -->
<footer>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> HikeGear. Hak cipta dilindungi.</span>
    <span>🔒 Checkout ini dilindungi SSL 256-bit</span>
  </div>
</footer>

<!-- SUCCESS MODAL -->
<div class="success-overlay" id="successOverlay">
  <div class="success-box">
    <div class="success-icon">
      <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h2>Pesanan Berhasil! 🎉</h2>
    <p>Terima kasih telah berbelanja di HikeGear. Pesananmu sedang kami proses.</p>
    <div class="success-kode" id="successKode"><?= $kode_pesanan ?></div>

    <div class="success-steps">
      <div class="ss-item"><div class="ss-num">1</div>Konfirmasi pesanan dikirim ke email kamu</div>
      <div class="ss-item"><div class="ss-num">2</div>Lakukan pembayaran sesuai metode yang dipilih</div>
      <div class="ss-item"><div class="ss-num">3</div>Pesanan dikemas dan dikirim dalam 1×24 jam</div>
      <div class="ss-item"><div class="ss-num">4</div>Lacak paketmu melalui menu "Lacak Pesanan"</div>
    </div>

    <div class="success-actions">
      <a href="#" class="btn-lacak">🔍 Lacak Pesanan</a>
      <a href="index.php" class="btn-home">🏠 Kembali ke Beranda</a>
    </div>
  </div>
</div>

<script>
// ── Toggle form alamat ────────────────────────────────────────────────────────
function toggleAlamat() {
  const saved = document.getElementById('alamatSaved');
  const form  = document.getElementById('alamatForm');
  const show  = form.style.display === 'none';
  form.style.display  = show ? 'block' : 'none';
  saved.style.display = show ? 'none'  : 'flex';
}
function simpanAlamat() {
  document.getElementById('alamatForm').style.display  = 'none';
  document.getElementById('alamatSaved').style.display = 'flex';
  showToast('Alamat berhasil disimpan!');
}

// ── Kota dropdown ─────────────────────────────────────────────────────────────
const kotaMap = {
  jatim: ['Kota Malang','Kota Surabaya','Kota Batu','Kab. Malang','Kab. Sidoarjo'],
  jateng:['Kota Semarang','Kota Solo','Kota Magelang','Kab. Boyolali'],
  jabar: ['Kota Bandung','Kota Bogor','Kota Depok','Kab. Bekasi'],
  dki:   ['Jakarta Pusat','Jakarta Selatan','Jakarta Barat','Jakarta Timur','Jakarta Utara'],
  diy:   ['Kota Yogyakarta','Kab. Sleman','Kab. Bantul','Kab. Gunungkidul'],
  bali:  ['Kota Denpasar','Kab. Badung','Kab. Gianyar','Kab. Tabanan'],
  sulsel:['Kota Makassar','Kab. Gowa','Kab. Maros','Kab. Bone'],
  sumut: ['Kota Medan','Kab. Deli Serdang','Kota Binjai','Kab. Serdang Bedagai'],
};
function updateKota(sel) {
  const kotaSel = document.getElementById('kotaSelect');
  kotaSel.innerHTML = '<option value="">Pilih Kota</option>';
  (kotaMap[sel.value] || []).forEach(k => {
    const opt = document.createElement('option');
    opt.value = k; opt.textContent = k;
    kotaSel.appendChild(opt);
  });
}

// ── Update ongkir di sidebar ──────────────────────────────────────────────────
const baseSubtotal = <?= $subtotal ?>;
const baseDiskon   = <?= $diskon ?>;

function updateOngkir(harga) {
  const total = baseSubtotal - baseDiskon + harga;
  document.getElementById('pbOngkir').textContent = harga === 0 ? 'Gratis' : 'Rp' + harga.toLocaleString('id-ID');
  document.getElementById('pbTotal').textContent  = 'Rp' + total.toLocaleString('id-ID');
}

// ── Select metode bayar ───────────────────────────────────────────────────────
function selectBayar(kode) {
  document.querySelectorAll('.bayar-option').forEach(el => el.classList.remove('selected'));
  document.getElementById('bayar_wrap_' + kode)?.classList.add('selected');
}
// Set default on load
window.addEventListener('DOMContentLoaded', () => {
  selectBayar('transfer_bca');
  // Animate
  document.querySelectorAll('.co-card').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(14px)';
    el.style.transition = `opacity .4s ease ${i*.08}s, transform .4s ease ${i*.08}s`;
    setTimeout(() => { el.style.opacity='1'; el.style.transform='translateY(0)'; }, i*80);
  });
});

// ── Submit pesanan ────────────────────────────────────────────────────────────
function submitOrder(e) {
  e.preventDefault();
  const btn = document.querySelector('.btn-order');
  btn.disabled = true;
  btn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="2.3" style="animation:spin 1s linear infinite"><path d="M12 2a10 10 0 0 1 10 10"/></svg> Memproses...';

  // Validasi sederhana
  const metode = document.querySelector('input[name="metode_bayar"]:checked');
  const kurir  = document.querySelector('input[name="kurir"]:checked');
  if (!metode || !kurir) {
    alert('Pilih metode pembayaran dan kurir terlebih dahulu.');
    btn.disabled = false;
    btn.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="white" stroke-width="2.3"><path d="M5 12h14M12 5l7 7-7 7"/></svg> Buat Pesanan Sekarang';
    return false;
  }

  // Simulasi proses (di sini ganti dengan fetch ke backend)
  setTimeout(() => {
    const kode = 'ORD-' + Math.random().toString(36).substr(2,6).toUpperCase();
    document.getElementById('successKode').textContent = kode;
    document.getElementById('successOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
  }, 1500);
  return false;
}

// ── Toast ─────────────────────────────────────────────────────────────────────
function showToast(msg) {
  let t = document.getElementById('toast');
  if (!t) {
    t = document.createElement('div');
    t.id = 'toast';
    t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:var(--green-dark);color:white;padding:12px 20px;border-radius:10px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:9px;box-shadow:0 8px 30px rgba(0,0,0,.2);z-index:999;transform:translateY(20px);opacity:0;transition:all .3s ease';
    t.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span></span>';
    document.body.appendChild(t);
  }
  t.querySelector('span').textContent = msg;
  t.style.opacity='1'; t.style.transform='translateY(0)';
  setTimeout(() => { t.style.opacity='0'; t.style.transform='translateY(20px)'; }, 3000);
}

// ── CSS spin animation ────────────────────────────────────────────────────────
const style = document.createElement('style');
style.textContent = '@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}';
document.head.appendChild(style);

// ── Label tag interaktif ──────────────────────────────────────────────────────
document.querySelectorAll('.label-tag').forEach(label => {
  label.addEventListener('click', function() {
    document.querySelectorAll('.label-tag').forEach(l => {
      l.style.borderColor = 'var(--gray-200)';
      l.style.background  = 'white';
      l.style.color       = 'var(--gray-800)';
    });
    this.style.borderColor = 'var(--accent)';
    this.style.background  = 'var(--green-light)';
    this.style.color       = 'var(--green)';
  });
});
</script>
</body>
</html>