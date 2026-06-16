<?php
// ── Data Produk ──────────────────────────────────────────────────────────────
$all_products = [
  ["id"=>1,  "name"=>"Eiger Mountaineer Jacket",  "brand"=>"Eiger",      "kategori"=>"Jaket Outdoor",  "harga"=>1250000, "harga_coret"=>null,    "rating"=>5.0, "ulasan"=>120, "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&q=80"],
  ["id"=>2,  "name"=>"Consina Panjang Pria",       "brand"=>"Consina",    "kategori"=>"Celana Outdoor", "harga"=>650000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>86,  "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&q=80"],
  ["id"=>3,  "name"=>"Eiger Quick Dry Tee",        "brand"=>"Eiger",      "kategori"=>"Pakaian Pria",   "harga"=>199000,  "harga_coret"=>220000,  "rating"=>4.5, "ulasan"=>47,  "badge"=>"-10%",  "img"=>"https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80"],
  ["id"=>4,  "name"=>"Rei Venturer Hiking Boots",  "brand"=>"Rei",        "kategori"=>"Sepatu Gunung",  "harga"=>1350000, "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>68,  "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80"],
  ["id"=>5,  "name"=>"Consina Carrier 60L",        "brand"=>"Consina",    "kategori"=>"Tas Carrier",    "harga"=>1275000, "harga_coret"=>1500000, "rating"=>4.5, "ulasan"=>39,  "badge"=>"-15%",  "img"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&q=80"],
  ["id"=>6,  "name"=>"Eiger X-Trail 4P",           "brand"=>"Eiger",      "kategori"=>"Tenda",          "harga"=>2250000, "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>55,  "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&q=80"],
  ["id"=>7,  "name"=>"Rei Sleeping Bag Polar",     "brand"=>"Rei",        "kategori"=>"Sleeping Bag",   "harga"=>650000,  "harga_coret"=>null,    "rating"=>4.0, "ulasan"=>32,  "badge"=>null,    "img"=>"https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=400&q=80"],
  ["id"=>8,  "name"=>"Naturehike Alat Masak Set",  "brand"=>"Naturehike", "kategori"=>"Alat Masak",     "harga"=>550000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>21,  "badge"=>null,    "img"=>"https://images.unsplash.com/photo-1464146072230-91cabc968266?w=400&q=80"],
  ["id"=>9,  "name"=>"Eiger Headlamp 500 Lumens",  "brand"=>"Eiger",      "kategori"=>"Aksesoris",      "harga"=>325000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>19,  "badge"=>null,    "img"=>"https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=400&q=80"],
  ["id"=>10, "name"=>"Consina Winter Glove",       "brand"=>"Consina",    "kategori"=>"Aksesoris",      "harga"=>275000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>27,  "badge"=>null,    "img"=>"https://images.unsplash.com/photo-1601762603339-fd61e28b698a?w=400&q=80"],
  ["id"=>11, "name"=>"The North Face Rain Jacket",  "brand"=>"The North Face","kategori"=>"Jaket Outdoor","harga"=>1850000,"harga_coret"=>2200000,"rating"=>5.0, "ulasan"=>94,  "badge"=>"-16%",  "img"=>"https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&q=80"],
  ["id"=>12, "name"=>"Jack Wolfskin Trekking Pants","brand"=>"Jack Wolfskin","kategori"=>"Celana Outdoor","harga"=>890000, "harga_coret"=>null,    "rating"=>4.0, "ulasan"=>43,  "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&q=80"],
  ["id"=>13, "name"=>"Eiger Trail Running Shoes",  "brand"=>"Eiger",      "kategori"=>"Sepatu Gunung",  "harga"=>785000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>61,  "badge"=>null,    "img"=>"https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80"],
  ["id"=>14, "name"=>"Consina Trekking Pole",      "brand"=>"Consina",    "kategori"=>"Aksesoris",      "harga"=>320000,  "harga_coret"=>400000,  "rating"=>4.0, "ulasan"=>18,  "badge"=>"-20%",  "img"=>"https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?w=400&q=80"],
  ["id"=>15, "name"=>"Rei Ultralight Backpack 30L","brand"=>"Rei",        "kategori"=>"Tas Carrier",    "harga"=>975000,  "harga_coret"=>null,    "rating"=>4.5, "ulasan"=>52,  "badge"=>"BARU",  "img"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&q=80"],
];

$brands = ["Eiger"=>86,"Consina"=>74,"Rei"=>63,"The North Face"=>48,"Jack Wolfskin"=>35,"Naturehike"=>29,"Deuter"=>22,"Black Diamond"=>17];
$kategoris = ["Pakaian Pria"=>128,"Pakaian Wanita"=>96,"Jaket Outdoor"=>87,"Celana Outdoor"=>64,"Tas Carrier"=>53,"Sepatu Gunung"=>71,"Tenda"=>42,"Sleeping Bag"=>36,"Alat Masak"=>28,"Aksesoris"=>90];

// ── GET params ───────────────────────────────────────────────────────────────
$sort         = $_GET['sort']       ?? 'terbaru';
$search       = trim($_GET['q']     ?? '');
$cat_filter   = $_GET['kategori']   ?? '';
$min_price    = (int)($_GET['min']  ?? 0);
$max_price    = (int)($_GET['max']  ?? 5000000);
$brand_filter = isset($_GET['brand']) ? (array)$_GET['brand'] : [];
$page         = max(1, (int)($_GET['page'] ?? 1));
$per_page     = 10;
$cart_count   = 3;

// ── Filter ───────────────────────────────────────────────────────────────────
$products = array_filter($all_products, function($p) use ($search, $cat_filter, $min_price, $max_price, $brand_filter) {
    if ($search && stripos($p['name'].' '.$p['brand'], $search) === false) return false;
    if ($cat_filter && $p['kategori'] !== $cat_filter) return false;
    if ($p['harga'] < $min_price || $p['harga'] > $max_price) return false;
    if ($brand_filter && !in_array($p['brand'], $brand_filter)) return false;
    return true;
});
$products = array_values($products);

// ── Sort ─────────────────────────────────────────────────────────────────────
usort($products, function($a, $b) use ($sort) {
    return match($sort) {
        'termurah'  => $a['harga'] - $b['harga'],
        'termahal'  => $b['harga'] - $a['harga'],
        'rating'    => $b['rating'] <=> $a['rating'],
        'ulasan'    => $b['ulasan'] - $a['ulasan'],
        default     => $b['id'] - $a['id'],
    };
});

// ── Pagination ───────────────────────────────────────────────────────────────
$total     = count($products);
$pages     = max(1, ceil($total / $per_page));
$page      = min($page, $pages);
$offset    = ($page - 1) * $per_page;
$paged     = array_slice($products, $offset, $per_page);

function fmt($n) { return 'Rp'.number_format($n,0,',','.'); }
function stars($r) {
    $full = floor($r); $half = ($r - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half;
    return str_repeat('★',$full).str_repeat('½',$half).str_repeat('☆',$empty);
}
function qstr($extra=[]) {
    $p = $_GET; foreach($extra as $k=>$v) { if($v===null) unset($p[$k]); else $p[$k]=$v; }
    return $p ? '?'.http_build_query($p) : '?';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Produk – HikeGear</title>
<style>
/* ── Reset ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --red:#e74c3c;--orange:#e67e22;
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
.s-list li a{display:flex;align-items:center;justify-content:space-between;padding:9px 16px;font-size:13px;color:var(--gray-800);border-bottom:1px solid var(--gray-100);transition:all var(--tr)}
.s-list li a:hover,.s-list li a.active{background:var(--green-light);color:var(--green);padding-left:20px}
.s-list li:last-child a{border:none}
.s-count{font-size:11px;color:var(--gray-400);font-weight:600}
.s-list li a.active .s-count{color:var(--accent)}

/* price filter */
.price-body{padding:16px}
.price-labels{display:flex;justify-content:space-between;font-size:12px;color:var(--gray-600);margin-bottom:10px}
.price-range{display:flex;flex-direction:column;gap:8px}
.range-track{position:relative;height:4px;background:var(--gray-200);border-radius:2px;margin:8px 0}
.range-fill{position:absolute;height:4px;background:var(--accent);border-radius:2px}
input[type=range]{width:100%;-webkit-appearance:none;appearance:none;height:4px;background:transparent;outline:none;position:absolute;top:0;pointer-events:none}
input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:16px;height:16px;border-radius:50%;background:var(--accent);cursor:pointer;pointer-events:all;border:2px solid white;box-shadow:0 1px 4px rgba(0,0,0,.2)}
.price-inputs{display:flex;gap:8px;margin-top:10px}
.price-inputs input{flex:1;padding:7px 10px;border:1.5px solid var(--gray-200);border-radius:6px;font-size:12px;outline:none;font-family:inherit;text-align:center}
.price-inputs input:focus{border-color:var(--accent)}
.apply-btn{display:block;width:100%;margin-top:12px;padding:9px;background:var(--green-light);color:var(--green);border-radius:8px;font-size:13px;font-weight:700;text-align:center;transition:all var(--tr)}
.apply-btn:hover{background:var(--accent);color:white}

/* brand filter */
.brand-body{padding:12px 16px}
.brand-item{display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--gray-100)}
.brand-item:last-child{border:none}
.brand-check{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:var(--gray-800)}
.brand-check input[type=checkbox]{width:15px;height:15px;accent-color:var(--accent);cursor:pointer}
.brand-check.checked{color:var(--green);font-weight:600}
.see-more{display:block;text-align:center;padding:8px;font-size:12.5px;color:var(--accent);font-weight:600;cursor:pointer;transition:color var(--tr)}
.see-more:hover{color:var(--green)}

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

.result-info{font-size:12.5px;color:var(--gray-600);margin-bottom:14px}
.result-info strong{color:var(--gray-800);font-weight:700}

/* product grid */
.prod-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px}

/* product card */
.prod-card{background:white;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);border:1.5px solid var(--gray-200);transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr);position:relative;display:flex;flex-direction:column}
.prod-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--accent)}
.prod-img{height:180px;overflow:hidden;position:relative;background:var(--gray-100)}
.prod-img img{height:100%;transition:transform .35s ease}
.prod-card:hover .prod-img img{transform:scale(1.06)}
.badge{position:absolute;top:10px;left:10px;padding:3px 8px;border-radius:5px;font-size:11px;font-weight:800;letter-spacing:.3px;z-index:2}
.badge.baru{background:var(--accent);color:white}
.badge.disc{background:var(--red);color:white}
.wish-btn{position:absolute;top:8px;right:8px;width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,.9);display:flex;align-items:center;justify-content:center;z-index:2;transition:all var(--tr);border:1.5px solid var(--gray-200)}
.wish-btn:hover,.wish-btn.active{background:var(--red);border-color:var(--red)}
.wish-btn svg{width:14px;height:14px;stroke:var(--gray-600);fill:none;stroke-width:2;transition:stroke var(--tr)}
.wish-btn:hover svg,.wish-btn.active svg{stroke:white}
.prod-body{padding:12px;flex:1;display:flex;flex-direction:column}
.prod-brand{font-size:11px;color:var(--gray-400);font-weight:600;text-transform:uppercase;letter-spacing:.3px;margin-bottom:2px}
.prod-name{font-size:13px;font-weight:700;color:var(--gray-800);line-height:1.35;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.prod-stars{font-size:11px;color:#f0ad00;letter-spacing:1px;display:flex;align-items:center;gap:5px;margin-bottom:8px;flex-wrap:wrap}
.prod-stars span{font-size:11px;color:var(--gray-400);font-style:normal}
.prod-price{margin-top:auto}
.prod-price .coret{font-size:11.5px;color:var(--gray-400);text-decoration:line-through;margin-bottom:1px}
.prod-price .harga{font-size:15px;font-weight:800;color:var(--green-dark)}
.prod-footer{display:flex;align-items:center;justify-content:flex-end;padding:10px 12px 12px;margin-top:auto}
.cart-btn{display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;background:var(--green-light);transition:all var(--tr)}
.cart-btn:hover{background:var(--accent)}
.cart-btn svg{width:16px;height:16px;stroke:var(--green);fill:none;stroke-width:2.2;transition:stroke var(--tr)}
.cart-btn:hover svg{stroke:white}

/* empty state */
.empty-state{text-align:center;padding:60px 20px;color:var(--gray-600)}
.empty-state svg{width:56px;height:56px;stroke:var(--gray-400);fill:none;stroke-width:1.5;margin:0 auto 16px}
.empty-state h3{font-size:17px;font-weight:700;color:var(--gray-800);margin-bottom:8px}

/* pagination */
.pagination{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:28px;flex-wrap:wrap}
.pagination a,.pagination span{display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;font-size:13.5px;font-weight:600;border:1.5px solid var(--gray-200);background:white;color:var(--gray-800);transition:all var(--tr)}
.pagination a:hover{background:var(--green-light);border-color:var(--accent);color:var(--green)}
.pagination span.active{background:var(--accent);border-color:var(--accent);color:white}
.pagination span.dots{border:none;background:none;width:auto;color:var(--gray-400)}

/* footer */
footer{background:var(--green-dark);color:#aecfbe;padding:32px 24px 20px;margin-top:40px;font-size:12.5px}
.footer-inner{max-width:1340px;margin:0 auto;display:flex;gap:32px;flex-wrap:wrap;justify-content:space-between}
.footer-brand p{margin-top:10px;font-size:12px;max-width:220px;line-height:1.6;color:#7abf99}
.footer-col h4{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6dab89;margin-bottom:10px}
.footer-col a{display:block;margin-bottom:6px;color:#aecfbe;transition:color var(--tr)}
.footer-col a:hover{color:white}
.footer-bottom{max-width:1340px;margin:20px auto 0;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;font-size:11.5px;color:#5a8a72;flex-wrap:wrap;gap:8px}

/* responsive */
@media(max-width:1100px){.prod-grid{grid-template-columns:repeat(4,1fr)}}
@media(max-width:900px){.sidebar{display:none}.prod-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:600px){
  header{flex-wrap:wrap}
  .search-wrap{order:3;max-width:100%;flex:0 0 100%}
  .header-actions span{display:none}
  .prod-grid{grid-template-columns:repeat(2,1fr);gap:10px}
  .prod-img{height:140px}
  .topbar-right{display:none}
  .page-wrap{padding:14px}
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
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
    </div>
    <div class="logo-text">
      <strong>HIKEGEAR</strong>
      <small>Adventure Starts Here</small>
    </div>
  </a>
  <form class="search-wrap" method="GET" action="">
    <input type="text" name="q" placeholder="Cari produk pendakian..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></button>
  </form>
  <div class="header-actions">
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><span>Wishlist</span></a>
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Masuk / Daftar</span></a>
    <a class="haction" href="#" style="position:relative"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg><span>Keranjang</span><span class="cart-badge"><?= $cart_count ?></span></a>
  </div>
</header>

<!-- Nav -->
<nav>
  <button class="nav-cat-btn"><svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>KATEGORI</button>
  <div class="nav-links">
    <a href="#">Beranda</a>
    <a href="kategori.php">Kategori</a>
    <a href="produk.php" class="active">Produk</a>
    <a href="#">Promo</a>
    <a href="#">Artikel</a>
    <a href="#">Tentang Kami</a>
  </div>
</nav>

<!-- Page -->
<div class="page-wrap">

  <!-- Sidebar -->
  <aside class="sidebar">

    <!-- Kategori -->
    <div class="s-box">
      <div class="s-title">Kategori</div>
      <ul class="s-list">
        <li><a href="produk.php" <?= !$cat_filter ? 'class="active"' : '' ?>>
          <span>Semua Kategori</span>
          <span class="s-count">356</span></a></li>
        <?php foreach ($kategoris as $k => $n): ?>
        <li><a href="<?= qstr(['kategori'=>$k,'page'=>1]) ?>" <?= $cat_filter===$k ? 'class="active"' : '' ?>>
          <span><?= htmlspecialchars($k) ?></span>
          <span class="s-count"><?= $n ?></span></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Filter Harga -->
    <div class="s-box">
      <div class="s-title">Filter Harga</div>
      <div class="price-body">
        <div class="price-labels">
          <span id="lbl-min"><?= fmt($min_price) ?></span>
          <span id="lbl-max"><?= ($max_price >= 5000000) ? 'Rp5.000.000+' : fmt($max_price) ?></span>
        </div>
        <form method="GET" id="priceForm">
          <?php foreach($_GET as $k=>$v): if($k==='min'||$k==='max'||$k==='page') continue; ?>
            <?php if(is_array($v)): foreach($v as $vi): ?><input type="hidden" name="<?= htmlspecialchars($k) ?>[]" value="<?= htmlspecialchars($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>"><?php endif; ?>
          <?php endforeach; ?>
          <input type="hidden" name="page" value="1">
          <div class="range-track">
            <div class="range-fill" id="rangeFill"></div>
            <input type="range" id="rangeMin" name="min" min="0" max="5000000" step="50000" value="<?= $min_price ?>">
            <input type="range" id="rangeMax" name="max" min="0" max="5000000" step="50000" value="<?= $max_price ?>">
          </div>
          <button type="submit" class="apply-btn">Terapkan</button>
        </form>
      </div>
    </div>

    <!-- Merek -->
    <div class="s-box">
      <div class="s-title">Merek</div>
      <form method="GET" id="brandForm">
        <?php foreach($_GET as $k=>$v): if($k==='brand'||$k==='page') continue; ?>
          <?php if(is_array($v)): foreach($v as $vi): ?><input type="hidden" name="<?= htmlspecialchars($k) ?>[]" value="<?= htmlspecialchars($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>"><?php endif; ?>
        <?php endforeach; ?>
        <input type="hidden" name="page" value="1">
        <div class="brand-body">
          <?php $shown = 0; foreach ($brands as $b => $n): $shown++; $checked = in_array($b, $brand_filter); ?>
          <div class="brand-item" <?= $shown > 5 ? 'class="brand-extra" style="display:none"' : '' ?>>
            <label class="brand-check <?= $checked ? 'checked' : '' ?>">
              <input type="checkbox" name="brand[]" value="<?= htmlspecialchars($b) ?>" <?= $checked ? 'checked' : '' ?> onchange="document.getElementById('brandForm').submit()">
              <?= htmlspecialchars($b) ?>
            </label>
            <span class="s-count">(<?= $n ?>)</span>
          </div>
          <?php endforeach; ?>
        </div>
        <a class="see-more" id="seeMoreBrand">Lihat semua ▾</a>
      </form>
    </div>

  </aside>

  <!-- Main -->
  <main class="main">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="/">Beranda</a><span class="sep">›</span>
      <span class="current">Produk</span>
      <?php if ($cat_filter): ?><span class="sep">›</span><span class="current"><?= htmlspecialchars($cat_filter) ?></span><?php endif; ?>
      <?php if ($search): ?><span class="sep">›</span><span class="current">"<?= htmlspecialchars($search) ?>"</span><?php endif; ?>
    </div>

    <!-- Header -->
    <div class="content-header">
      <div>
        <h1>PRODUK</h1>
        <p>Temukan perlengkapan pendakian berkualitas untuk setiap petualanganmu.</p>
      </div>
      <div class="sort-wrap">
        <label>Urutkan:</label>
        <form method="GET" id="sortForm">
          <?php foreach($_GET as $k=>$v): if($k==='sort'||$k==='page') continue; ?>
            <?php if(is_array($v)): foreach($v as $vi): ?><input type="hidden" name="<?= htmlspecialchars($k) ?>[]" value="<?= htmlspecialchars($vi) ?>"><?php endforeach; else: ?><input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>"><?php endif; ?>
          <?php endforeach; ?>
          <input type="hidden" name="page" value="1">
          <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
            <option value="terbaru"  <?= $sort==='terbaru'  ? 'selected':'' ?>>Terbaru</option>
            <option value="terlaris" <?= $sort==='terlaris' ? 'selected':'' ?>>Terlaris</option>
            <option value="rating"   <?= $sort==='rating'   ? 'selected':'' ?>>Rating Tertinggi</option>
            <option value="termurah" <?= $sort==='termurah' ? 'selected':'' ?>>Harga Termurah</option>
            <option value="termahal" <?= $sort==='termahal' ? 'selected':'' ?>>Harga Termahal</option>
          </select>
        </form>
      </div>
    </div>

    <p class="result-info">Menampilkan <strong><?= $offset+1 ?>–<?= min($offset+$per_page, $total) ?></strong> dari <strong><?= $total ?></strong> produk<?= $cat_filter ? ' di <strong>'.htmlspecialchars($cat_filter).'</strong>' : '' ?><?= $search ? ' untuk "<strong>'.htmlspecialchars($search).'</strong>"' : '' ?></p>

    <!-- Grid -->
    <?php if (empty($paged)): ?>
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <h3>Produk tidak ditemukan</h3>
      <p>Coba ubah filter atau <a href="produk.php" style="color:var(--accent)">lihat semua produk</a>.</p>
    </div>
    <?php else: ?>
    <div class="prod-grid" id="prodGrid">
      <?php foreach ($paged as $p):
        $is_disc = !empty($p['harga_coret']);
        $badge = $p['badge'] ?? null;
        $badge_class = ($badge && str_starts_with($badge,'-')) ? 'disc' : 'baru';
        $full  = floor($p['rating']);
        $half  = ($p['rating'] - $full) >= 0.5 ? 1 : 0;
        $empty = 5 - $full - $half;
      ?>
      <div class="prod-card" data-id="<?= $p['id'] ?>">
        <div class="prod-img">
          <?php if ($badge): ?><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($badge) ?></span><?php endif; ?>
          <button class="wish-btn" onclick="toggleWish(this)" aria-label="Wishlist">
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
          <div class="prod-price">
            <?php if ($is_disc): ?><div class="coret"><?= fmt($p['harga_coret']) ?></div><?php endif; ?>
            <div class="harga"><?= fmt($p['harga']) ?></div>
          </div>
        </div>
        <div class="prod-footer">
          <button class="cart-btn" onclick="addToCart(this,<?= $p['id'] ?>)" aria-label="Tambah ke keranjang">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?><a href="<?= qstr(['page'=>$page-1]) ?>">‹</a><?php endif; ?>
      <?php
        $range = range(max(1,$page-2), min($pages,$page+2));
        if ($range[0] > 1) { echo '<a href="'.qstr(['page'=>1]).'">1</a>'; if ($range[0]>2) echo '<span class="dots">…</span>'; }
        foreach ($range as $pg):
          if ($pg===$page) echo '<span class="active">'.$pg.'</span>';
          else echo '<a href="'.qstr(['page'=>$pg]).'">'.$pg.'</a>';
        endforeach;
        if (end($range) < $pages) { if (end($range)<$pages-1) echo '<span class="dots">…</span>'; echo '<a href="'.qstr(['page'=>$pages]).'">'.$pages.'</a>'; }
      ?>
      <?php if ($page < $pages): ?><a href="<?= qstr(['page'=>$page+1]) ?>">›</a><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </main>
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
// ── Price Range Slider ──────────────────────────────────────────────────────
const rangeMin = document.getElementById('rangeMin');
const rangeMax = document.getElementById('rangeMax');
const fill     = document.getElementById('rangeFill');
const lblMin   = document.getElementById('lbl-min');
const lblMax   = document.getElementById('lbl-max');

function fmt(n) {
  return n >= 5000000 ? 'Rp5.000.000+' : 'Rp' + n.toLocaleString('id-ID');
}
function updateSlider() {
  const min = parseInt(rangeMin.value), max = parseInt(rangeMax.value);
  const pMin = min / 5000000 * 100, pMax = max / 5000000 * 100;
  fill.style.left  = pMin + '%';
  fill.style.width = (pMax - pMin) + '%';
  lblMin.textContent = fmt(min);
  lblMax.textContent = fmt(max);
}
if (rangeMin && rangeMax) {
  rangeMin.addEventListener('input', () => {
    if (parseInt(rangeMin.value) > parseInt(rangeMax.value) - 50000)
      rangeMin.value = parseInt(rangeMax.value) - 50000;
    updateSlider();
  });
  rangeMax.addEventListener('input', () => {
    if (parseInt(rangeMax.value) < parseInt(rangeMin.value) + 50000)
      rangeMax.value = parseInt(rangeMin.value) + 50000;
    updateSlider();
  });
  updateSlider();
}

// ── See More Brands ─────────────────────────────────────────────────────────
const seeMore = document.getElementById('seeMoreBrand');
let brandsExpanded = false;
seeMore?.addEventListener('click', () => {
  brandsExpanded = !brandsExpanded;
  document.querySelectorAll('.brand-extra').forEach(el => el.style.display = brandsExpanded ? '' : 'none');
  seeMore.textContent = brandsExpanded ? 'Lihat lebih sedikit ▴' : 'Lihat semua ▾';
});

// ── Wishlist toggle ──────────────────────────────────────────────────────────
function toggleWish(btn) {
  btn.classList.toggle('active');
  const svg = btn.querySelector('svg');
  svg.style.fill = btn.classList.contains('active') ? 'white' : 'none';
}

// ── Add to cart ──────────────────────────────────────────────────────────────
function addToCart(btn, id) {
  btn.style.background = 'var(--accent)';
  btn.querySelector('svg').style.stroke = 'white';
  const badge = document.querySelector('.cart-badge');
  if (badge) badge.textContent = parseInt(badge.textContent || 0) + 1;
  setTimeout(() => {
    btn.style.background = '';
    btn.querySelector('svg').style.stroke = '';
  }, 900);
}

// ── Card entrance animation ──────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.prod-card');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
        }, i * 40);
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.06 });
  cards.forEach(c => {
    c.style.opacity = '0';
    c.style.transform = 'translateY(16px)';
    c.style.transition = 'opacity .35s ease, transform .35s ease, box-shadow .22s ease, border-color .22s ease';
    obs.observe(c);
  });
});
</script>
</body>
</html>