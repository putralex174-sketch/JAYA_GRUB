<?php
session_start();
// require_once __DIR__.'/config/db.php';
// require_once __DIR__.'/config/helpers.php';
// require_once __DIR__.'/config/model_keranjang.php';

// ── Data dummy keranjang (ganti dengan query database) ──────────────────────
$cart_items = [
  [
    "id"=>1,"produk_id"=>1,"nama"=>"Eiger Mountaineer Jacket",
    "brand"=>"Eiger","kategori"=>"Jaket Outdoor",
    "harga"=>1250000,"harga_asli"=>null,"stok"=>34,"qty"=>1,
    "foto"=>"https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=300&q=80",
    "varian"=>"Hitam / L","berat"=>800,
  ],
  [
    "id"=>2,"produk_id"=>5,"nama"=>"Consina Carrier 60L",
    "brand"=>"Consina","kategori"=>"Tas Carrier",
    "harga"=>1275000,"harga_asli"=>1500000,"stok"=>12,"qty"=>1,
    "foto"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=300&q=80",
    "varian"=>"Hijau Army","berat"=>1200,
  ],
  [
    "id"=>3,"produk_id"=>7,"nama"=>"Rei Sleeping Bag Polar",
    "brand"=>"Rei","kategori"=>"Sleeping Bag",
    "harga"=>650000,"harga_asli"=>null,"stok"=>5,"qty"=>2,
    "foto"=>"https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=300&q=80",
    "varian"=>"Biru Navy","berat"=>900,
  ],
];

// ── Promo/kupon dummy ────────────────────────────────────────────────────────
$promo_applied = null;
$kode_input    = '';

if (isset($_POST['apply_kupon'])) {
    $kode_input = strtoupper(trim($_POST['kode_kupon'] ?? ''));
    $valid_kode = ['HIKE10'=>['tipe'=>'persen','nilai'=>10,'label'=>'Diskon 10%'],
                   'HEMAT50K'=>['tipe'=>'nominal','nilai'=>50000,'label'=>'Hemat Rp50.000'],
                   'NEWMEMBER'=>['tipe'=>'persen','nilai'=>15,'label'=>'New Member 15%']];
    if (isset($valid_kode[$kode_input])) $promo_applied = $valid_kode[$kode_input];
}

// ── Hitung total ─────────────────────────────────────────────────────────────
function fmt($n){ return 'Rp'.number_format($n,0,',','.'); }

$subtotal   = array_sum(array_map(fn($i)=>$i['harga']*$i['qty'], $cart_items));
$total_berat= array_sum(array_map(fn($i)=>$i['berat']*$i['qty'], $cart_items));
$ongkir     = $subtotal >= 200000 ? 0 : 15000;
$diskon     = 0;
if ($promo_applied) {
    $diskon = $promo_applied['tipe']==='persen'
        ? round($subtotal * $promo_applied['nilai'] / 100)
        : $promo_applied['nilai'];
}
$total      = $subtotal - $diskon + $ongkir;
$total_item = array_sum(array_column($cart_items,'qty'));

$produk_rekomendasi = [
  ["id"=>4,"nama"=>"Eiger Quick Dry Tee","brand"=>"Eiger","harga"=>199000,"harga_asli"=>265000,
   "foto"=>"https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&q=80","disc"=>25],
  ["id"=>9,"nama"=>"Eiger Headlamp 500L","brand"=>"Eiger","harga"=>325000,"harga_asli"=>null,
   "foto"=>"https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=300&q=80","disc"=>null],
  ["id"=>10,"nama"=>"Consina Winter Glove","brand"=>"Consina","harga"=>275000,"harga_asli"=>350000,
   "foto"=>"https://images.unsplash.com/photo-1601762603339-fd61e28b698a?w=300&q=80","disc"=>21],
  ["id"=>8,"nama"=>"Naturehike Alat Masak","brand"=>"Naturehike","harga"=>550000,"harga_asli"=>null,
   "foto"=>"https://images.unsplash.com/photo-1464146072230-91cabc968266?w=300&q=80","disc"=>null],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Keranjang Belanja – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-300:#cdd9d2;--gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --red:#e74c3c;--red-light:#fdecea;--orange:#e67e22;
  --radius:10px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-md:0 4px 16px rgba(0,0,0,.10);--shadow-lg:0 8px 32px rgba(0,0,0,.14);
  --tr:.2s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
img{display:block;object-fit:cover}
button{cursor:pointer;border:none;font-family:inherit}
input{font-family:inherit}

/* ── Topbar ── */
.topbar{background:var(--green-dark);color:#c8e6d0;font-size:12.5px;padding:7px 32px;display:flex;align-items:center;justify-content:space-between}
.topbar-left{display:flex;align-items:center;gap:6px}
.topbar-right{display:flex;align-items:center;gap:20px}
.topbar-right a{display:flex;align-items:center;gap:5px;color:#c8e6d0;transition:color var(--tr)}
.topbar-right a:hover{color:#fff}

/* ── Header ── */
header{background:var(--white);border-bottom:1px solid var(--gray-200);padding:15px 32px;display:flex;align-items:center;gap:24px;position:sticky;top:0;z-index:200;box-shadow:var(--shadow-sm)}
.logo{display:flex;align-items:center;gap:11px;flex-shrink:0}
.logo-icon{width:44px;height:44px;background:var(--green);border-radius:9px;display:flex;align-items:center;justify-content:center}
.logo-icon svg{width:25px;height:25px;fill:none;stroke:white;stroke-width:2}
.logo-text strong{font-size:19px;font-weight:800;color:var(--green-dark);letter-spacing:-.3px}
.logo-text small{font-size:9px;color:var(--gray-600);letter-spacing:1.5px;text-transform:uppercase;display:block}
.search-wrap{flex:1;max-width:580px;display:flex}
.search-wrap input{flex:1;padding:11px 18px;border:1.5px solid var(--gray-200);border-right:none;border-radius:var(--radius) 0 0 var(--radius);font-size:14px;outline:none;transition:border-color var(--tr);background:var(--gray-50)}
.search-wrap input:focus{border-color:var(--accent);background:var(--white)}
.search-wrap button{background:var(--accent);color:white;padding:11px 20px;border-radius:0 var(--radius) var(--radius) 0;transition:background var(--tr)}
.search-wrap button:hover{background:var(--accent-hover)}
.search-wrap button svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:2.5}
.header-actions{display:flex;align-items:center;gap:6px;margin-left:auto}
.haction{display:flex;align-items:center;gap:6px;padding:9px 13px;border-radius:8px;color:var(--gray-800);font-size:13px;font-weight:500;transition:background var(--tr);position:relative}
.haction:hover{background:var(--green-light);color:var(--green)}
.haction svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8}
.cart-badge{position:absolute;top:4px;right:5px;background:var(--red);color:white;font-size:10px;font-weight:700;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white}

/* ── Nav ── */
nav{background:var(--white);border-bottom:1px solid var(--gray-200);padding:0 32px;display:flex;align-items:center;gap:4px}
.nav-cat-btn{display:flex;align-items:center;gap:8px;background:var(--green);color:white;padding:12px 18px;font-size:13.5px;font-weight:600;transition:background var(--tr);margin-right:10px}
.nav-cat-btn:hover{background:var(--green-mid)}
.nav-cat-btn svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2.5}
.nav-links{display:flex}
.nav-links a{padding:13px 16px;font-size:13.5px;color:var(--gray-800);border-bottom:2px solid transparent;transition:all var(--tr);font-weight:500}
.nav-links a:hover{color:var(--accent);border-bottom-color:var(--accent)}

/* ── Page ── */
.page-wrap{max-width:1340px;margin:0 auto;padding:28px 32px}

/* breadcrumb */
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--gray-600);margin-bottom:20px}
.breadcrumb a{color:var(--gray-600);transition:color var(--tr)}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb .sep{color:var(--gray-400)}
.breadcrumb .current{color:var(--gray-800);font-weight:600}

/* page title */
.page-title{display:flex;align-items:center;gap:12px;margin-bottom:24px}
.page-title h1{font-size:24px;font-weight:800;color:var(--green-dark);letter-spacing:-.3px}
.page-title .badge-count{background:var(--accent);color:white;font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px}

/* ── Layout grid ── */
.cart-layout{display:grid;grid-template-columns:1fr 360px;gap:22px;align-items:start}

/* ── CART TABLE ── */
.cart-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);overflow:hidden}
.cart-toolbar{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--gray-100)}
.cart-toolbar-left{display:flex;align-items:center;gap:10px}
.select-all-label{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--gray-800);cursor:pointer}
.select-all-label input{width:16px;height:16px;accent-color:var(--accent)}
.selected-info{font-size:12px;color:var(--gray-400)}
.btn-hapus-terpilih{display:none;align-items:center;gap:5px;font-size:12.5px;font-weight:600;color:var(--red);padding:6px 11px;border-radius:7px;background:var(--red-light);transition:all var(--tr)}
.btn-hapus-terpilih:hover{background:var(--red);color:white}
.btn-hapus-terpilih svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.3}
.btn-hapus-terpilih.show{display:flex}

/* cart items */
.cart-items{list-style:none}
.cart-item{display:grid;grid-template-columns:24px 100px 1fr auto auto;gap:14px;align-items:center;padding:18px 20px;border-bottom:1px solid var(--gray-100);transition:background var(--tr)}
.cart-item:hover{background:var(--gray-50)}
.cart-item:last-child{border:none}
.cart-item.removing{opacity:0;transform:translateX(-20px);transition:all .3s ease}

.ci-check input{width:16px;height:16px;accent-color:var(--accent);cursor:pointer}
.ci-img{width:100px;height:100px;border-radius:9px;overflow:hidden;background:var(--gray-100);flex-shrink:0}
.ci-img img{width:100%;height:100%;object-fit:cover;transition:transform var(--tr)}
.cart-item:hover .ci-img img{transform:scale(1.04)}

.ci-info{min-width:0}
.ci-brand{font-size:11px;font-weight:600;color:var(--gray-400);text-transform:uppercase;letter-spacing:.3px;margin-bottom:2px}
.ci-name{font-size:14px;font-weight:700;color:var(--gray-800);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ci-varian{display:inline-flex;align-items:center;gap:4px;font-size:11.5px;color:var(--gray-600);background:var(--gray-100);padding:3px 9px;border-radius:5px;margin-bottom:6px}
.ci-berat{font-size:11.5px;color:var(--gray-400)}
.ci-actions{display:flex;align-items:center;gap:6px;margin-top:6px}
.ci-action-btn{display:flex;align-items:center;gap:4px;font-size:12px;color:var(--gray-600);padding:4px 8px;border-radius:5px;background:none;transition:all var(--tr)}
.ci-action-btn:hover{background:var(--gray-100);color:var(--gray-800)}
.ci-action-btn.delete:hover{background:var(--red-light);color:var(--red)}
.ci-action-btn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2}

.ci-qty{display:flex;align-items:center;gap:0;border:1.5px solid var(--gray-200);border-radius:8px;overflow:hidden}
.qty-btn{width:34px;height:36px;display:flex;align-items:center;justify-content:center;background:var(--gray-50);font-size:16px;font-weight:700;color:var(--gray-800);transition:all var(--tr);flex-shrink:0}
.qty-btn:hover{background:var(--green-light);color:var(--green)}
.qty-btn:active{background:var(--accent);color:white}
.qty-num{width:44px;height:36px;text-align:center;border:none;border-left:1.5px solid var(--gray-200);border-right:1.5px solid var(--gray-200);outline:none;font-size:14px;font-weight:700;color:var(--gray-800);background:white}

.ci-subtotal{text-align:right;min-width:110px}
.ci-subtotal .harga{font-size:16px;font-weight:800;color:var(--green-dark);white-space:nowrap}
.ci-subtotal .coret{font-size:11.5px;color:var(--gray-400);text-decoration:line-through;margin-bottom:2px}
.ci-subtotal .hemat{font-size:11px;color:var(--accent);font-weight:600;margin-top:2px}

/* stok warning */
.stok-warn{display:flex;align-items:center;gap:5px;font-size:11.5px;color:var(--orange);margin-top:4px}
.stok-warn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2.2}

/* empty cart */
.empty-cart{text-align:center;padding:70px 20px}
.empty-cart-icon{width:80px;height:80px;border-radius:50%;background:var(--green-light);display:flex;align-items:center;justify-content:center;margin:0 auto 20px}
.empty-cart-icon svg{width:38px;height:38px;stroke:var(--gray-400);fill:none;stroke-width:1.5}
.empty-cart h3{font-size:18px;font-weight:700;color:var(--gray-800);margin-bottom:8px}
.empty-cart p{font-size:13.5px;color:var(--gray-600);margin-bottom:22px}
.btn-belanja{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:var(--accent);color:white;border-radius:var(--radius);font-size:14px;font-weight:700;transition:background var(--tr)}
.btn-belanja:hover{background:var(--accent-hover)}
.btn-belanja svg{width:17px;height:17px;stroke:white;fill:none;stroke-width:2.3}

/* cart footer */
.cart-footer{padding:14px 20px;border-top:1px solid var(--gray-100);display:flex;align-items:center;justify-content:space-between;background:var(--gray-50);flex-wrap:wrap;gap:10px}
.cart-footer-left{font-size:12.5px;color:var(--gray-600)}
.cart-footer-left strong{color:var(--green-dark)}
.btn-lanjut{display:flex;align-items:center;gap:7px;font-size:13px;font-weight:600;color:var(--green);padding:8px 14px;border-radius:8px;border:1.5px solid var(--green-light);background:var(--green-light);transition:all var(--tr)}
.btn-lanjut:hover{background:var(--accent);border-color:var(--accent);color:white}
.btn-lanjut svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.3}

/* ── SIDEBAR RINGKASAN ── */
.sidebar-sticky{position:sticky;top:90px;display:flex;flex-direction:column;gap:14px}
.summary-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);overflow:hidden}
.summary-title{padding:15px 20px;font-size:14.5px;font-weight:800;color:var(--green-dark);border-bottom:1px solid var(--gray-100)}

/* kupon */
.kupon-body{padding:16px 20px}
.kupon-body label{display:block;font-size:12px;font-weight:700;color:var(--gray-800);margin-bottom:8px}
.kupon-form{display:flex;gap:8px}
.kupon-input{flex:1;padding:9px 12px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px;outline:none;transition:border-color var(--tr);text-transform:uppercase}
.kupon-input:focus{border-color:var(--accent)}
.kupon-input.valid{border-color:var(--accent);background:var(--green-light)}
.kupon-input.invalid{border-color:var(--red);background:#fff9f9}
.btn-kupon{padding:9px 16px;background:var(--accent);color:white;border-radius:8px;font-size:13px;font-weight:700;white-space:nowrap;transition:background var(--tr)}
.btn-kupon:hover{background:var(--accent-hover)}
.kupon-msg{margin-top:7px;font-size:12px;display:flex;align-items:center;gap:5px}
.kupon-msg svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2.2}
.kupon-msg.ok{color:var(--accent)}
.kupon-msg.err{color:var(--red)}
.kupon-applied{display:flex;align-items:center;justify-content:space-between;background:var(--green-light);border-radius:8px;padding:9px 12px;margin-top:8px}
.kupon-applied span{font-size:12.5px;font-weight:700;color:var(--green)}
.btn-hapus-kupon{font-size:11.5px;color:var(--red);font-weight:600;background:none;padding:0;transition:color var(--tr)}
.btn-hapus-kupon:hover{color:#c0392b}

/* ringkasan harga */
.summary-body{padding:16px 20px}
.summary-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:11px;font-size:13.5px}
.summary-row .label{color:var(--gray-600)}
.summary-row .val{color:var(--gray-800);font-weight:600}
.summary-row .val.green{color:var(--accent)}
.summary-row .val.red{color:var(--red)}
.summary-divider{height:1px;background:var(--gray-100);margin:12px 0}
.summary-total{display:flex;align-items:center;justify-content:space-between;font-size:16px;font-weight:800;padding-top:4px}
.summary-total .tot-label{color:var(--gray-800)}
.summary-total .tot-val{color:var(--green-dark);font-size:20px}

/* gratis ongkir progress */
.ongkir-progress{margin-top:12px;padding-top:12px;border-top:1px solid var(--gray-100)}
.ongkir-progress .prog-label{font-size:12px;color:var(--gray-600);margin-bottom:7px}
.ongkir-progress .prog-label strong{color:var(--accent)}
.prog-track{height:6px;background:var(--gray-200);border-radius:3px;overflow:hidden}
.prog-fill{height:100%;background:var(--accent);border-radius:3px;transition:width .5s ease}

/* checkout btn */
.btn-checkout{display:flex;align-items:center;justify-content:center;gap:9px;width:100%;padding:14px;background:var(--green);color:white;border-radius:var(--radius);font-size:15px;font-weight:700;transition:background var(--tr);margin:0 20px 16px;width:calc(100% - 40px)}
.btn-checkout:hover{background:var(--green-mid)}
.btn-checkout svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:2.3}
.btn-checkout:disabled{background:var(--gray-300);cursor:not-allowed}

.summary-guarantee{padding:12px 20px;border-top:1px solid var(--gray-100);display:flex;flex-direction:column;gap:7px}
.guar-item{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--gray-600)}
.guar-item svg{width:14px;height:14px;stroke:var(--green);fill:none;stroke-width:2;flex-shrink:0}

/* ── REKOMENDASI ── */
.rekomendasi-section{margin-top:28px}
.rek-title{font-size:18px;font-weight:800;color:var(--green-dark);margin-bottom:16px}
.rek-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.rek-card{background:white;border-radius:var(--radius);border:1.5px solid var(--gray-200);overflow:hidden;box-shadow:var(--shadow-sm);transition:all var(--tr)}
.rek-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:var(--accent)}
.rek-img{height:150px;overflow:hidden;position:relative}
.rek-img img{width:100%;height:100%;transition:transform .35s ease}
.rek-card:hover .rek-img img{transform:scale(1.06)}
.rek-disc{position:absolute;top:8px;left:8px;background:var(--red);color:white;font-size:10.5px;font-weight:800;padding:2px 7px;border-radius:4px}
.rek-body{padding:12px}
.rek-brand{font-size:10.5px;color:var(--gray-400);font-weight:600;text-transform:uppercase;margin-bottom:2px}
.rek-name{font-size:13px;font-weight:700;color:var(--gray-800);margin-bottom:8px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.rek-foot{display:flex;align-items:center;justify-content:space-between}
.rek-harga{font-size:14px;font-weight:800;color:var(--green-dark)}
.rek-coret{font-size:11px;color:var(--gray-400);text-decoration:line-through;margin-bottom:1px}
.btn-add-rek{width:30px;height:30px;border-radius:7px;background:var(--green-light);display:flex;align-items:center;justify-content:center;transition:all var(--tr)}
.btn-add-rek:hover{background:var(--accent)}
.btn-add-rek svg{width:14px;height:14px;stroke:var(--green);fill:none;stroke-width:2.3;transition:stroke var(--tr)}
.btn-add-rek:hover svg{stroke:white}

/* toast */
.toast{position:fixed;bottom:26px;right:26px;background:var(--green-dark);color:white;padding:13px 20px;border-radius:10px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:var(--shadow-lg);z-index:999;transform:translateY(20px);opacity:0;transition:all .3s ease;max-width:340px}
.toast.show{transform:translateY(0);opacity:1}
.toast svg{width:18px;height:18px;stroke:var(--accent);fill:none;stroke-width:2.4;flex-shrink:0}

/* footer */
footer{background:var(--green-dark);color:#aecfbe;padding:28px 32px 18px;margin-top:40px;font-size:12.5px}
.footer-bottom{max-width:1340px;margin:0 auto;display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;color:#5a8a72;font-size:12px}

/* responsive */
@media(max-width:1100px){.cart-layout{grid-template-columns:1fr 320px}.rek-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:860px){.cart-layout{grid-template-columns:1fr}.sidebar-sticky{position:static}.rek-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){
  header{flex-wrap:wrap}.search-wrap{order:3;max-width:100%;flex:0 0 100%}
  .header-actions span{display:none}.topbar-right{display:none}
  .page-wrap{padding:16px}
  .cart-item{grid-template-columns:20px 76px 1fr;gap:10px}
  .ci-subtotal,.ci-qty{display:none}
  .cart-item .ci-actions{flex-wrap:wrap}
  .rek-grid{grid-template-columns:repeat(2,1fr);gap:10px}
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
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>Lacak Pesanan</a>
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>Bantuan</a>
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Kontak Kami</a>
  </div>
</div>

<!-- Header -->
<header>
  <a href="index.php" class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg></div>
    <div class="logo-text"><strong>HIKEGEAR</strong><small>Adventure Starts Here</small></div>
  </a>
  <form class="search-wrap" action="produk.php" method="GET">
    <input type="text" name="q" placeholder="Cari produk pendakian...">
    <button type="submit"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></button>
  </form>
  <div class="header-actions">
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><span>Wishlist</span></a>
    <a class="haction" href="login.php"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Masuk</span></a>
    <a class="haction" href="keranjang.php" style="position:relative;background:var(--green-light)">
      <svg viewBox="0 0 24 24" style="stroke:var(--green)"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      <span style="color:var(--green);font-weight:700">Keranjang</span>
      <span class="cart-badge" id="cartBadge"><?= $total_item ?></span>
    </a>
  </div>
</header>

<!-- Nav -->
<nav>
  <button class="nav-cat-btn"><svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>KATEGORI</button>
  <div class="nav-links">
    <a href="index.php">Beranda</a>
    <a href="kategori.php">Kategori</a>
    <a href="produk.php">Produk</a>
    <a href="promo.php">Promo</a>
    <a href="artikel.php">Artikel</a>
    <a href="tentang.php">Tentang Kami</a>
  </div>
</nav>

<!-- PAGE -->
<div class="page-wrap">

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="index.php">Beranda</a><span class="sep">›</span>
    <span class="current">Keranjang Belanja</span>
  </div>

  <!-- Title -->
  <div class="page-title">
    <h1>Keranjang Belanja</h1>
    <span class="badge-count"><?= $total_item ?> item</span>
  </div>

  <?php if (empty($cart_items)): ?>
  <!-- EMPTY CART -->
  <div class="cart-card">
    <div class="empty-cart">
      <div class="empty-cart-icon">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      </div>
      <h3>Keranjangmu masih kosong</h3>
      <p>Yuk, tambahkan produk perlengkapan pendakian favoritmu!</p>
      <a href="produk.php" class="btn-belanja">
        <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Mulai Belanja
      </a>
    </div>
  </div>

  <?php else: ?>
  <div class="cart-layout">

    <!-- LEFT: DAFTAR ITEM -->
    <div>
      <div class="cart-card">
        <!-- Toolbar -->
        <div class="cart-toolbar">
          <div class="cart-toolbar-left">
            <label class="select-all-label">
              <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
              Pilih Semua
            </label>
            <span class="selected-info" id="selectedInfo"></span>
          </div>
          <button class="btn-hapus-terpilih" id="btnHapusTerpilih" onclick="hapusTerpilih()">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            Hapus Terpilih
          </button>
        </div>

        <!-- Items -->
        <ul class="cart-items" id="cartItems">
          <?php foreach ($cart_items as $item):
            $subtotal_item = $item['harga'] * $item['qty'];
            $hemat = $item['harga_asli'] ? ($item['harga_asli'] - $item['harga']) * $item['qty'] : 0;
            $stok_sedikit = $item['stok'] <= 5;
          ?>
          <li class="cart-item" data-id="<?= $item['id'] ?>" data-harga="<?= $item['harga'] ?>">
            <!-- Checkbox -->
            <div class="ci-check">
              <input type="checkbox" class="item-check" onchange="updateSelected()">
            </div>

            <!-- Foto -->
            <a href="detail-produk.php?id=<?= $item['produk_id'] ?>" class="ci-img">
              <img src="<?= $item['foto'] ?>" alt="<?= htmlspecialchars($item['nama']) ?>">
            </a>

            <!-- Info -->
            <div class="ci-info">
              <div class="ci-brand"><?= htmlspecialchars($item['brand']) ?></div>
              <a href="detail-produk.php?id=<?= $item['produk_id'] ?>" class="ci-name"><?= htmlspecialchars($item['nama']) ?></a>
              <div class="ci-varian">
                <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg>
                <?= htmlspecialchars($item['varian']) ?>
              </div>
              <?php if ($stok_sedikit): ?>
              <div class="stok-warn">
                <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                Stok tersisa <?= $item['stok'] ?> pcs — segera checkout!
              </div>
              <?php endif; ?>
              <div class="ci-berat">Berat: <?= number_format($item['berat']/1000,1) ?> kg/pcs</div>
              <div class="ci-actions">
                <button class="ci-action-btn" onclick="saveForLater(<?= $item['id'] ?>)">
                  <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                  Simpan ke Wishlist
                </button>
                <span style="color:var(--gray-200)">|</span>
                <button class="ci-action-btn delete" onclick="hapusItem(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nama']) ?>')">
                  <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  Hapus
                </button>
              </div>
            </div>

            <!-- Qty -->
            <div class="ci-qty">
              <button class="qty-btn" onclick="ubahQty(<?= $item['id'] ?>, -1, <?= $item['stok'] ?>)">−</button>
              <input class="qty-num" type="number" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stok'] ?>"
                     onchange="setQty(<?= $item['id'] ?>, this.value, <?= $item['stok'] ?>)">
              <button class="qty-btn" onclick="ubahQty(<?= $item['id'] ?>, 1, <?= $item['stok'] ?>)">+</button>
            </div>

            <!-- Subtotal -->
            <div class="ci-subtotal">
              <?php if ($item['harga_asli']): ?>
              <div class="coret"><?= fmt($item['harga_asli'] * $item['qty']) ?></div>
              <?php endif; ?>
              <div class="harga" id="subtotal-<?= $item['id'] ?>"><?= fmt($subtotal_item) ?></div>
              <?php if ($hemat > 0): ?>
              <div class="hemat">Hemat <?= fmt($hemat) ?></div>
              <?php endif; ?>
            </div>
          </li>
          <?php endforeach; ?>
        </ul>

        <!-- Cart Footer -->
        <div class="cart-footer">
          <div class="cart-footer-left">
            Total berat: <strong><?= number_format($total_berat/1000,2) ?> kg</strong> &nbsp;|&nbsp;
            <strong><?= $total_item ?> item</strong> dalam keranjang
          </div>
          <a href="produk.php" class="btn-lanjut">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Lanjut Belanja
          </a>
        </div>
      </div>
    </div><!-- /left -->

    <!-- RIGHT: RINGKASAN -->
    <div class="sidebar-sticky">

      <!-- Kupon -->
      <div class="summary-card">
        <div class="summary-title">🎟️ Kode Promo / Kupon</div>
        <div class="kupon-body">
          <?php if ($promo_applied): ?>
          <div class="kupon-applied">
            <span>✓ <?= htmlspecialchars($promo_applied['label']) ?> diterapkan</span>
            <form method="POST" style="display:inline">
              <input type="hidden" name="kode_kupon" value="">
              <button type="submit" name="apply_kupon" class="btn-hapus-kupon">Hapus</button>
            </form>
          </div>
          <?php else: ?>
          <label>Masukkan kode kupon</label>
          <form method="POST" class="kupon-form">
            <input type="text" name="kode_kupon" class="kupon-input" placeholder="Contoh: HIKE10" maxlength="20" value="<?= htmlspecialchars($kode_input) ?>">
            <button type="submit" name="apply_kupon" class="btn-kupon">Pakai</button>
          </form>
          <?php if ($kode_input && !$promo_applied): ?>
          <div class="kupon-msg err">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Kode kupon tidak valid atau sudah kedaluwarsa.
          </div>
          <?php endif; ?>
          <div style="margin-top:10px;font-size:11.5px;color:var(--gray-400)">
            Coba: <span style="cursor:pointer;color:var(--green);font-weight:600" onclick="document.querySelector('.kupon-input').value='HIKE10'">HIKE10</span> |
            <span style="cursor:pointer;color:var(--green);font-weight:600" onclick="document.querySelector('.kupon-input').value='NEWMEMBER'">NEWMEMBER</span>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Ringkasan Harga -->
      <div class="summary-card">
        <div class="summary-title">📋 Ringkasan Pesanan</div>
        <div class="summary-body">
          <div class="summary-row">
            <span class="label">Subtotal (<?= $total_item ?> item)</span>
            <span class="val" id="summSubtotal"><?= fmt($subtotal) ?></span>
          </div>
          <?php if ($diskon > 0): ?>
          <div class="summary-row">
            <span class="label">Diskon Kupon</span>
            <span class="val red">− <?= fmt($diskon) ?></span>
          </div>
          <?php endif; ?>
          <div class="summary-row">
            <span class="label">Ongkos Kirim</span>
            <span class="val <?= $ongkir===0?'green':'' ?>"><?= $ongkir===0 ? 'Gratis ✓' : fmt($ongkir) ?></span>
          </div>
          <div class="summary-divider"></div>
          <div class="summary-total">
            <span class="tot-label">Total Pembayaran</span>
            <span class="tot-val" id="summTotal"><?= fmt($total) ?></span>
          </div>

          <!-- Gratis Ongkir Progress -->
          <?php if ($ongkir > 0):
            $pct = min(100, round($subtotal / 200000 * 100));
            $sisa = 200000 - $subtotal;
          ?>
          <div class="ongkir-progress">
            <div class="prog-label">Tambah <strong><?= fmt($sisa) ?></strong> lagi untuk gratis ongkir!</div>
            <div class="prog-track"><div class="prog-fill" style="width:<?= $pct ?>%"></div></div>
          </div>
          <?php else: ?>
          <div class="ongkir-progress">
            <div class="prog-label" style="color:var(--accent)">✓ Kamu mendapatkan <strong>Gratis Ongkir</strong>!</div>
            <div class="prog-track"><div class="prog-fill" style="width:100%"></div></div>
          </div>
          <?php endif; ?>
        </div>

        <a href="checkout.php" class="btn-checkout">
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          Lanjut ke Checkout
        </a>

        <div class="summary-guarantee">
          <div class="guar-item"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Transaksi aman & terlindungi</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>Pengiriman 1–3 hari kerja</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>Kembalikan dalam 7 hari</div>
          <div class="guar-item"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>100% Produk Original</div>
        </div>
      </div>

    </div><!-- /sidebar -->
  </div><!-- /cart-layout -->

  <!-- REKOMENDASI -->
  <div class="rekomendasi-section">
    <h2 class="rek-title">Produk yang Mungkin Kamu Suka</h2>
    <div class="rek-grid">
      <?php foreach ($produk_rekomendasi as $r): ?>
      <div class="rek-card">
        <a href="detail-produk.php?id=<?= $r['id'] ?>" class="rek-img">
          <?php if ($r['disc']): ?><span class="rek-disc">-<?= $r['disc'] ?>%</span><?php endif; ?>
          <img src="<?= $r['foto'] ?>" alt="<?= htmlspecialchars($r['nama']) ?>">
        </a>
        <div class="rek-body">
          <div class="rek-brand"><?= htmlspecialchars($r['brand']) ?></div>
          <div class="rek-name"><?= htmlspecialchars($r['nama']) ?></div>
          <div class="rek-foot">
            <div>
              <?php if ($r['harga_asli']): ?><div class="rek-coret"><?= fmt($r['harga_asli']) ?></div><?php endif; ?>
              <div class="rek-harga"><?= fmt($r['harga']) ?></div>
            </div>
            <button class="btn-add-rek" onclick="tambahRekomendasi('<?= htmlspecialchars($r['nama']) ?>')">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php endif; ?>
</div><!-- /page-wrap -->

<!-- Footer -->
<footer>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> HikeGear. Hak cipta dilindungi.</span>
    <span>Dibuat dengan ❤️ untuk para pendaki Indonesia</span>
  </div>
</footer>

<!-- Toast -->
<div class="toast" id="toast">
  <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastMsg"></span>
</div>

<script>
// ── Data harga dari PHP ───────────────────────────────────────────────────────
const itemPrices = {
  <?php foreach ($cart_items as $i): ?>
  <?= $i['id'] ?>: { harga: <?= $i['harga'] ?>, stok: <?= $i['stok'] ?> },
  <?php endforeach; ?>
};

// ── Toast ─────────────────────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.getElementById('toast');
  document.getElementById('toastMsg').textContent = msg;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
}

// ── Ubah QTY ─────────────────────────────────────────────────────────────────
function ubahQty(id, delta, maxStok) {
  const input = document.querySelector(`li[data-id="${id}"] .qty-num`);
  let val = parseInt(input.value) + delta;
  val = Math.max(1, Math.min(maxStok, val));
  input.value = val;
  updateSubtotal(id, val);
}
function setQty(id, val, maxStok) {
  val = Math.max(1, Math.min(maxStok, parseInt(val) || 1));
  document.querySelector(`li[data-id="${id}"] .qty-num`).value = val;
  updateSubtotal(id, val);
}
function updateSubtotal(id, qty) {
  const harga = itemPrices[id]?.harga || 0;
  const sub   = document.getElementById('subtotal-' + id);
  if (sub) sub.textContent = 'Rp' + (harga * qty).toLocaleString('id-ID');
  recalcTotal();
  // Di sini: fetch('/api/keranjang.php', {method:'POST', body: JSON.stringify({id, qty})})
}

// ── Recalculate total ─────────────────────────────────────────────────────────
function recalcTotal() {
  let subtotal = 0;
  document.querySelectorAll('.cart-item').forEach(row => {
    if (row.style.display === 'none') return;
    const id   = parseInt(row.dataset.id);
    const qty  = parseInt(row.querySelector('.qty-num')?.value || 1);
    const harga= itemPrices[id]?.harga || 0;
    subtotal  += harga * qty;
  });
  const ongkir   = subtotal >= 200000 ? 0 : 15000;
  const diskon   = <?= $diskon ?>;
  const total    = subtotal - diskon + ongkir;
  const subEl    = document.getElementById('summSubtotal');
  const totEl    = document.getElementById('summTotal');
  if (subEl) subEl.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
  if (totEl) totEl.textContent = 'Rp' + total.toLocaleString('id-ID');
}

// ── Hapus item ────────────────────────────────────────────────────────────────
function hapusItem(id, nama) {
  if (!confirm(`Hapus "${nama}" dari keranjang?`)) return;
  const row = document.querySelector(`li[data-id="${id}"]`);
  row.classList.add('removing');
  setTimeout(() => {
    row.remove();
    updateSelected();
    recalcTotal();
    updateCartCount();
    showToast(`"${nama}" dihapus dari keranjang.`);
    // fetch('/api/keranjang.php', {method:'DELETE', body: JSON.stringify({id})})
  }, 300);
}

// ── Simpan ke wishlist ────────────────────────────────────────────────────────
function saveForLater(id) {
  showToast('✓ Disimpan ke Wishlist!');
  // fetch('/api/wishlist.php', {method:'POST', body: JSON.stringify({produk_id: id})})
}

// ── Select all ────────────────────────────────────────────────────────────────
function toggleSelectAll(master) {
  document.querySelectorAll('.item-check').forEach(cb => cb.checked = master.checked);
  updateSelected();
}
function updateSelected() {
  const checks  = document.querySelectorAll('.item-check');
  const checked = [...checks].filter(c => c.checked);
  const info    = document.getElementById('selectedInfo');
  const btn     = document.getElementById('btnHapusTerpilih');
  const master  = document.getElementById('selectAll');

  info.textContent = checked.length > 0 ? `(${checked.length} dipilih)` : '';
  btn.classList.toggle('show', checked.length > 0);
  master.indeterminate = checked.length > 0 && checked.length < checks.length;
  master.checked = checks.length > 0 && checked.length === checks.length;
}
function hapusTerpilih() {
  const checked = [...document.querySelectorAll('.item-check:checked')];
  if (!checked.length) return;
  if (!confirm(`Hapus ${checked.length} item dari keranjang?`)) return;
  checked.forEach(cb => {
    const row = cb.closest('.cart-item');
    row.classList.add('removing');
    setTimeout(() => { row.remove(); recalcTotal(); updateCartCount(); }, 300);
  });
  setTimeout(() => updateSelected(), 350);
  showToast(`${checked.length} item berhasil dihapus.`);
}

// ── Update badge keranjang di header ─────────────────────────────────────────
function updateCartCount() {
  const items  = document.querySelectorAll('.cart-item');
  const badge  = document.getElementById('cartBadge');
  let total_qty = 0;
  items.forEach(row => {
    const qty = parseInt(row.querySelector('.qty-num')?.value || 0);
    total_qty += qty;
  });
  if (badge) badge.textContent = total_qty;
  const title = document.querySelector('.page-title .badge-count');
  if (title) title.textContent = items.length + ' item';
}

// ── Tambah rekomendasi ke keranjang ──────────────────────────────────────────
function tambahRekomendasi(nama) {
  showToast(`🛒 "${nama}" ditambahkan ke keranjang!`);
  const badge = document.getElementById('cartBadge');
  if (badge) badge.textContent = parseInt(badge.textContent||0)+1;
  // fetch('/api/keranjang.php', {method:'POST', body: JSON.stringify({produk_id, qty:1})})
}

// ── Scroll animation ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.cart-item, .rek-card');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => { e.target.style.opacity='1'; e.target.style.transform='translateY(0)'; }, i*40);
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.06 });
  items.forEach(el => {
    el.style.opacity='0'; el.style.transform='translateY(12px)';
    el.style.transition='opacity .35s ease, transform .35s ease';
    obs.observe(el);
  });
});
</script>
</body>
</html>