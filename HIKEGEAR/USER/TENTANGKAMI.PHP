<?php
$cart_count = 3;

$stats = [
  ["icon"=>"backpack", "num"=>"5.000+",   "label"=>"Produk Berkualitas"],
  ["icon"=>"users",    "num"=>"20.000+",  "label"=>"Pelanggan Puas"],
  ["icon"=>"star",     "num"=>"4.8/5",    "label"=>"Rating Toko"],
  ["icon"=>"box",      "num"=>"100%",     "label"=>"Produk Original"],
  ["icon"=>"truck",    "num"=>"1-3 Hari", "label"=>"Pengiriman Cepat"],
  ["icon"=>"handshake","num"=>"50+",      "label"=>"Brand Partner"],
];

$team = [
  ["name"=>"Rian Pratama",   "role"=>"Founder & CEO",          "img"=>"https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80"],
  ["name"=>"Dewi Lestari",   "role"=>"Product Specialist",     "img"=>"https://images.unsplash.com/photo-1494790108755-2616b612b786?w=200&q=80"],
  ["name"=>"Andi Setiawan",  "role"=>"Marketing Lead",         "img"=>"https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80"],
  ["name"=>"Nadya Rahma",    "role"=>"Customer Service Lead",  "img"=>"https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80"],
  ["name"=>"Fajar Nugroho",  "role"=>"Logistics Coordinator",  "img"=>"https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80"],
];

$misi = [
  "Menyediakan produk outdoor berkualitas dan original.",
  "Memberikan pengalaman belanja online yang mudah, aman, dan menyenangkan.",
  "Memberikan edukasi dan inspirasi seputar dunia pendakian.",
  "Mendukung kelestarian alam melalui kegiatan positif bersama komunitas.",
];

$values = [
  ["icon"=>"heart",    "title"=>"Passion",    "desc"=>"Kami mencintai alam dan dunia pendakian, menjadikannya lebih dari sekadar bisnis."],
  ["icon"=>"shield",   "title"=>"Integritas", "desc"=>"Kejujuran dan kepercayaan adalah fondasi utama dalam setiap layanan kami."],
  ["icon"=>"users",    "title"=>"Komunitas",  "desc"=>"Kami membangun ekosistem pendaki yang saling mendukung dan menginspirasi."],
  ["icon"=>"leaf",     "title"=>"Keberlanjutan","desc"=>"Berkomitmen menjaga kelestarian alam untuk generasi pendaki berikutnya."],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tentang Kami – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --radius:12px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-md:0 4px 16px rgba(0,0,0,.10);--shadow-lg:0 8px 32px rgba(0,0,0,.14);
  --tr:.24s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.6}
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
.search-wrap input{flex:1;padding:10px 16px;border:1.5px solid var(--gray-200);border-right:none;border-radius:10px 0 0 10px;font-size:14px;outline:none;transition:border-color var(--tr);background:var(--gray-50)}
.search-wrap input:focus{border-color:var(--accent);background:var(--white)}
.search-wrap button{background:var(--accent);color:white;padding:10px 18px;border-radius:0 10px 10px 0;transition:background var(--tr)}
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

/* Breadcrumb */
.breadcrumb-bar{background:var(--white);border-bottom:1px solid var(--gray-100);padding:10px 24px}
.breadcrumb{max-width:1340px;margin:0 auto;display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--gray-600)}
.breadcrumb a{color:var(--gray-600);transition:color var(--tr)}
.breadcrumb a:hover{color:var(--accent)}
.breadcrumb .sep{color:var(--gray-400)}
.breadcrumb .current{color:var(--gray-800);font-weight:600}

/* ── Hero Banner ── */
.hero{position:relative;height:340px;overflow:hidden}
.hero img{height:340px;object-fit:cover;object-position:center 30%}
.hero-overlay{position:absolute;inset:0;background:linear-gradient(to right,rgba(10,30,18,.88) 45%,rgba(10,30,18,.3))}
.hero-content{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;padding:0 60px;max-width:680px}
.hero-content h1{font-size:44px;font-weight:900;color:white;line-height:1.1;letter-spacing:-1px;margin-bottom:10px;text-transform:uppercase}
.hero-content .tagline{font-size:20px;font-weight:700;color:var(--accent);margin-bottom:14px}
.hero-content p{font-size:14px;color:#b8d8c4;line-height:1.7;max-width:480px}

/* ── Sections wrapper ── */
.page-content{max-width:1340px;margin:0 auto;padding:36px 24px}

/* section title */
.sec-title{font-size:16px;font-weight:800;color:var(--green-dark);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;display:flex;align-items:center;gap:8px}
.sec-title::after{content:'';display:block;width:36px;height:3px;background:var(--accent);border-radius:2px;margin-top:6px}
.sec-label{display:flex;flex-direction:column;margin-bottom:20px}

/* ── Cerita + Visi/Misi ── */
.story-section{display:grid;grid-template-columns:280px 1fr 1px 1fr;gap:0;background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;margin-bottom:28px;border:1px solid var(--gray-200)}
.story-img{height:100%;min-height:240px}
.story-img img{height:100%;object-fit:cover}
.story-body{padding:32px 28px}
.story-body p{font-size:13.5px;color:var(--gray-600);line-height:1.75;margin-bottom:12px}
.divider-v{width:1px;background:var(--gray-200)}
.visi-misi{display:grid;grid-template-columns:1fr 1fr;gap:0}
.visi-box{padding:32px 28px;border-bottom:1px solid var(--gray-200)}
.misi-box{padding:32px 28px}
.visi-icon{width:52px;height:52px;background:var(--green-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:14px}
.visi-icon svg{width:26px;height:26px;stroke:var(--green);fill:none;stroke-width:1.8}
.visi-box h3{font-size:15px;font-weight:800;color:var(--green-dark);margin-bottom:10px;text-transform:uppercase;letter-spacing:.3px}
.visi-box p{font-size:13px;color:var(--gray-600);line-height:1.7}
.misi-box h3{font-size:15px;font-weight:800;color:var(--green-dark);margin-bottom:10px;text-transform:uppercase;letter-spacing:.3px}
.misi-list{list-style:none;display:flex;flex-direction:column;gap:8px}
.misi-list li{display:flex;align-items:flex-start;gap:8px;font-size:13px;color:var(--gray-600);line-height:1.55}
.misi-list li svg{width:16px;height:16px;stroke:var(--accent);fill:none;stroke-width:2.5;flex-shrink:0;margin-top:1px}

/* ── Stats Bar ── */
.stats-bar{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1px solid var(--gray-200);display:grid;grid-template-columns:repeat(6,1fr);margin-bottom:28px;overflow:hidden}
.stat-item{padding:24px 16px;text-align:center;border-right:1px solid var(--gray-200);transition:background var(--tr)}
.stat-item:last-child{border:none}
.stat-item:hover{background:var(--green-light)}
.stat-icon{width:44px;height:44px;background:var(--green-light);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px}
.stat-icon svg{width:22px;height:22px;stroke:var(--green);fill:none;stroke-width:1.8}
.stat-num{font-size:22px;font-weight:900;color:var(--green-dark);line-height:1;margin-bottom:4px}
.stat-label{font-size:11.5px;color:var(--gray-600);font-weight:500}

/* ── Team ── */
.team-section{margin-bottom:28px}
.team-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px}
.team-header p{font-size:13px;color:var(--gray-600);max-width:320px;line-height:1.6}
.team-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:18px}
.team-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1.5px solid var(--gray-200);padding:24px 16px;text-align:center;transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr)}
.team-card:hover{transform:translateY(-5px);box-shadow:var(--shadow-lg);border-color:var(--accent)}
.team-avatar{width:80px;height:80px;border-radius:50%;overflow:hidden;margin:0 auto 12px;border:3px solid var(--green-light);transition:border-color var(--tr)}
.team-card:hover .team-avatar{border-color:var(--accent)}
.team-avatar img{width:80px;height:80px;object-fit:cover}
.team-name{font-size:13.5px;font-weight:700;color:var(--gray-800);margin-bottom:3px}
.team-role{font-size:12px;color:var(--gray-400);font-weight:500}
.team-socials{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:10px}
.team-socials a{width:28px;height:28px;border-radius:6px;background:var(--green-light);display:flex;align-items:center;justify-content:center;transition:background var(--tr)}
.team-socials a:hover{background:var(--accent)}
.team-socials a svg{width:13px;height:13px;stroke:var(--green);fill:none;stroke-width:2;transition:stroke var(--tr)}
.team-socials a:hover svg{stroke:white}

/* ── Values ── */
.values-section{margin-bottom:28px}
.values-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
.value-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);border:1.5px solid var(--gray-200);padding:24px 20px;transition:transform var(--tr),box-shadow var(--tr),border-color var(--tr)}
.value-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--accent)}
.val-icon{width:48px;height:48px;background:var(--green-light);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;transition:background var(--tr)}
.value-card:hover .val-icon{background:var(--accent)}
.val-icon svg{width:24px;height:24px;stroke:var(--green);fill:none;stroke-width:1.8;transition:stroke var(--tr)}
.value-card:hover .val-icon svg{stroke:white}
.value-card h4{font-size:14px;font-weight:800;color:var(--green-dark);margin-bottom:6px;text-transform:uppercase;letter-spacing:.3px}
.value-card p{font-size:12.5px;color:var(--gray-600);line-height:1.65}

/* ── CTA ── */
.cta-section{background:linear-gradient(135deg,var(--green-dark) 0%,var(--green-mid) 100%);border-radius:var(--radius);padding:44px 48px;display:flex;align-items:center;justify-content:space-between;gap:28px;margin-bottom:0}
.cta-text h2{font-size:24px;font-weight:900;color:white;margin-bottom:8px;letter-spacing:-.3px}
.cta-text p{font-size:14px;color:#a8d8bc;line-height:1.6}
.cta-actions{display:flex;align-items:center;gap:12px;flex-shrink:0}
.cta-btn{padding:12px 24px;border-radius:10px;font-size:14px;font-weight:700;transition:all var(--tr)}
.cta-btn-fill{background:var(--accent);color:white;border:2px solid var(--accent)}
.cta-btn-fill:hover{background:var(--accent-hover);border-color:var(--accent-hover)}
.cta-btn-outline{background:transparent;color:white;border:2px solid rgba(255,255,255,.5)}
.cta-btn-outline:hover{background:rgba(255,255,255,.1);border-color:white}

/* Footer */
footer{background:var(--green-dark);color:#aecfbe;padding:32px 24px 20px;margin-top:0;font-size:12.5px}
.footer-inner{max-width:1340px;margin:0 auto;display:flex;gap:32px;flex-wrap:wrap;justify-content:space-between}
.footer-brand p{margin-top:10px;font-size:12px;max-width:220px;line-height:1.6;color:#7abf99}
.footer-col h4{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6dab89;margin-bottom:10px}
.footer-col a{display:block;margin-bottom:6px;color:#aecfbe;transition:color var(--tr)}
.footer-col a:hover{color:white}
.footer-bottom{max-width:1340px;margin:20px auto 0;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);display:flex;justify-content:space-between;font-size:11.5px;color:#5a8a72;flex-wrap:wrap;gap:8px}

/* Responsive */
@media(max-width:1100px){
  .story-section{grid-template-columns:220px 1fr 1px 1fr}
  .stats-bar{grid-template-columns:repeat(3,1fr)}
  .stat-item:nth-child(3){border-right:none}
  .stat-item:nth-child(4){border-top:1px solid var(--gray-200)}
  .team-grid{grid-template-columns:repeat(3,1fr)}
  .values-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:860px){
  .story-section{grid-template-columns:1fr;grid-template-rows:200px auto auto}
  .story-img{min-height:200px}
  .divider-v{display:none}
  .visi-misi{grid-template-columns:1fr}
  .stats-bar{grid-template-columns:repeat(2,1fr)}
  .stat-item:nth-child(2){border-right:none}
  .stat-item:nth-child(3){border-top:1px solid var(--gray-200);border-right:1px solid var(--gray-200)}
  .stat-item:nth-child(4){border-right:none}
  .stat-item:nth-child(5){border-top:1px solid var(--gray-200)}
  .team-grid{grid-template-columns:repeat(2,1fr)}
  .cta-section{flex-direction:column;text-align:center;padding:32px 24px}
  .cta-actions{justify-content:center}
}
@media(max-width:600px){
  header{flex-wrap:wrap}.search-wrap{order:3;max-width:100%;flex:0 0 100%}
  .header-actions span{display:none}.topbar-right{display:none}
  .hero-content{padding:0 24px}.hero-content h1{font-size:30px}
  .hero-content .tagline{font-size:15px}
  .page-content{padding:20px 14px}
  .stats-bar{grid-template-columns:repeat(2,1fr)}
  .values-grid{grid-template-columns:1fr}
  .team-grid{grid-template-columns:repeat(2,1fr)}
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
  <form class="search-wrap" method="GET" action="produk.php">
    <input type="text" name="q" placeholder="Cari produk pendakian...">
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
    <a href="artikel.php">Artikel</a>
    <a href="tentang.php" class="active">Tentang Kami</a>
  </div>
</nav>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
  <div class="breadcrumb">
    <a href="/">Beranda</a><span class="sep">›</span>
    <span class="current">Tentang Kami</span>
  </div>
</div>

<!-- ── Hero ── -->
<div class="hero">
  <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=1400&q=80" alt="HikeGear Hero">
  <div class="hero-overlay"></div>
  <div class="hero-content" data-animate>
    <h1>TENTANG KAMI</h1>
    <div class="tagline">HikeGear – Adventure Starts Here</div>
    <p>HikeGear adalah toko online terpercaya yang menyediakan perlengkapan pendakian berkualitas untuk setiap petualanganmu. Kami hadir untuk mendukung setiap langkahmu menuju puncak.</p>
  </div>
</div>

<!-- ── Main Content ── -->
<div class="page-content">

  <!-- Cerita + Visi + Misi -->
  <div class="story-section" data-animate>

    <!-- Foto -->
    <div class="story-img">
      <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=600&q=80" alt="Cerita Kami">
    </div>

    <!-- Cerita -->
    <div class="story-body">
      <div class="sec-label">
        <span class="sec-title">Cerita Kami</span>
      </div>
      <p>Berawal dari kecintaan kami terhadap alam dan dunia pendakian, HikeGear didirikan dengan tujuan menyediakan perlengkapan outdoor yang tidak hanya berkualitas, tetapi juga terjangkau bagi semua kalangan.</p>
      <p>Kami percaya bahwa setiap petualangan, sekecil apapun, memiliki cerita dan makna. Karena itu, kami berkomitmen untuk menjadi teman terbaik dalam setiap perjalananmu.</p>
    </div>

    <!-- Divider -->
    <div class="divider-v"></div>

    <!-- Visi & Misi -->
    <div class="visi-misi">
      <div class="visi-box">
        <div class="visi-icon">
          <svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
        </div>
        <h3>Visi Kami</h3>
        <p>Menjadi toko perlengkapan pendakian terdepan di Indonesia yang menginspirasi lebih banyak orang untuk mencintai alam dan menjelajah secara bertanggung jawab.</p>
      </div>
      <div class="misi-box">
        <h3>Misi Kami</h3>
        <ul class="misi-list">
          <?php foreach ($misi as $m): ?>
          <li>
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            <?= htmlspecialchars($m) ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div>

  <!-- Stats Bar -->
  <div class="stats-bar" data-animate>
    <?php
    $stat_icons = [
      'backpack'  => '<path d="M4 20V10a8 8 0 0 1 16 0v10"/><rect x="8" y="14" width="8" height="6" rx="1"/><path d="M8 10h8"/><path d="M12 4v2"/>',
      'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
      'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
      'box'       => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
      'truck'     => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
      'handshake' => '<path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
    ];
    foreach ($stats as $s):
    ?>
    <div class="stat-item">
      <div class="stat-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?= $stat_icons[$s['icon']] ?></svg>
      </div>
      <div class="stat-num" data-count><?= $s['num'] ?></div>
      <div class="stat-label"><?= $s['label'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Tim Kami -->
  <div class="team-section" data-animate>
    <div class="team-header">
      <div>
        <div class="sec-label">
          <span class="sec-title">Tim Kami</span>
        </div>
        <p>Kami adalah tim pecinta alam yang siap memberikan pelayanan terbaik dan solusi terbaik untuk setiap petualanganmu.</p>
      </div>
    </div>
    <div class="team-grid">
      <?php foreach ($team as $m): ?>
      <div class="team-card">
        <div class="team-avatar">
          <img src="<?= $m['img'] ?>" alt="<?= htmlspecialchars($m['name']) ?>">
        </div>
        <div class="team-name"><?= htmlspecialchars($m['name']) ?></div>
        <div class="team-role"><?= htmlspecialchars($m['role']) ?></div>
        <div class="team-socials">
          <a href="#" title="Instagram">
            <svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
          <a href="#" title="LinkedIn">
            <svg viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
          <a href="#" title="Email">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Nilai Kami -->
  <div class="values-section" data-animate>
    <div class="sec-label" style="margin-bottom:20px">
      <span class="sec-title">Nilai-Nilai Kami</span>
    </div>
    <div class="values-grid">
      <?php
      $val_icons = [
        'heart' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
        'shield'=> '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'leaf'  => '<path d="M2 22c0 0 5-3 7-10 2 4 4 6 7 6-2 2-5 3-7 3s-4-1-7 1z"/><path d="M18 8c0 3.3-2.7 6-6 6"/>',
      ];
      foreach ($values as $v):
      ?>
      <div class="value-card">
        <div class="val-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?= $val_icons[$v['icon']] ?></svg>
        </div>
        <h4><?= htmlspecialchars($v['title']) ?></h4>
        <p><?= htmlspecialchars($v['desc']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- CTA -->
  <div class="cta-section" data-animate>
    <div class="cta-text">
      <h2>Siap Memulai Petualanganmu?</h2>
      <p>Temukan perlengkapan pendakian terbaik dan berkualitas di HikeGear. Ribuan produk siap mendukung setiap langkahmu menuju puncak.</p>
    </div>
    <div class="cta-actions">
      <a href="produk.php" class="cta-btn cta-btn-fill">Belanja Sekarang</a>
      <a href="artikel.php" class="cta-btn cta-btn-outline">Baca Artikel</a>
    </div>
  </div>

</div><!-- /page-content -->

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
// Entrance animations
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.opacity = '1';
      e.target.style.transform = 'translateY(0)';
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.08 });

document.querySelectorAll('[data-animate]').forEach((el, i) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(22px)';
  el.style.transition = `opacity .5s ease ${i * 0.08}s, transform .5s ease ${i * 0.08}s`;
  observer.observe(el);
});

// Team card tilt effect
document.querySelectorAll('.team-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    card.style.transform = `translateY(-5px) rotateY(${x * 8}deg) rotateX(${-y * 8}deg)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
  });
});
</script>
</body>
</html>