<?php
// ── Data Artikel ─────────────────────────────────────────────────────────────
$articles = [
  ["id"=>1,  "title"=>"10 Tips Persiapan Sebelum Mendaki Gunung untuk Pemula",   "slug"=>"10-tips-persiapan-mendaki",        "cat"=>"Tips Pendakian",      "cat_slug"=>"tips",        "excerpt"=>"Persiapan yang matang adalah kunci pendakian yang aman dan menyenangkan.",                        "tanggal"=>"20 Mei 2024","views"=>"12.5K","komentar"=>24,"img"=>"https://images.unsplash.com/photo-1551632811-561732d1e306?w=600&q=80"],
  ["id"=>2,  "title"=>"Cara Memilih Sepatu Gunung yang Tepat dan Nyaman",        "slug"=>"cara-memilih-sepatu-gunung",        "cat"=>"Gear Review",         "cat_slug"=>"gear",        "excerpt"=>"Tips memilih sepatu gunung sesuai medan, ukuran, dan bahan terbaik.",                           "tanggal"=>"18 Mei 2024","views"=>"8.7K", "komentar"=>18,"img"=>"https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80"],
  ["id"=>3,  "title"=>"Perlengkapan Wajib untuk Pendakian Pemula",               "slug"=>"perlengkapan-wajib-pemula",         "cat"=>"Tips Pendakian",      "cat_slug"=>"tips",        "excerpt"=>"Daftar perlengkapan penting yang wajib dibawa agar pendakian lebih aman.",                     "tanggal"=>"15 Mei 2024","views"=>"7.3K", "komentar"=>16,"img"=>"https://images.unsplash.com/photo-1622260614153-03223fb72052?w=600&q=80"],
  ["id"=>4,  "title"=>"5 Gunung Terbaik untuk Pemula di Indonesia",              "slug"=>"5-gunung-terbaik-pemula",           "cat"=>"Destinasi",           "cat_slug"=>"destinasi",   "excerpt"=>"Rekomendasi gunung dengan jalur bersahabat dan pemandangan indah.",                            "tanggal"=>"12 Mei 2024","views"=>"9.1K", "komentar"=>22,"img"=>"https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=80"],
  ["id"=>5,  "title"=>"Tips Mendaki Gunung Saat Musim Hujan",                   "slug"=>"tips-mendaki-musim-hujan",          "cat"=>"Tips Pendakian",      "cat_slug"=>"tips",        "excerpt"=>"Ketahui cara menghadapi hujan, menjaga peralatan tetap kering, dan tetap aman.",               "tanggal"=>"10 Mei 2024","views"=>"6.1K", "komentar"=>14,"img"=>"https://images.unsplash.com/photo-1530569673472-307dc017a82d?w=600&q=80"],
  ["id"=>6,  "title"=>"Cara Mendirikan Tenda yang Aman dan Nyaman",              "slug"=>"cara-mendirikan-tenda",             "cat"=>"Tips Pendakian",      "cat_slug"=>"tips",        "excerpt"=>"Panduan mendirikan tenda dengan benar di berbagai medan pendakian.",                           "tanggal"=>"8 Mei 2024", "views"=>"5.2K", "komentar"=>11,"img"=>"https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=600&q=80"],
  ["id"=>7,  "title"=>"Etika Pendakian: Menjaga Alam dan Sesama Pendaki",        "slug"=>"etika-pendakian",                   "cat"=>"Keselamatan",         "cat_slug"=>"keselamatan", "excerpt"=>"Pentingnya etika dan tanggung jawab saat mendaki gunung.",                                     "tanggal"=>"5 Mei 2024", "views"=>"4.8K", "komentar"=>9, "img"=>"https://images.unsplash.com/photo-1522163182402-834f871fd851?w=600&q=80"],
  ["id"=>8,  "title"=>"Pengalaman Overnight di Gunung Prau",                    "slug"=>"overnight-gunung-prau",             "cat"=>"Cerita Petualangan",  "cat_slug"=>"cerita",      "excerpt"=>"Cerita perjalanan semalam di Gunung Prau yang penuh kesan dan pelajaran.",                     "tanggal"=>"3 Mei 2024", "views"=>"3.6K", "komentar"=>7, "img"=>"https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=600&q=80"],
  ["id"=>9,  "title"=>"Review Lengkap: Eiger Mountaineer Jacket 2024",           "slug"=>"review-eiger-mountaineer-jacket",   "cat"=>"Gear Review",         "cat_slug"=>"gear",        "excerpt"=>"Review mendalam jaket pendakian Eiger terbaru dari segi material, fitur, dan harga.",          "tanggal"=>"1 Mei 2024", "views"=>"7.9K", "komentar"=>31,"img"=>"https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=600&q=80"],
  ["id"=>10, "title"=>"Manfaat Mendaki Gunung untuk Kesehatan Fisik dan Mental", "slug"=>"manfaat-mendaki-kesehatan",         "cat"=>"Tips Pendakian",      "cat_slug"=>"tips",        "excerpt"=>"Ternyata mendaki gunung membawa banyak manfaat luar biasa bagi kesehatan.",                   "tanggal"=>"28 Apr 2024","views"=>"5.8K", "komentar"=>19,"img"=>"https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&q=80"],
  ["id"=>11, "title"=>"Panduan Navigasi: Menggunakan Peta dan Kompas di Alam",   "slug"=>"panduan-navigasi-peta-kompas",      "cat"=>"Persiapan Pendakian", "cat_slug"=>"persiapan",   "excerpt"=>"Cara membaca peta topografi dan menggunakan kompas untuk navigasi di hutan.",                 "tanggal"=>"25 Apr 2024","views"=>"4.2K", "komentar"=>8, "img"=>"https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=80"],
  ["id"=>12, "title"=>"5 Event Pendakian Bersama Terbesar di Indonesia 2024",    "slug"=>"event-pendakian-bersama-2024",      "cat"=>"Event & Komunitas",   "cat_slug"=>"event",       "excerpt"=>"Daftar event hiking dan komunitas pendaki yang sayang untuk dilewatkan tahun ini.",           "tanggal"=>"22 Apr 2024","views"=>"3.1K", "komentar"=>5, "img"=>"https://images.unsplash.com/photo-1519681393784-d120267933ba?w=600&q=80"],
];

$cat_list = [
  ['slug'=>'semua',       'label'=>'Semua Artikel',      'icon'=>'grid',    'count'=>count($articles)],
  ['slug'=>'tips',        'label'=>'Tips Pendakian',      'icon'=>'compass', 'count'=>5],
  ['slug'=>'gear',        'label'=>'Gear Review',         'icon'=>'star',    'count'=>2],
  ['slug'=>'destinasi',   'label'=>'Destinasi',           'icon'=>'map-pin', 'count'=>1],
  ['slug'=>'keselamatan', 'label'=>'Keselamatan',         'icon'=>'shield',  'count'=>1],
  ['slug'=>'persiapan',   'label'=>'Persiapan Pendakian', 'icon'=>'list',    'count'=>1],
  ['slug'=>'cerita',      'label'=>'Cerita Petualangan',  'icon'=>'book',    'count'=>1],
  ['slug'=>'event',       'label'=>'Event & Komunitas',   'icon'=>'users',   'count'=>1],
];

$cat_icons = [
  'grid'    => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>',
  'compass' => '<circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>',
  'star'    => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
  'map-pin' => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
  'shield'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
  'list'    => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
  'book'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
  'users'   => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
];

$cat_colors = [
  'Tips Pendakian'      => '#27ae60',
  'Gear Review'         => '#2980b9',
  'Destinasi'           => '#8e44ad',
  'Keselamatan'         => '#e74c3c',
  'Persiapan Pendakian' => '#e67e22',
  'Cerita Petualangan'  => '#16a085',
  'Event & Komunitas'   => '#c0392b',
];

// GET params
$cat_filter = $_GET['cat']  ?? 'semua';
$sort       = $_GET['sort'] ?? 'terbaru';
$search     = trim($_GET['q'] ?? '');
$page       = max(1,(int)($_GET['page'] ?? 1));
$per_page   = 8;
$cart_count = 3;

// popular (top 5 by views)
$popular = $articles;
usort($popular, fn($a,$b) => (int)str_replace(['K','.'],['',' '],$b['views']) - (int)str_replace(['K','.'],['',' '],$a['views']));
$popular = array_slice($popular, 0, 5);

// filter
$filtered = array_values(array_filter($articles, function($a) use ($cat_filter,$search){
  if ($cat_filter !== 'semua' && $a['cat_slug'] !== $cat_filter) return false;
  if ($search && stripos($a['title'].' '.$a['excerpt'], $search) === false) return false;
  return true;
}));

// sort
usort($filtered, function($a,$b) use ($sort){
  return match($sort){
    'terpopuler' => (int)str_replace(['K','.'],['',' '],$b['views'])-(int)str_replace(['K','.'],['',' '],$a['views']),
    'komentar'   => $b['komentar']-$a['komentar'],
    default      => $b['id']-$a['id'],
  };
});

// paginate
$total  = count($filtered);
$pages  = max(1,ceil($total/$per_page));
$page   = min($page,$pages);
$offset = ($page-1)*$per_page;
$paged  = array_slice($filtered, $offset, $per_page);

function qstr($extra=[]){
  $p=$_GET; foreach($extra as $k=>$v){ if($v===null) unset($p[$k]); else $p[$k]=$v; }
  return $p ? '?'.http_build_query($p) : '?';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Artikel Pendakian – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --radius:10px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-lg:0 8px 32px rgba(0,0,0,.14);
  --tr:.22s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
img{display:block;width:100%;object-fit:cover}
button{cursor:pointer;border:none;font-family:inherit}

/* Topbar */
.topbar{background:var(--green-dark);color:#c8e6d0;font-size:12.5px;padding:7px 24px;display:flex;align-items:center;justify-content:space-between}
.topbar-left{display:flex;align-items:center;gap:6px}
.topbar-right{display:flex;align-items:center;gap:20px}
.topbar-right a{display:flex;align-items:center;gap:5px;color:#c8e6d0;transition:color var(--tr)}
.topbar-right a:hover{color:#fff}

/* Header */
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

/* Nav */
nav{background:var(--white);border-bottom:1px solid var(--gray-200);padding:0 24px;display:flex;align-items:center;gap:4px}
.nav-cat-btn{display:flex;align-items:center;gap:8px;background:var(--green);color:white;padding:12px 16px;font-size:13.5px;font-weight:600;transition:background var(--tr);margin-right:8px}
.nav-cat-btn:hover{background:var(--green-mid)}
.nav-cat-btn svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2.5}
.nav-links{display:flex}
.nav-links a{padding:13px 16px;font-size:13.5px;color:var(--gray-800);border-bottom:2px solid transparent;transition:all var(--tr);font-weight:500}
.nav-links a:hover,.nav-links a.active{color:var(--accent);border-bottom-color:var(--accent)}

/* Layout */
.page-wrap{max-width:1340px;margin:0 auto;padding:24px;display:flex;gap:24px}

/* Sidebar */
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

/* popular */
.pop-list{list-style:none}
.pop-item{display:flex;align-items:flex-start;gap:10px;padding:10px 16px;border-bottom:1px solid var(--gray-100);transition:background var(--tr);cursor:pointer}
.pop-item:hover{background:var(--green-light)}
.pop-item:last-child{border:none}
.pop-num{width:22px;height:22px;border-radius:6px;background:var(--green-light);color:var(--green);font-size:11px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;transition:all var(--tr)}
.pop-item:nth-child(1) .pop-num,.pop-item:nth-child(2) .pop-num,.pop-item:nth-child(3) .pop-num{background:var(--accent);color:white}
.pop-text h5{font-size:12.5px;font-weight:600;color:var(--gray-800);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color var(--tr)}
.pop-item:hover .pop-text h5{color:var(--green)}
.pop-views{font-size:11px;color:var(--gray-400);margin-top:3px;display:flex;align-items:center;gap:3px}
.pop-views svg{width:11px;height:11px;stroke:currentColor;fill:none;stroke-width:2}

/* newsletter */
.newsletter-box{background:linear-gradient(135deg,var(--green-dark),var(--green-mid));border-radius:var(--radius);padding:18px;color:white}
.nl-icon{width:36px;height:36px;background:rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;margin-bottom:10px}
.nl-icon svg{width:18px;height:18px;stroke:white;fill:none;stroke-width:2}
.newsletter-box p{font-size:12px;color:#a8d8bc;margin-bottom:12px;line-height:1.5}
.nl-input{width:100%;padding:8px 12px;border:none;border-radius:7px;font-size:12.5px;outline:none;margin-bottom:8px;font-family:inherit}
.nl-btn{display:block;width:100%;padding:9px;background:var(--accent);color:white;border-radius:8px;font-size:13px;font-weight:700;text-align:center;transition:background var(--tr);border:none;cursor:pointer;font-family:inherit}
.nl-btn:hover{background:var(--accent-hover)}

/* Main */
.main{flex:1;min-width:0}
.breadcrumb{display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--gray-600);margin-bottom:16px}
.breadcrumb a{color:var(--gray-600);transition:color var(--tr)}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb .sep{color:var(--gray-400)}
.breadcrumb .current{color:var(--gray-800);font-weight:600}

.content-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:20px;gap:16px;flex-wrap:wrap}
.content-header h1{font-size:24px;font-weight:800;color:var(--green-dark);letter-spacing:-.5px}
.content-header p{font-size:13px;color:var(--gray-600);margin-top:3px}
.header-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap}

.art-search{display:flex;border:1.5px solid var(--gray-200);border-radius:8px;overflow:hidden;background:white}
.art-search input{padding:8px 12px;border:none;font-size:13px;outline:none;font-family:inherit;min-width:160px}
.art-search button{padding:8px 12px;background:transparent;border-left:1px solid var(--gray-200);color:var(--gray-600);transition:all var(--tr)}
.art-search button:hover{background:var(--green-light);color:var(--green)}
.art-search button svg{width:15px;height:15px;stroke:currentColor;fill:none;stroke-width:2.5;display:block}

.sort-wrap{display:flex;align-items:center;gap:8px}
.sort-wrap label{font-size:13px;color:var(--gray-600);white-space:nowrap}
.sort-select{padding:8px 32px 8px 12px;border:1.5px solid var(--gray-200);border-radius:8px;font-size:13px;font-family:inherit;color:var(--gray-800);background:white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235a7568' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;appearance:none;outline:none;cursor:pointer;transition:border-color var(--tr)}
.sort-select:focus{border-color:var(--accent)}

.search-info{background:var(--green-light);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:var(--green);display:flex;align-items:center;justify-content:space-between}
.search-clear{color:var(--green);font-size:12px;font-weight:600;text-decoration:underline;cursor:pointer}
.result-info{font-size:12.5px;color:var(--gray-600);margin-bottom:14px}
.result-info strong{color:var(--gray-800);font-weight:700}

/* article grid */
.art-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.art-card{background:white;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);border:1.5px solid var(--gray-200);transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr);display:flex;flex-direction:column}
.art-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--accent)}
.art-img{height:175px;overflow:hidden;position:relative}
.art-img img{height:100%;transition:transform .35s ease}
.art-card:hover .art-img img{transform:scale(1.06)}
.art-cat-badge{position:absolute;top:10px;left:10px;padding:4px 10px;border-radius:5px;font-size:10.5px;font-weight:800;letter-spacing:.3px;z-index:2;text-transform:uppercase;color:white}
.art-body{padding:14px;flex:1;display:flex;flex-direction:column}
.art-title{font-size:13.5px;font-weight:700;color:var(--gray-800);line-height:1.4;margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;transition:color var(--tr)}
.art-card:hover .art-title{color:var(--green)}
.art-excerpt{font-size:12px;color:var(--gray-600);line-height:1.55;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
.art-meta{display:flex;align-items:center;gap:12px;margin-top:12px;padding-top:10px;border-top:1px solid var(--gray-100);flex-wrap:wrap}
.art-meta-item{display:flex;align-items:center;gap:4px;font-size:11px;color:var(--gray-400)}
.art-meta-item svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2}

/* empty */
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
@media(max-width:1100px){.art-grid{grid-template-columns:repeat(3,1fr)}}
@media(max-width:900px){.sidebar{display:none}.art-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){
  header{flex-wrap:wrap}.search-wrap{order:3;max-width:100%;flex:0 0 100%}
  .header-actions span{display:none}.topbar-right{display:none}.page-wrap{padding:14px}
  .art-grid{grid-template-columns:repeat(2,1fr);gap:10px}.art-img{height:130px}
  .content-header{flex-direction:column;align-items:flex-start}.header-right{width:100%}
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
    <input type="hidden" name="cat" value="<?= htmlspecialchars($cat_filter) ?>">
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="text" name="q" placeholder="Cari artikel pendakian, tips, gear review, dll..." value="<?= htmlspecialchars($search) ?>">
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
    <a href="produk.php">Produk</a>
    <a href="promo.php">Promo</a>
    <a href="artikel.php" class="active">Artikel</a>
    <a href="#">Tentang Kami</a>
  </div>
</nav>

<!-- Page -->
<div class="page-wrap">

  <!-- Sidebar -->
  <aside class="sidebar">

    <!-- Kategori Artikel -->
    <div class="s-box">
      <div class="s-title">Kategori Artikel</div>
      <ul class="s-list">
        <?php foreach ($cat_list as $c):
          $active = ($cat_filter === $c['slug']);
        ?>
        <li><a href="<?= qstr(['cat'=>$c['slug'],'page'=>1]) ?>" <?= $active?'class="active"':'' ?>>
          <span class="s-cat-icon">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $cat_icons[$c['icon']] ?></svg>
            <?= htmlspecialchars($c['label']) ?>
          </span>
          <span class="s-count"><?= $c['count'] ?></span>
        </a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Artikel Populer -->
    <div class="s-box">
      <div class="s-title">Artikel Populer</div>
      <ul class="pop-list">
        <?php foreach ($popular as $i => $a): ?>
        <li class="pop-item" onclick="location.href='artikel-detail.php?slug=<?= urlencode($a['slug']) ?>'">
          <span class="pop-num"><?= $i+1 ?></span>
          <div class="pop-text">
            <h5><?= htmlspecialchars($a['title']) ?></h5>
            <div class="pop-views">
              <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <?= $a['views'] ?>
            </div>
          </div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Newsletter -->
    <div class="newsletter-box">
      <div class="nl-icon">
        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      </div>
      <p>Dapatkan artikel terbaru dan promo menarik!</p>
      <input type="email" class="nl-input" id="nlEmail" placeholder="Email kamu...">
      <button class="nl-btn" id="nlBtn" onclick="submitNL()">Berlangganan Newsletter</button>
    </div>

  </aside>

  <!-- Main -->
  <main class="main">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="/">Beranda</a><span class="sep">›</span>
      <span class="current">Artikel</span>
      <?php
        $cat_label_map = array_column($cat_list,'label','slug');
        if ($cat_filter !== 'semua'):
      ?>
        <span class="sep">›</span>
        <span class="current"><?= htmlspecialchars($cat_label_map[$cat_filter] ?? $cat_filter) ?></span>
      <?php endif; ?>
    </div>

    <!-- Content Header -->
    <div class="content-header">
      <div>
        <h1>ARTIKEL PENDAKIAN</h1>
        <p>Tips, panduan, review, dan inspirasi untuk setiap petualanganmu.</p>
      </div>
      <div class="header-right">
        <form method="GET">
          <input type="hidden" name="cat"  value="<?= htmlspecialchars($cat_filter) ?>">
          <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
          <input type="hidden" name="page" value="1">
          <div class="art-search">
            <input type="text" name="q" placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></button>
          </div>
        </form>
        <form method="GET" id="sortForm">
          <input type="hidden" name="cat" value="<?= htmlspecialchars($cat_filter) ?>">
          <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
          <input type="hidden" name="page" value="1">
          <div class="sort-wrap">
            <label>Urutkan:</label>
            <select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
              <option value="terbaru"    <?= $sort==='terbaru'    ?'selected':''?>>Terbaru</option>
              <option value="terpopuler" <?= $sort==='terpopuler' ?'selected':''?>>Terpopuler</option>
              <option value="komentar"   <?= $sort==='komentar'   ?'selected':''?>>Komentar Terbanyak</option>
            </select>
          </div>
        </form>
      </div>
    </div>

    <!-- Search info -->
    <?php if ($search): ?>
    <div class="search-info">
      <span>Hasil: <strong>"<?= htmlspecialchars($search) ?>"</strong> — <?= $total ?> artikel ditemukan</span>
      <a href="<?= qstr(['q'=>null,'page'=>1]) ?>" class="search-clear">Hapus filter</a>
    </div>
    <?php endif; ?>

    <p class="result-info">Menampilkan <strong><?= $total>0 ? $offset+1 : 0 ?>–<?= min($offset+$per_page,$total) ?></strong> dari <strong><?= $total ?></strong> artikel</p>

    <!-- Grid -->
    <?php if (empty($paged)): ?>
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
      <h3>Artikel tidak ditemukan</h3>
      <p>Coba kata kunci lain atau <a href="artikel.php" style="color:var(--accent)">lihat semua artikel</a>.</p>
    </div>
    <?php else: ?>
    <div class="art-grid" id="artGrid">
      <?php foreach ($paged as $a):
        $color = $cat_colors[$a['cat']] ?? '#27ae60';
      ?>
      <a class="art-card" href="artikel-detail.php?slug=<?= urlencode($a['slug']) ?>">
        <div class="art-img">
          <span class="art-cat-badge" style="background:<?= $color ?>"><?= htmlspecialchars(strtoupper($a['cat'])) ?></span>
          <img src="<?= $a['img'] ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy">
        </div>
        <div class="art-body">
          <h3 class="art-title"><?= htmlspecialchars($a['title']) ?></h3>
          <p class="art-excerpt"><?= htmlspecialchars($a['excerpt']) ?></p>
          <div class="art-meta">
            <span class="art-meta-item">
              <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <?= $a['tanggal'] ?>
            </span>
            <span class="art-meta-item">
              <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <?= $a['views'] ?>
            </span>
            <span class="art-meta-item">
              <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <?= $a['komentar'] ?>
            </span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>

    <?php if ($pages > 1): ?>
    <div class="pagination">
      <?php if ($page>1): ?><a href="<?= qstr(['page'=>$page-1]) ?>">‹</a><?php endif; ?>
      <?php
        $range = range(max(1,$page-2), min($pages,$page+2));
        if ($range[0]>1){ echo '<a href="'.qstr(['page'=>1]).'">1</a>'; if($range[0]>2) echo '<span class="dots">…</span>'; }
        foreach($range as $pg){ if($pg===$page) echo '<span class="active">'.$pg.'</span>'; else echo '<a href="'.qstr(['page'=>$pg]).'">'.$pg.'</a>'; }
        $last = end($range); if($last<$pages){ if($last<$pages-1) echo '<span class="dots">…</span>'; echo '<a href="'.qstr(['page'=>$pages]).'">'.$pages.'</a>'; }
      ?>
      <?php if ($page<$pages): ?><a href="<?= qstr(['page'=>$page+1]) ?>">›</a><?php endif; ?>
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
    <div class="footer-col"><h4>Artikel</h4><a href="#">Tips Pendakian</a><a href="#">Gear Review</a><a href="#">Destinasi</a><a href="#">Cerita Petualangan</a></div>
    <div class="footer-col"><h4>Perusahaan</h4><a href="#">Tentang Kami</a><a href="#">Karir</a><a href="#">Kontak</a><a href="#">FAQ</a></div>
  </div>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> HikeGear. Hak cipta dilindungi.</span>
    <span>Dibuat dengan ❤️ untuk para pendaki Indonesia</span>
  </div>
</footer>

<script>
// Card entrance animation
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.art-card');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) {
        setTimeout(() => { e.target.style.opacity='1'; e.target.style.transform='translateY(0)'; }, i*50);
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.06 });
  cards.forEach(c => {
    c.style.opacity='0'; c.style.transform='translateY(16px)';
    c.style.transition='opacity .35s ease, transform .35s ease, box-shadow .22s ease, border-color .22s ease';
    obs.observe(c);
  });
});

// Newsletter
function submitNL() {
  const inp = document.getElementById('nlEmail');
  const btn = document.getElementById('nlBtn');
  if (inp.value.includes('@')) {
    inp.value = '';
    btn.textContent = '✓ Berhasil Berlangganan!';
    btn.style.background = '#16a085';
    setTimeout(() => { btn.textContent='Berlangganan Newsletter'; btn.style.background=''; }, 3000);
  } else {
    inp.style.outline = '2px solid #e74c3c';
    inp.focus();
    setTimeout(() => inp.style.outline='', 2000);
  }
}
</script>
</body>
</html>