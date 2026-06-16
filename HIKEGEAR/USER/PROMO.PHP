<?php
// ── Data Promo Produk ────────────────────────────────────────────────────────
$promo_products = [
  ["id"=>1,  "name"=>"Eiger Mountaineer Jacket", "brand"=>"Eiger",   "harga"=>1250000,"harga_asli"=>1560000,"disc"=>20,"rating"=>4.5,"ulasan"=>120,"img"=>"https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&q=80"],
  ["id"=>2,  "name"=>"Consina Panjang Pria",      "brand"=>"Consina","harga"=>650000, "harga_asli"=>765000, "disc"=>15,"rating"=>4.5,"ulasan"=>86, "img"=>"https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&q=80"],
  ["id"=>3,  "name"=>"Eiger Quick Dry Tee",       "brand"=>"Eiger",  "harga"=>199000, "harga_asli"=>265000, "disc"=>25,"rating"=>4.5,"ulasan"=>47, "img"=>"https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80"],
  ["id"=>4,  "name"=>"Rei Venturer Hiking Boots", "brand"=>"Rei",    "harga"=>1350000,"harga_asli"=>1950000,"disc"=>30,"rating"=>4.5,"ulasan"=>68, "img"=>"https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80"],
  ["id"=>5,  "name"=>"Consina Carrier 60L",       "brand"=>"Consina","harga"=>1275000,"harga_asli"=>1425000,"disc"=>10,"rating"=>4.5,"ulasan"=>39, "img"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&q=80"],
  ["id"=>6,  "name"=>"Eiger X-Trail 4P",          "brand"=>"Eiger",  "harga"=>2250000,"harga_asli"=>2612500,"disc"=>14,"rating"=>4.5,"ulasan"=>55, "img"=>"https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&q=80"],
  ["id"=>7,  "name"=>"Rei Sleeping Bag Polar",    "brand"=>"Rei",    "harga"=>650000, "harga_asli"=>850000, "disc"=>24,"rating"=>4.0,"ulasan"=>32, "img"=>"https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=400&q=80"],
  ["id"=>8,  "name"=>"TNF Rain Jacket Women",     "brand"=>"The North Face","harga"=>1850000,"harga_asli"=>2600000,"disc"=>29,"rating"=>5.0,"ulasan"=>94,"img"=>"https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&q=80"],
  ["id"=>9,  "name"=>"Naturehike Alat Masak Set", "brand"=>"Naturehike","harga"=>550000,"harga_asli"=>680000,"disc"=>19,"rating"=>4.5,"ulasan"=>21,"img"=>"https://images.unsplash.com/photo-1464146072230-91cabc968266?w=400&q=80"],
  ["id"=>10, "name"=>"Consina Winter Glove",      "brand"=>"Consina","harga"=>275000, "harga_asli"=>350000, "disc"=>21,"rating"=>4.5,"ulasan"=>27, "img"=>"https://images.unsplash.com/photo-1601762603339-fd61e28b698a?w=400&q=80"],
  ["id"=>11, "name"=>"Eiger Headlamp 500L",       "brand"=>"Eiger",  "harga"=>325000, "harga_asli"=>420000, "disc"=>23,"rating"=>4.5,"ulasan"=>19, "img"=>"https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=400&q=80"],
  ["id"=>12, "name"=>"Jack Wolfskin Trek Pants",  "brand"=>"Jack Wolfskin","harga"=>890000,"harga_asli"=>1100000,"disc"=>19,"rating"=>4.0,"ulasan"=>43,"img"=>"https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&q=80"],
];

$cat_filter = $_GET['promo_cat'] ?? 'semua';
$sort       = $_GET['sort']      ?? 'terbaru';
$cart_count = 3;

// sort
usort($promo_products, function($a,$b) use ($sort){
  return match($sort){
    'termurah' => $a['harga']-$b['harga'],
    'termahal' => $b['harga']-$a['harga'],
    'diskon'   => $b['disc']-$a['disc'],
    'rating'   => $b['rating']<=>$a['rating'],
    default    => $b['id']-$a['id'],
  };
});

function fmt($n){ return 'Rp'.number_format($n,0,',','.'); }
function hemat($a,$h){ return 'Hemat '.fmt($a-$h); }

// Flash sale end time: 2 hours 15 min 30 sec from page load (JS handles countdown)
$flash_end = time() + 2*3600 + 15*60 + 30;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Promo Spesial – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --red:#e74c3c;--orange:#e67e22;--yellow:#f39c12;
  --radius:10px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-md:0 4px 16px rgba(0,0,0,.10);--shadow-lg:0 8px 32px rgba(0,0,0,.14);
  --tr:.22s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
img{display:block;width:100%;object-fit:cover}
button{cursor:pointer;border:none;font-family:inherit}

/* ── Topbar ── */
.topbar{background:var(--green-dark);color:#c8e6d0;font-size:12.5px;padding:7px 24px;display:flex;align-items:center;justify-content:space-between}
.topbar-left{display:flex;align-items:center;gap:6px}
.topbar-right{display:flex;align-items:center;gap:20px}
.topbar-right a{display:flex;align-items:center;gap:5px;color:#c8e6d0;transition:color var(--tr)}
.topbar-right a:hover{color:#fff}

/* ── Header ── */
header{background:var(--white);border-bottom:1px solid var(--gray-200);padding:14px 24px;display:flex;align-items:center;gap:20px;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm)}
.logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
.logo-icon{width:42px;height:42px;background:var(--green);border-radius:8px;display:flex;align-items:center;justify-content:center}
.logo-icon svg{width:24px;height:24px;fill:none;stroke:white;stroke-width:2}
.logo-text strong{font-size:18px;font-weight:800;color:var(--green-dark);letter-spacing:-.3px}
.logo-text small{font-size:9px;color:var(--gray-600);letter-spacing:1.5px;text-transform:uppercase;display:block}
.search-wrap{flex:1;max-width:580px;display:flex}
.search-wrap input{flex:1;padding:10px 16px;border:1.5px solid var(--gray-200);border-right:none;border-radius:var(--radius) 0 0 var(--radius);font-size:14px;outline:none;transition:border-color var(--tr);background:var(--gray-50)}
.search-wrap input:focus{border-color:var(--accent);background:var(--white)}
.search-wrap button{background:var(--accent);color:white;padding:10px 18px;border-radius:0 var(--radius) var(--radius) 0;transition:background var(--tr)}
.search-wrap button:hover{background:var(--accent-hover)}
.search-wrap button svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:2.5}
.header-actions{display:flex;align-items:center;gap:6px;margin-left:auto}
.haction{display:flex;align-items:center;gap:6px;padding:8px 12px;border-radius:8px;color:var(--gray-800);font-size:13px;font-weight:500;transition:background var(--tr);position:relative}
.haction:hover{background:var(--green-light);color:var(--green)}
.haction svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8}
.cart-badge{position:absolute;top:4px;right:6px;background:#e74c3c;color:white;font-size:10px;font-weight:700;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white}

/* ── Nav ── */
nav{background:var(--white);border-bottom:1px solid var(--gray-200);padding:0 24px;display:flex;align-items:center;gap:4px}
.nav-cat-btn{display:flex;align-items:center;gap:8px;background:var(--green);color:white;padding:12px 16px;font-size:13.5px;font-weight:600;transition:background var(--tr);margin-right:8px}
.nav-cat-btn:hover{background:var(--green-mid)}
.nav-cat-btn svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2.5}
.nav-links{display:flex}
.nav-links a{padding:13px 16px;font-size:13.5px;color:var(--gray-800);border-bottom:2px solid transparent;transition:all var(--tr);font-weight:500}
.nav-links a:hover,.nav-links a.active{color:var(--accent);border-bottom-color:var(--accent)}

/* ── Layout ── */
.page-wrap{max-width:1340px;margin:0 auto;padding:24px;display:flex;gap:24px}

/* ── Sidebar ── */
.sidebar{width:248px;flex-shrink:0;display:flex;flex-direction:column;gap:14px}
.s-box{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden}
.s-title{padding:12px 16px;font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--gray-800);border-bottom:1px solid var(--gray-100)}
.s-list{list-style:none}
.s-list li a{display:flex;align-items:center;justify-content:space-between;padding:10px 16px;font-size:13px;color:var(--gray-800);border-bottom:1px solid var(--gray-100);transition:all var(--tr)}
.s-list li a:hover,.s-list li a.active{background:var(--green-light);color:var(--green);padding-left:20px}
.s-list li:last-child a{border:none}
.s-cat-icon{display:flex;align-items:center;gap:8px}
.s-count{font-size:11.5px;color:var(--gray-400);font-weight:700}
.s-list li a.active .s-count{color:var(--accent)}

/* free shipping box */
.free-ship{background:linear-gradient(135deg,var(--green-dark) 0%,var(--green-mid) 100%);color:white;border-radius:var(--radius);padding:18px;position:relative;overflow:hidden}
.free-ship::after{content:'';position:absolute;right:-20px;bottom:-20px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.08)}
.free-ship-icon{width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:10px}
.free-ship-icon svg{width:20px;height:20px;stroke:white;fill:none;stroke-width:2}
.free-ship h4{font-size:14px;font-weight:800;letter-spacing:.3px;margin-bottom:3px}
.free-ship p{font-size:12px;color:#a8d8bc}
.truck-wrap{text-align:right;margin-top:8px}
.truck-wrap svg{width:56px;height:56px;stroke:rgba(255,255,255,.3);fill:none;stroke-width:1.2}

/* flash sale box */
.flash-box{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:16px}
.flash-title{font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;color:var(--gray-800);margin-bottom:12px;display:flex;align-items:center;gap:6px}
.flash-title svg{width:14px;height:14px;stroke:var(--red);fill:none;stroke-width:2.5}
.countdown{display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:14px}
.cd-block{text-align:center;background:var(--gray-800);color:white;border-radius:8px;padding:8px 12px;min-width:52px}
.cd-block .num{font-size:22px;font-weight:800;line-height:1;display:block}
.cd-block .lbl{font-size:9px;text-transform:uppercase;letter-spacing:.8px;color:#aaa;display:block;margin-top:2px}
.cd-sep{font-size:20px;font-weight:800;color:var(--gray-400);margin-bottom:14px}
.flash-btn{display:block;text-align:center;padding:9px;background:var(--red);color:white;border-radius:8px;font-size:13px;font-weight:700;transition:background var(--tr)}
.flash-btn:hover{background:#c0392b}

/* ── Main ── */
.main{flex:1;min-width:0}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--gray-600);margin-bottom:16px}
.breadcrumb a{color:var(--gray-600);transition:color var(--tr)}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb .sep{color:var(--gray-400)}
.breadcrumb .current{color:var(--gray-800);font-weight:600}

.content-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:20px;gap:16px;flex-wrap:wrap}
.content-header h1{font-size:24px;font-weight:800;color:var(--green-dark);letter-spacing:-.5px}
.content-header p{font-size:13px;color:var(--gray-600);margin-top:3px}
.sort-wrap{display:flex;align-items:center;gap:8px;flex-shrink:0}
.sort-wrap label{font-size:13px;color:var(--gray-600);white-space:nowrap}
.sort-select{padding:8px 32px 8px 12px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px;font-family:inherit;color:var(--gray-800);background:white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235a7568' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;appearance:none;outline:none;cursor:pointer;transition:border-color var(--tr)}
.sort-select:focus{border-color:var(--accent)}

/* ── Banner ── */
.banners{display:grid;grid-template-columns:2fr 1fr 1fr;gap:14px;margin-bottom:28px}
.banner{border-radius:var(--radius);overflow:hidden;position:relative;cursor:pointer}
.banner img{height:100%;object-fit:cover;transition:transform .4s ease}
.banner:hover img{transform:scale(1.04)}
.banner-overlay{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:22px}
.banner-main .banner-overlay{background:linear-gradient(to right,rgba(0,0,0,.72) 50%,rgba(0,0,0,.2))}
.banner-sm  .banner-overlay{background:linear-gradient(to top,rgba(0,0,0,.75) 60%,rgba(0,0,0,.15))}
.banner-tag{display:inline-flex;align-items:center;background:#f39c12;color:white;font-size:11px;font-weight:800;padding:3px 10px;border-radius:20px;letter-spacing:.3px;margin-bottom:8px;width:fit-content}
.banner-main .b-label{font-size:13px;color:#aed9b8;font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px}
.banner-main .b-big{font-size:42px;font-weight:900;color:#f39c12;line-height:1;margin-bottom:4px}
.banner-main .b-sub{font-size:13.5px;font-weight:700;color:white;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px}
.banner-main .b-desc{font-size:12px;color:#b0cfb8;margin-bottom:14px;max-width:260px}
.banner-sm .b-title{font-size:15px;font-weight:800;color:white;margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px}
.banner-sm .b-subtitle{font-size:11.5px;color:#c8e6d0;margin-bottom:6px}
.banner-sm .b-price{font-size:20px;font-weight:900;color:#f39c12;margin-bottom:10px}
.banner-sm .b-sub2{font-size:11px;color:#aaa}
.banner-sm .b-pct{font-size:42px;font-weight:900;color:#f39c12;line-height:1}
.b-btn{display:inline-block;padding:9px 18px;border-radius:8px;font-size:13px;font-weight:700;transition:all var(--tr);border:2px solid white;color:white;width:fit-content}
.b-btn:hover{background:white;color:var(--green-dark)}
.b-btn-fill{background:var(--accent);border-color:var(--accent)}
.b-btn-fill:hover{background:var(--accent-hover);border-color:var(--accent-hover);color:white}
.banner-dots{position:absolute;bottom:12px;right:12px;display:flex;gap:5px}
.banner-dots span{width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.4)}
.banner-dots span.active{background:white}

/* product grid */
.prod-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}

/* product card */
.prod-card{background:white;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);border:1.5px solid var(--gray-200);transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr);position:relative;display:flex;flex-direction:column}
.prod-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--accent)}
.prod-img{height:160px;overflow:hidden;position:relative;background:var(--gray-100)}
.prod-img img{height:100%;transition:transform .35s ease}
.prod-card:hover .prod-img img{transform:scale(1.06)}
.disc-badge{position:absolute;top:8px;left:8px;background:var(--red);color:white;font-size:11px;font-weight:800;padding:3px 8px;border-radius:5px;z-index:2}
.promo-tag{position:absolute;bottom:8px;left:8px;background:var(--orange);color:white;font-size:10px;font-weight:700;padding:2px 7px;border-radius:4px;z-index:2;letter-spacing:.3px}
.wish-btn{position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.9);display:flex;align-items:center;justify-content:center;z-index:2;transition:all var(--tr);border:1.5px solid var(--gray-200)}
.wish-btn:hover,.wish-btn.active{background:var(--red);border-color:var(--red)}
.wish-btn svg{width:13px;height:13px;stroke:var(--gray-600);fill:none;stroke-width:2;transition:stroke var(--tr)}
.wish-btn:hover svg,.wish-btn.active svg{stroke:white}
.prod-body{padding:10px 12px;flex:1;display:flex;flex-direction:column}
.prod-brand{font-size:10.5px;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.3px;margin-bottom:2px}
.prod-name{font-size:12.5px;font-weight:700;color:var(--gray-800);line-height:1.35;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prod-price-row{display:flex;align-items:flex-end;justify-content:space-between;margin-top:auto}
.prod-prices .coret{font-size:11px;color:var(--gray-400);text-decoration:line-through}
.prod-prices .harga{font-size:14.5px;font-weight:800;color:var(--green-dark)}
.prod-stars{font-size:11px;color:#f0ad00;display:flex;align-items:center;gap:4px;margin-bottom:4px}
.prod-stars span{font-size:11px;color:var(--gray-400)}
.hemat-label{font-size:11px;color:var(--accent);font-weight:700;margin-top:3px}
.cart-btn{display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:var(--green-light);transition:all var(--tr);flex-shrink:0;margin-left:8px;align-self:flex-end}
.cart-btn:hover{background:var(--accent)}
.cart-btn svg{width:15px;height:15px;stroke:var(--green);fill:none;stroke-width:2.2;transition:stroke var(--tr)}
.cart-btn:hover svg{stroke:white}

/* trust bar */
.trust-bar{background:white;border-top:1px solid var(--gray-200);margin-top:36px;padding:20px 24px}
.trust-inner{max-width:1340px;margin:0 auto;display:flex;align-items:center;justify-content:space-around;flex-wrap:wrap;gap:16px}
.trust-item{display:flex;align-items:center;gap:12px}
.trust-icon{width:38px;height:38px;flex-shrink:0}
.trust-icon svg{width:38px;height:38px;stroke:var(--green);fill:none;stroke-width:1.5}
.trust-item h5{font-size:13px;font-weight:700;color:var(--gray-800)}
.trust-item p{font-size:11.5px;color:var(--gray-600)}

/* footer */
footer{background:var(--green-dark);color:#aecfbe;padding:32px 24px 20px;margin-top:0;font-size:12.5px}
.footer-inner{max-width:1340px;margin:0 auto;display:flex;gap:32px;flex-wrap:wrap;justify-content:space-between}
.footer-brand p{margin-top:10px;font-size:12px;max-width:220px;line-height:1.6;color:#7abf99}
.footer-col h4{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6dab89;margin-bottom:10px}
.footer-col a{display:block;margin-bottom:6px;color:#aecfbe;transition:color var(--tr)}
.footer-col a:hover{color:white}
.footer-bottom{max-width:1340px;margin:20px auto 0;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;font-size:11.5px;color:#5a8a72;flex-wrap:wrap;gap:8px}

/* responsive */
@media(max-width:1200px){.prod-grid{grid-template-columns:repeat(4,1fr)}}
@media(max-width:1000px){.banners{grid-template-columns:1fr 1fr}.banner-sm:last-child{display:none}.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:900px){.sidebar{display:none}.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){
  header{flex-wrap:wrap}.search-wrap{order:3;max-width:100%;flex:0 0 100%}
  .header-actions span{display:none}.topbar-right{display:none}.page-wrap{padding:14px}
  .banners{grid-template-columns:1fr}.prod-grid{grid-template-columns:repeat(2,1fr);gap:10px}
  .prod-img{height:130px}
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
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg> Lacak Pesanan</a>
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg> Bantuan</a>
    <a href="#"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Kontak Kami</a>
  </div>
</div>

<!-- Header -->
<header>
  <a href="/" class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg></div>
    <div class="logo-text"><strong>HIKEGEAR</strong><small>Adventure Starts Here</small></div>
  </a>
  <form class="search-wrap" method="GET" action="">
    <input type="text" name="q" placeholder="Cari produk pendakian...">
    <button type="submit"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></button>
  </form>
  <div class="header-actions">
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><span>Wishlist</span></a>
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Masuk / Daftar</span></a>
    <a class="haction" href="#" style="position:relative"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg><span>Keranjang</span><span class="cart-badge" id="cartBadge"><?= $cart_count ?></span></a>
  </div>
</header>

<!-- Nav -->
<nav>
  <button class="nav-cat-btn"><svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>KATEGORI</button>
  <div class="nav-links">
    <a href="#">Beranda</a>
    <a href="kategori.php">Kategori</a>
    <a href="produk.php">Produk</a>
    <a href="promo.php" class="active">Promo</a>
    <a href="#">Artikel</a>
    <a href="#">Tentang Kami</a>
  </div>
</nav>

<!-- Page -->
<div class="page-wrap">

  <!-- Sidebar -->
  <aside class="sidebar">

    <!-- Kategori Promo -->
    <div class="s-box">
      <div class="s-title">Kategori Promo</div>
      <ul class="s-list">
        <?php
        $promo_cats = [
          ['slug'=>'semua',    'label'=>'Semua Promo',    'icon'=>'tag',     'count'=>24],
          ['slug'=>'diskon',   'label'=>'Diskon Spesial', 'icon'=>'percent', 'count'=>12],
          ['slug'=>'bundling', 'label'=>'Bundling Hemat', 'icon'=>'package', 'count'=>6],
          ['slug'=>'clearance','label'=>'Clearance Sale', 'icon'=>'trash',   'count'=>4],
          ['slug'=>'flash',    'label'=>'Flash Sale',     'icon'=>'zap',     'count'=>2],
        ];
        $icons = [
          'tag'     => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
          'percent' => '<line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
          'package' => '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
          'trash'   => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>',
          'zap'     => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        ];
        foreach ($promo_cats as $c):
          $active = ($cat_filter === $c['slug']);
        ?>
        <li><a href="?promo_cat=<?= $c['slug'] ?>&sort=<?= urlencode($sort) ?>" <?= $active ? 'class="active"' : '' ?>>
          <span class="s-cat-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $icons[$c['icon']] ?></svg>
            <?= htmlspecialchars($c['label']) ?>
          </span>
          <span class="s-count"><?= $c['count'] ?></span>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Free Shipping Box -->
    <div class="free-ship">
      <div class="free-ship-icon">
        <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <h4>GRATIS ONGKIR</h4>
      <p>Min. belanja Rp200.000</p>
      <div class="truck-wrap">
        <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
    </div>

    <!-- Flash Sale Countdown -->
    <div class="flash-box">
      <div class="flash-title">
        <svg viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
        FLASH SALE BERAKHIR DALAM
      </div>
      <div class="countdown">
        <div class="cd-block"><span class="num" id="cd-jam">02</span><span class="lbl">JAM</span></div>
        <span class="cd-sep">:</span>
        <div class="cd-block"><span class="num" id="cd-menit">15</span><span class="lbl">MENIT</span></div>
        <span class="cd-sep">:</span>
        <div class="cd-block"><span class="num" id="cd-detik">30</span><span class="lbl">DETIK</span></div>
      </div>
      <a href="?promo_cat=flash" class="flash-btn">Lihat Semua Flash Sale</a>
    </div>

  </aside>

  <!-- Main -->
  <main class="main">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="/">Beranda</a><span class="sep">›</span>
      <span class="current">Promo</span>
    </div>

    <!-- Header -->
    <div class="content-header">
      <div>
        <h1>PROMO SPESIAL</h1>
        <p>Dapatkan penawaran terbaik untuk perlengkapan pendakian pilihanmu.</p>
      </div>
      <div class="sort-wrap">
        <label>Urutkan:</label>
        <form method="GET" id="sortForm">
          <input type="hidden" name="promo_cat" value="<?= htmlspecialchars($cat_filter) ?>">
          <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
            <option value="terbaru"  <?= $sort==='terbaru'  ?'selected':''?>>Terbaru</option>
            <option value="diskon"   <?= $sort==='diskon'   ?'selected':''?>>Diskon Terbesar</option>
            <option value="termurah" <?= $sort==='termurah' ?'selected':''?>>Harga Termurah</option>
            <option value="termahal" <?= $sort==='termahal' ?'selected':''?>>Harga Termahal</option>
            <option value="rating"   <?= $sort==='rating'   ?'selected':''?>>Rating Tertinggi</option>
          </select>
        </form>
      </div>
    </div>

    <!-- Banners -->
    <div class="banners">

      <!-- Banner 1 – Big Diskon -->
      <div class="banner banner-main" style="height:220px">
        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800&q=80" alt="Diskon 50%" style="height:220px">
        <div class="banner-overlay">
          <div class="banner-tag">⏰ Hingga 31 Mei 2024</div>
          <div class="b-label">Diskon Hingga</div>
          <div class="b-big">50%</div>
          <div class="b-sub">Untuk Produk Pilihan</div>
          <div class="b-desc">Perlengkapan terbaik untuk setiap petualanganmu</div>
          <a href="#" class="b-btn b-btn-fill">Belanja Sekarang</a>
        </div>
        <div class="banner-dots"><span class="active"></span><span></span><span></span></div>
      </div>

      <!-- Banner 2 – Bundling -->
      <div class="banner banner-sm" style="height:220px">
        <img src="https://images.unsplash.com/photo-1622260614153-03223fb72052?w=500&q=80" alt="Bundling" style="height:220px">
        <div class="banner-overlay">
          <div class="b-title">BUNDLING HEMAT</div>
          <div class="b-subtitle">Paket Carrier + Sleeping Bag ⛺🏕</div>
          <div style="font-size:11px;color:#aaa;margin-bottom:4px">Hemat hingga</div>
          <div class="b-price">Rp300.000</div>
          <a href="?promo_cat=bundling" class="b-btn">Lihat Bundling</a>
        </div>
      </div>

      <!-- Banner 3 – Clearance -->
      <div class="banner banner-sm" style="height:220px">
        <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=500&q=80" alt="Clearance" style="height:220px">
        <div class="banner-overlay">
          <div class="b-title">CLEARANCE SALE</div>
          <div class="b-subtitle">Diskon Hingga</div>
          <div class="b-pct">70%</div>
          <div class="b-sub2">Stok terbatas!</div>
          <a href="?promo_cat=clearance" class="b-btn" style="margin-top:8px">Lihat Semua</a>
        </div>
      </div>

    </div>

    <!-- Product Grid -->
    <div class="prod-grid" id="prodGrid">
      <?php foreach ($promo_products as $p):
        $full  = floor($p['rating']); $half = ($p['rating']-$full)>=.5?1:0; $empty=5-$full-$half;
      ?>
      <div class="prod-card">
        <div class="prod-img">
          <span class="disc-badge">-<?= $p['disc'] ?>%</span>
          <span class="promo-tag">PROMO</span>
          <button class="wish-btn" onclick="toggleWish(this)">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          </button>
          <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
        </div>
        <div class="prod-body">
          <div class="prod-brand"><?= htmlspecialchars($p['brand']) ?></div>
          <div class="prod-name"><?= htmlspecialchars($p['name']) ?></div>
          <div class="prod-stars">
            <?= str_repeat('<span style="color:#f0ad00">★</span>',$full) ?>
            <?= $half ? '<span style="color:#f0ad00;font-size:10px">●</span>' : '' ?>
            <?= str_repeat('<span style="color:#ccc">★</span>',$empty) ?>
            <span>(<?= $p['ulasan'] ?>)</span>
          </div>
          <div class="prod-price-row">
            <div>
              <div class="prod-prices">
                <div class="coret"><?= fmt($p['harga_asli']) ?></div>
                <div class="harga"><?= fmt($p['harga']) ?></div>
              </div>
              <div class="hemat-label"><?= hemat($p['harga_asli'],$p['harga']) ?></div>
            </div>
            <button class="cart-btn" onclick="addToCart(this)">
              <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </main>
</div>

<!-- Trust Bar -->
<div class="trust-bar">
  <div class="trust-inner">
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      <div><h5>Produk Original</h5><p>100% Original</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
      <div><h5>Pembayaran Aman</h5><p>Metode pembayaran terpercaya</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
      <div><h5>Garansi Resmi</h5><p>Barang bergaransi resmi</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
      <div><h5>Pengiriman Cepat</h5><p>1-3 hari sampai</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16"/></svg></div>
      <div><h5>Customer Service</h5><p>Siap melayani 24/7</p></div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo">
        <div class="logo-icon"><svg viewBox="0 0 24 24" style="width:24px;height:24px;fill:none;stroke:white;stroke-width:2"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg></div>
        <div class="logo-text"><strong style="color:white">HIKEGEAR</strong><small style="color:#6dab89">Adventure Starts Here</small></div>
      </div>
      <p>Perlengkapan pendakian berkualitas untuk petualangan terbaik kamu di alam bebas.</p>
    </div>
    <div class="footer-col"><h4>Belanja</h4><a href="#">Semua Produk</a><a href="#">Promo & Diskon</a><a href="#">Produk Baru</a><a href="#">Best Seller</a></div>
    <div class="footer-col"><h4>Bantuan</h4><a href="#">Cara Belanja</a><a href="#">Lacak Pesanan</a><a href="#">Pengembalian</a><a href="#">FAQ</a></div>
    <div class="footer-col"><h4>Perusahaan</h4><a href="#">Tentang Kami</a><a href="#">Blog & Artikel</a><a href="#">Karir</a><a href="#">Kontak</a></div>
  </div>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> HikeGear. Hak cipta dilindungi.</span>
    <span>Dibuat dengan ❤️ untuk para pendaki Indonesia</span>
  </div>
</footer>

<script>
// ── Countdown Flash Sale ─────────────────────────────────────────────────────
const endTime = Date.now() + (2*3600 + 15*60 + 30) * 1000;
function updateCountdown() {
  const diff = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
  const h = Math.floor(diff / 3600);
  const m = Math.floor((diff % 3600) / 60);
  const s = diff % 60;
  document.getElementById('cd-jam').textContent   = String(h).padStart(2,'0');
  document.getElementById('cd-menit').textContent = String(m).padStart(2,'0');
  document.getElementById('cd-detik').textContent = String(s).padStart(2,'0');
  if (diff > 0) requestAnimationFrame(() => setTimeout(updateCountdown, 1000));
}
updateCountdown();

// ── Wishlist ─────────────────────────────────────────────────────────────────
function toggleWish(btn) {
  btn.classList.toggle('active');
  btn.querySelector('svg').style.fill = btn.classList.contains('active') ? 'white' : 'none';
}

// ── Add to Cart ──────────────────────────────────────────────────────────────
function addToCart(btn) {
  btn.style.background = 'var(--accent)';
  btn.querySelector('svg').style.stroke = 'white';
  const badge = document.getElementById('cartBadge');
  if (badge) badge.textContent = parseInt(badge.textContent||0) + 1;
  setTimeout(() => { btn.style.background=''; btn.querySelector('svg').style.stroke=''; }, 900);
}

// ── Banner Dots Auto-slide ────────────────────────────────────────────────────
const dots = document.querySelectorAll('.banner-dots span');
let dotIdx = 0;
setInterval(() => {
  dots.forEach(d => d.classList.remove('active'));
  dotIdx = (dotIdx + 1) % dots.length;
  dots[dotIdx]?.classList.add('active');
}, 3000);

// ── Card entrance animation ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.prod-card');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e,i) => {
      if (e.isIntersecting) {
        setTimeout(() => { e.target.style.opacity='1'; e.target.style.transform='translateY(0)'; }, i*45);
        obs.unobserve(e.target);
      }
    });
  }, {threshold:0.06});
  cards.forEach(c => {
    c.style.opacity='0'; c.style.transform='translateY(16px)';
    c.style.transition='opacity .35s ease, transform .35s ease, box-shadow .22s ease, border-color .22s ease';
    obs.observe(c);
  });
});
</script>
</body>
</html>