<?php
// Data kategori produk
$categories = [
    ["id" => 1, "name" => "Pakaian Pria",      "icon" => "shirt",       "desc" => "T-shirt, kemeja, celana pendek, dan lainnya",        "count" => 128, "img" => "https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&q=80"],
    ["id" => 2, "name" => "Pakaian Wanita",    "icon" => "shirt",       "desc" => "T-shirt, kemeja, celana pendek, dan lainnya",        "count" => 96,  "img" => "https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&q=80"],
    ["id" => 3, "name" => "Jaket Outdoor",     "icon" => "wind",        "desc" => "Jaket waterproof, windbreaker, dan lainnya",         "count" => 87,  "img" => "https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&q=80"],
    ["id" => 4, "name" => "Celana Outdoor",    "icon" => "trousers",    "desc" => "Celana panjang, celana pendek, legging, dan lainnya", "count" => 64,  "img" => "https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&q=80"],
    ["id" => 5, "name" => "Tas Carrier",       "icon" => "backpack",    "desc" => "Carrier 20L - 90L untuk segala kebutuhan",           "count" => 53,  "img" => "https://images.unsplash.com/photo-1622260614153-03223fb72052?w=400&q=80"],
    ["id" => 6, "name" => "Sepatu Gunung",     "icon" => "boot",        "desc" => "Hiking, trekking, boots, dan lainnya",               "count" => 71,  "img" => "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80"],
    ["id" => 7, "name" => "Tenda",             "icon" => "tent",        "desc" => "Tenda dome, tenda tunnel, dan lainnya",              "count" => 42,  "img" => "https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&q=80"],
    ["id" => 8, "name" => "Sleeping Bag",      "icon" => "moon",        "desc" => "Sleeping bag untuk cuaca dingin & hangat",           "count" => 36,  "img" => "https://images.unsplash.com/photo-1510672981848-a1c4f1cb5ccf?w=400&q=80"],
    ["id" => 9, "name" => "Alat Masak",        "icon" => "flame",       "desc" => "Kompor, nesting, cookware, dan lainnya",             "count" => 28,  "img" => "https://images.unsplash.com/photo-1464146072230-91cabc968266?w=400&q=80"],
    ["id" => 10,"name" => "Aksesoris",         "icon" => "tag",         "desc" => "Carabiner, tali, matras, dan lainnya",               "count" => 90,  "img" => "https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?w=400&q=80"],
    ["id" => 11,"name" => "Headlamp & Senter", "icon" => "flashlight",  "desc" => "Headlamp, senter, dan aksesoris cahaya",             "count" => 34,  "img" => "https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=400&q=80"],
    ["id" => 12,"name" => "Tongkat & Poles",   "icon" => "pole",        "desc" => "Trekking poles, tongkat gunung, dan lainnya",        "count" => 22,  "img" => "https://images.unsplash.com/photo-1551632811-561732d1e306?w=400&q=80"],
    ["id" => 13,"name" => "Sarung Tangan",     "icon" => "hand",        "desc" => "Sarung tangan hiking & pendakian",                  "count" => 19,  "img" => "https://images.unsplash.com/photo-1601762603339-fd61e28b698a?w=400&q=80"],
    ["id" => 14,"name" => "Kaos Kaki",         "icon" => "sock",        "desc" => "Kaos kaki hiking, wool, dan lainnya",                "count" => 31,  "img" => "https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=400&q=80"],
    ["id" => 15,"name" => "Buff & Masker",     "icon" => "shield",      "desc" => "Buff, gaiter, masker outdoor, dan lainnya",          "count" => 25,  "img" => "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80"],
];

// Sort logic
$sort = $_GET['sort'] ?? 'popular';
$search = trim($_GET['q'] ?? '');

if ($search !== '') {
    $categories = array_filter($categories, fn($c) => stripos($c['name'], $search) !== false || stripos($c['desc'], $search) !== false);
    $categories = array_values($categories);
}

usort($categories, function($a, $b) use ($sort) {
    return match($sort) {
        'az'      => strcmp($a['name'], $b['name']),
        'za'      => strcmp($b['name'], $a['name']),
        'count'   => $b['count'] - $a['count'],
        default   => $b['count'] - $a['count'],
    };
});

$cart_count = 3;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kategori Produk – HikeGear</title>
<style>
  /* ── Reset & Base ── */
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --green-dark:  #1a4731;
    --green:       #1e6b3c;
    --green-mid:   #2d8653;
    --green-light: #e8f5ee;
    --accent:      #27ae60;
    --accent-hover:#1f9e53;
    --white:       #ffffff;
    --gray-50:     #f8fafb;
    --gray-100:    #f0f4f2;
    --gray-200:    #e0e8e4;
    --gray-400:    #9ab0a4;
    --gray-600:    #5a7568;
    --gray-800:    #2c3e35;
    --text:        #1a2e25;
    --radius:      10px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.08);
    --shadow-md:   0 4px 16px rgba(0,0,0,.10);
    --shadow-lg:   0 8px 32px rgba(0,0,0,.14);
    --transition:  .22s ease;
    --font:        'Segoe UI', system-ui, sans-serif;
  }
  html { scroll-behavior: smooth; }
  body { font-family: var(--font); background: var(--gray-50); color: var(--text); font-size: 14px; line-height: 1.5; }
  a { text-decoration: none; color: inherit; }
  img { display: block; width: 100%; object-fit: cover; }
  button { cursor: pointer; border: none; font-family: inherit; }

  /* ── Top bar ── */
  .topbar {
    background: var(--green-dark);
    color: #c8e6d0;
    font-size: 12.5px;
    padding: 7px 24px;
    display: flex; align-items: center; justify-content: space-between;
  }
  .topbar-left { display: flex; align-items: center; gap: 6px; }
  .topbar-right { display: flex; align-items: center; gap: 20px; }
  .topbar-right a { display: flex; align-items: center; gap: 5px; color: #c8e6d0; transition: color var(--transition); }
  .topbar-right a:hover { color: #fff; }

  /* ── Header ── */
  header {
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    padding: 14px 24px;
    display: flex; align-items: center; gap: 20px;
    position: sticky; top: 0; z-index: 100;
    box-shadow: var(--shadow-sm);
  }
  .logo { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
  .logo-icon {
    width: 42px; height: 42px; background: var(--green); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
  }
  .logo-icon svg { width: 24px; height: 24px; fill: none; stroke: white; stroke-width: 2; }
  .logo-text { line-height: 1.2; }
  .logo-text strong { font-size: 18px; font-weight: 800; color: var(--green-dark); letter-spacing: -.3px; }
  .logo-text small { font-size: 9px; color: var(--gray-600); letter-spacing: 1.5px; text-transform: uppercase; display: block; }

  .search-wrap { flex: 1; max-width: 580px; display: flex; }
  .search-wrap input {
    flex: 1; padding: 10px 16px; border: 1.5px solid var(--gray-200);
    border-right: none; border-radius: var(--radius) 0 0 var(--radius);
    font-size: 14px; outline: none; transition: border-color var(--transition);
    background: var(--gray-50);
  }
  .search-wrap input:focus { border-color: var(--accent); background: var(--white); }
  .search-wrap button {
    background: var(--accent); color: white; padding: 10px 18px;
    border-radius: 0 var(--radius) var(--radius) 0;
    transition: background var(--transition);
  }
  .search-wrap button:hover { background: var(--accent-hover); }
  .search-wrap button svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2.5; }

  .header-actions { display: flex; align-items: center; gap: 6px; margin-left: auto; }
  .haction {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 12px; border-radius: 8px;
    color: var(--gray-800); font-size: 13px; font-weight: 500;
    transition: background var(--transition);
    position: relative;
  }
  .haction:hover { background: var(--green-light); color: var(--green); }
  .haction svg { width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; }
  .cart-badge {
    position: absolute; top: 4px; right: 6px;
    background: #e74c3c; color: white; font-size: 10px; font-weight: 700;
    width: 17px; height: 17px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid white;
  }

  /* ── Nav ── */
  nav {
    background: var(--white); border-bottom: 1px solid var(--gray-200);
    padding: 0 24px; display: flex; align-items: center; gap: 4px;
  }
  .nav-cat-btn {
    display: flex; align-items: center; gap: 8px;
    background: var(--green); color: white;
    padding: 12px 16px; font-size: 13.5px; font-weight: 600;
    border-radius: 0; transition: background var(--transition);
    margin-right: 8px;
  }
  .nav-cat-btn:hover { background: var(--green-mid); }
  .nav-cat-btn svg { width: 16px; height: 16px; stroke: white; fill: none; stroke-width: 2.5; }
  .nav-links { display: flex; }
  .nav-links a {
    padding: 13px 16px; font-size: 13.5px; color: var(--gray-800);
    border-bottom: 2px solid transparent; transition: all var(--transition);
    font-weight: 500;
  }
  .nav-links a:hover, .nav-links a.active { color: var(--accent); border-bottom-color: var(--accent); }

  /* ── Layout ── */
  .page-wrap { max-width: 1340px; margin: 0 auto; padding: 24px; display: flex; gap: 24px; }

  /* ── Sidebar ── */
  .sidebar { width: 240px; flex-shrink: 0; }
  .sidebar-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; }
  .sidebar-title {
    background: var(--green); color: white; padding: 12px 16px;
    font-size: 13px; font-weight: 700; letter-spacing: .3px; text-transform: uppercase;
  }
  .sidebar-list { list-style: none; }
  .sidebar-list li a {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; color: var(--gray-800); font-size: 13px;
    transition: background var(--transition);
    border-bottom: 1px solid var(--gray-100);
  }
  .sidebar-list li a:hover, .sidebar-list li a.active {
    background: var(--green-light); color: var(--green); padding-left: 20px;
  }
  .sidebar-list li a .s-name { display: flex; align-items: center; gap: 8px; }
  .sidebar-list li a .s-count { font-size: 11px; color: var(--gray-400); font-weight: 600; }
  .sidebar-list li a.active .s-count { color: var(--accent); }
  .sidebar-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--gray-400); flex-shrink: 0; }
  .sidebar-list li a.active .sidebar-dot, .sidebar-list li a:hover .sidebar-dot { background: var(--accent); }

  .help-box {
    margin-top: 16px; background: white; border-radius: var(--radius);
    box-shadow: var(--shadow-sm); padding: 18px; text-align: center;
  }
  .help-box .help-icon {
    width: 44px; height: 44px; background: var(--green-light); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;
  }
  .help-box .help-icon svg { width: 22px; height: 22px; stroke: var(--green); fill: none; stroke-width: 2; }
  .help-box h4 { font-size: 13px; font-weight: 700; color: var(--gray-800); margin-bottom: 6px; }
  .help-box p { font-size: 12px; color: var(--gray-600); line-height: 1.5; margin-bottom: 12px; }
  .help-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--green-light); color: var(--green); border-radius: 8px;
    padding: 8px 14px; font-size: 12px; font-weight: 600;
    transition: background var(--transition);
  }
  .help-btn:hover { background: var(--accent); color: white; }
  .help-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2.2; }

  /* ── Main content ── */
  .main { flex: 1; min-width: 0; }

  /* breadcrumb */
  .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12.5px; color: var(--gray-600); margin-bottom: 16px; }
  .breadcrumb a { color: var(--gray-600); transition: color var(--transition); }
  .breadcrumb a:hover { color: var(--accent); }
  .breadcrumb .sep { color: var(--gray-400); }
  .breadcrumb .current { color: var(--gray-800); font-weight: 600; }

  /* header row */
  .content-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 20px; gap: 16px; flex-wrap: wrap; }
  .content-header h1 { font-size: 22px; font-weight: 800; color: var(--green-dark); letter-spacing: -.3px; }
  .content-header p { font-size: 13px; color: var(--gray-600); margin-top: 3px; }
  .sort-wrap { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
  .sort-wrap label { font-size: 13px; color: var(--gray-600); white-space: nowrap; }
  .sort-select {
    padding: 8px 32px 8px 12px; border: 1.5px solid var(--gray-200); border-radius: 8px;
    font-size: 13px; font-family: inherit; color: var(--gray-800);
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%235a7568' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    appearance: none; outline: none; cursor: pointer;
    transition: border-color var(--transition);
  }
  .sort-select:focus { border-color: var(--accent); }

  /* search info */
  .search-info { background: var(--green-light); border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: var(--green); display: flex; align-items: center; justify-content: space-between; }
  .search-info strong { font-weight: 700; }
  .search-clear { color: var(--green); font-size: 12px; font-weight: 600; text-decoration: underline; cursor: pointer; }

  /* empty state */
  .empty-state { text-align: center; padding: 60px 20px; color: var(--gray-600); }
  .empty-state svg { width: 56px; height: 56px; stroke: var(--gray-400); fill: none; stroke-width: 1.5; margin: 0 auto 16px; }
  .empty-state h3 { font-size: 17px; font-weight: 700; color: var(--gray-800); margin-bottom: 8px; }
  .empty-state p { font-size: 13px; }

  /* grid */
  .cat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 18px;
  }

  /* card */
  .cat-card {
    background: white; border-radius: var(--radius); overflow: hidden;
    box-shadow: var(--shadow-sm); border: 1.5px solid var(--gray-200);
    transition: transform var(--transition), box-shadow var(--transition), border-color var(--transition);
    cursor: pointer; display: flex; flex-direction: column;
  }
  .cat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--accent);
  }
  .cat-card-img { height: 170px; overflow: hidden; position: relative; }
  .cat-card-img img { height: 100%; transition: transform .35s ease; }
  .cat-card:hover .cat-card-img img { transform: scale(1.06); }
  .cat-card-img::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,.35));
  }
  .cat-card-body { padding: 14px; flex: 1; display: flex; flex-direction: column; }
  .cat-card-body h3 { font-size: 14.5px; font-weight: 700; color: var(--gray-800); margin-bottom: 4px; }
  .cat-card-body p { font-size: 12px; color: var(--gray-600); line-height: 1.5; flex: 1; }
  .cat-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 12px; padding-top: 10px; border-top: 1px solid var(--gray-100);
  }
  .cat-count { font-size: 12.5px; font-weight: 700; color: var(--accent); }
  .cat-arrow {
    width: 28px; height: 28px; border-radius: 50%; background: var(--green-light);
    display: flex; align-items: center; justify-content: center;
    transition: background var(--transition), transform var(--transition);
  }
  .cat-card:hover .cat-arrow { background: var(--accent); transform: translateX(2px); }
  .cat-arrow svg { width: 14px; height: 14px; stroke: var(--green); fill: none; stroke-width: 2.5; transition: stroke var(--transition); }
  .cat-card:hover .cat-arrow svg { stroke: white; }

  /* ── Footer ── */
  footer {
    background: var(--green-dark); color: #aecfbe;
    padding: 32px 24px 20px; margin-top: 40px; font-size: 12.5px;
  }
  .footer-inner { max-width: 1340px; margin: 0 auto; display: flex; gap: 32px; flex-wrap: wrap; justify-content: space-between; }
  .footer-brand .logo-text strong { color: white; }
  .footer-brand .logo-text small { color: #6dab89; }
  .footer-brand p { margin-top: 10px; font-size: 12px; max-width: 220px; line-height: 1.6; color: #7abf99; }
  .footer-col h4 { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6dab89; margin-bottom: 10px; }
  .footer-col a { display: block; margin-bottom: 6px; color: #aecfbe; transition: color var(--transition); }
  .footer-col a:hover { color: white; }
  .footer-bottom { max-width: 1340px; margin: 20px auto 0; padding-top: 14px; border-top: 1px solid rgba(255,255,255,.1); display: flex; justify-content: space-between; font-size: 11.5px; color: #5a8a72; flex-wrap: wrap; gap: 8px; }

  /* ── Responsive ── */
  @media (max-width: 900px) {
    .sidebar { display: none; }
    .cat-grid { grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); }
  }
  @media (max-width: 600px) {
    header { flex-wrap: wrap; }
    .search-wrap { order: 3; max-width: 100%; flex: 0 0 100%; }
    .header-actions span { display: none; }
    .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .cat-card-img { height: 130px; }
    .topbar-right { display: none; }
    .page-wrap { padding: 14px; }
  }
</style>
</head>
<body>

<!-- ── Top Bar ── -->
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

<!-- ── Header ── -->
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
    <button type="submit">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </button>
  </form>

  <div class="header-actions">
    <a class="haction" href="#">
      <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      <span>Wishlist</span>
    </a>
    <a class="haction" href="#">
      <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <span>Masuk / Daftar</span>
    </a>
    <a class="haction" href="#" style="position:relative;">
      <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      <span>Keranjang</span>
      <span class="cart-badge"><?= $cart_count ?></span>
    </a>
  </div>
</header>

<!-- ── Nav ── -->
<nav>
  <button class="nav-cat-btn" id="catToggle">
    <svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    KATEGORI
  </button>
  <div class="nav-links">
    <a href="#">Beranda</a>
    <a href="#" class="active">Kategori</a>
    <a href="#">Produk</a>
    <a href="#">Promo</a>
    <a href="#">Artikel</a>
    <a href="#">Tentang Kami</a>
  </div>
</nav>

<!-- ── Page Wrap ── -->
<div class="page-wrap">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Semua Kategori</div>
      <ul class="sidebar-list">
        <?php foreach ($categories as $cat): ?>
        <li>
          <a href="?q=<?= urlencode($cat['name']) ?>" <?= ($search === $cat['name']) ? 'class="active"' : '' ?>>
            <span class="s-name">
              <span class="sidebar-dot"></span>
              <?= htmlspecialchars($cat['name']) ?>
            </span>
            <span class="s-count"><?= $cat['count'] ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="help-box">
      <div class="help-icon">
        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <h4>Butuh bantuan memilih?</h4>
      <p>Tim kami siap membantu kamu menemukan perlengkapan terbaik.</p>
      <a class="help-btn" href="#">
        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Hubungi Kami
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="main">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <a href="/">Beranda</a>
      <span class="sep">›</span>
      <span class="current">Kategori</span>
      <?php if ($search): ?>
        <span class="sep">›</span>
        <span class="current"><?= htmlspecialchars($search) ?></span>
      <?php endif; ?>
    </div>

    <!-- Header -->
    <div class="content-header">
      <div>
        <h1>Kategori Produk</h1>
        <p>Pilih kategori perlengkapan pendakian yang kamu butuhkan</p>
      </div>
      <div class="sort-wrap">
        <label for="sortSelect">Urutkan:</label>
        <form method="GET" id="sortForm">
          <?php if ($search): ?><input type="hidden" name="q" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
          <select name="sort" id="sortSelect" class="sort-select" onchange="document.getElementById('sortForm').submit()">
            <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Terpopuler</option>
            <option value="count"   <?= $sort === 'count'   ? 'selected' : '' ?>>Produk Terbanyak</option>
            <option value="az"      <?= $sort === 'az'      ? 'selected' : '' ?>>A – Z</option>
            <option value="za"      <?= $sort === 'za'      ? 'selected' : '' ?>>Z – A</option>
          </select>
        </form>
      </div>
    </div>

    <!-- Search info -->
    <?php if ($search): ?>
    <div class="search-info">
      <span>Menampilkan hasil untuk: <strong>"<?= htmlspecialchars($search) ?>"</strong> — <?= count($categories) ?> kategori ditemukan</span>
      <a href="?" class="search-clear">Hapus filter</a>
    </div>
    <?php endif; ?>

    <!-- Grid -->
    <?php if (empty($categories)): ?>
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <h3>Kategori tidak ditemukan</h3>
      <p>Coba cari dengan kata kunci lain, atau <a href="?" style="color:var(--accent)">lihat semua kategori</a>.</p>
    </div>
    <?php else: ?>
    <div class="cat-grid" id="catGrid">
      <?php foreach ($categories as $cat): ?>
      <a class="cat-card" href="produk.php?kategori=<?= $cat['id'] ?>">
        <div class="cat-card-img">
          <img src="<?= $cat['img'] ?>" alt="<?= htmlspecialchars($cat['name']) ?>" loading="lazy">
        </div>
        <div class="cat-card-body">
          <h3><?= htmlspecialchars($cat['name']) ?></h3>
          <p><?= htmlspecialchars($cat['desc']) ?></p>
          <div class="cat-card-footer">
            <span class="cat-count"><?= $cat['count'] ?> Produk</span>
            <span class="cat-arrow">
              <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </main>
</div>

<!-- ── Footer ── -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo">
        <div class="logo-icon">
          <svg viewBox="0 0 24 24" style="width:24px;height:24px;fill:none;stroke:white;stroke-width:2"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
        </div>
        <div class="logo-text">
          <strong>HIKEGEAR</strong>
          <small>Adventure Starts Here</small>
        </div>
      </div>
      <p>Perlengkapan pendakian berkualitas untuk petualangan terbaik kamu di alam bebas.</p>
    </div>
    <div class="footer-col">
      <h4>Belanja</h4>
      <a href="#">Semua Produk</a>
      <a href="#">Promo & Diskon</a>
      <a href="#">Produk Baru</a>
      <a href="#">Best Seller</a>
    </div>
    <div class="footer-col">
      <h4>Bantuan</h4>
      <a href="#">Cara Belanja</a>
      <a href="#">Lacak Pesanan</a>
      <a href="#">Pengembalian</a>
      <a href="#">FAQ</a>
    </div>
    <div class="footer-col">
      <h4>Perusahaan</h4>
      <a href="#">Tentang Kami</a>
      <a href="#">Blog & Artikel</a>
      <a href="#">Karir</a>
      <a href="#">Kontak</a>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© <?= date('Y') ?> HikeGear. Hak cipta dilindungi.</span>
    <span>Dibuat dengan ❤️ untuk para pendaki Indonesia</span>
  </div>
</footer>

<script>
// Smooth card entrance animation
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.cat-card');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }, i * 40);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });

  cards.forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(18px)';
    card.style.transition = 'opacity .35s ease, transform .35s ease, box-shadow .22s ease, border-color .22s ease';
    observer.observe(card);
  });
});

// Highlight active sidebar item on scroll or click
document.querySelectorAll('.sidebar-list a').forEach(link => {
  link.addEventListener('click', function(e) {
    document.querySelectorAll('.sidebar-list a').forEach(l => l.classList.remove('active'));
    this.classList.add('active');
  });
});
</script>
</body>
</html>