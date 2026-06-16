<?php
// beranda.php — HikeGear Halaman Beranda
session_start();

/* ── DATA (ganti dengan query MySQL nyata) ─────────────── */
$produk_terbaru = [
  ['id'=>1,'kategori'=>'JAKET','nama'=>'Eiger Mountaineer Jacket','harga'=>1250000,'harga_coret'=>null,'badge'=>'BARU','rating'=>4.5,'ulasan'=>120,'gambar'=>'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=400&q=80'],
  ['id'=>2,'kategori'=>'CELANA','nama'=>'Consina Panjang Pria','harga'=>650000,'harga_coret'=>null,'badge'=>'BARU','rating'=>4.8,'ulasan'=>86,'gambar'=>'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&q=80'],
  ['id'=>3,'kategori'=>'KAOS','nama'=>'Eiger Quick Dry Tee','harga'=>199000,'harga_coret'=>220000,'badge'=>'-10%','rating'=>4.3,'ulasan'=>47,'gambar'=>'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80'],
  ['id'=>4,'kategori'=>'TOPI','nama'=>'Eiger Cap Journey','harga'=>149000,'harga_coret'=>null,'badge'=>'BARU','rating'=>4.4,'ulasan'=>35,'gambar'=>'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400&q=80'],
  ['id'=>5,'kategori'=>'SARUNG TANGAN','nama'=>'Consina Winter Glove','harga'=>275000,'harga_coret'=>null,'badge'=>null,'rating'=>4.2,'ulasan'=>29,'gambar'=>'https://images.unsplash.com/photo-1510771463146-e89e6e86560e?w=400&q=80'],
  ['id'=>6,'kategori'=>'KAUS KAKI','nama'=>'Eiger Hiking Sock','harga'=>85000,'harga_coret'=>100000,'badge'=>'-15%','rating'=>4.6,'ulasan'=>63,'gambar'=>'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=400&q=80'],
];

$produk_terlaris = [
  ['id'=>7,'kategori'=>'TAS CARRIER','nama'=>'Consina Amago 45L','harga'=>895000,'harga_coret'=>1100000,'badge'=>'-19%','rating'=>4.9,'ulasan'=>214,'gambar'=>'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&q=80'],
  ['id'=>8,'kategori'=>'SEPATU','nama'=>'Eiger Trail Runner V2','harga'=>1150000,'harga_coret'=>null,'badge'=>'TERLARIS','rating'=>4.7,'ulasan'=>189,'gambar'=>'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80'],
  ['id'=>9,'kategori'=>'TENDA','nama'=>'Consina Dome Tent 3P','harga'=>2350000,'harga_coret'=>2800000,'badge'=>'-16%','rating'=>4.8,'ulasan'=>97,'gambar'=>'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=400&q=80'],
  ['id'=>10,'kategori'=>'SLEEPING BAG','nama'=>'Eiger Mummy Bag -5°C','harga'=>650000,'harga_coret'=>null,'badge'=>'BARU','rating'=>4.5,'ulasan'=>73,'gambar'=>'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=400&q=80'],
  ['id'=>11,'kategori'=>'JAKET','nama'=>'Rei Waterproof Jacket','harga'=>980000,'harga_coret'=>1200000,'badge'=>'-18%','rating'=>4.6,'ulasan'=>145,'gambar'=>'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&q=80'],
  ['id'=>12,'kategori'=>'HEADLAMP','nama'=>'Petzl Tikka Core 450lm','harga'=>520000,'harga_coret'=>null,'badge'=>null,'rating'=>4.9,'ulasan'=>88,'gambar'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80'],
];

$kategori = [
  ['nama'=>'Jaket Outdoor','img'=>'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=200&q=80'],
  ['nama'=>'Celana Outdoor','img'=>'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=200&q=80'],
  ['nama'=>'Tas Carrier',   'img'=>'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=200&q=80'],
  ['nama'=>'Sepatu Gunung', 'img'=>'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&q=80'],
  ['nama'=>'Tenda',         'img'=>'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=200&q=80'],
  ['nama'=>'Sleeping Bag',  'img'=>'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=200&q=80'],
  ['nama'=>'Alat Masak',    'img'=>'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=200&q=80'],
  ['nama'=>'Aksesoris',     'img'=>'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=200&q=80'],
];

function rupiah(int $n): string {
  return 'Rp'.number_format($n,0,',','.');
}
function diskon(int $harga, int $coret): int {
  return round((1 - $harga/$coret)*100);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>HikeGear – Perlengkapan Pendakian Terbaik</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet"/>
<style>
/* ╔══════════════════════════════════════════════════════╗
   ║  RESET & DESIGN TOKENS                               ║
   ╚══════════════════════════════════════════════════════╝ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --g9:#0f3d1a; --g8:#1a5c2a; --g7:#2d7a3a;
  --g6:#3d9e4e; --g5:#4caf63; --g1:#e8f5ea; --g0:#f0faf1;
  --gold:#f59e0b; --red:#ef4444; --red-bg:#fef2f2;
  --w:#fff;
  --s0:#f9fafb; --s1:#f3f4f6; --s2:#e5e7eb;
  --s3:#d1d5db; --s4:#9ca3af; --s5:#6b7280;
  --s6:#4b5563; --s8:#1f2937; --s9:#111827;
  --sh0:0 1px 2px rgba(0,0,0,.06);
  --sh1:0 2px 8px rgba(0,0,0,.09);
  --sh2:0 6px 20px rgba(0,0,0,.12);
  --sh3:0 12px 40px rgba(0,0,0,.16);
  --r:10px; --rl:14px; --rxl:20px;
}
html{scroll-behavior:smooth}
body{font-family:'Inter',sans-serif;font-size:14px;color:var(--s8);background:var(--s0);line-height:1.5}
a{text-decoration:none;color:inherit}img{max-width:100%;display:block}
ul{list-style:none}button{cursor:pointer;font-family:inherit;border:none}

/* ╔══════════════════════════════════════════════════════╗
   ║  ANNOUNCEMENT BAR                                    ║
   ╚══════════════════════════════════════════════════════╝ */
.ann{background:var(--g8);color:#fff;font-size:.75rem;font-weight:500;
  padding:7px 32px;display:flex;align-items:center;justify-content:space-between}
.ann-left{display:flex;align-items:center;gap:6px}
.ann-right{display:flex;gap:20px}
.ann-right a{color:rgba(255,255,255,.82);display:flex;align-items:center;gap:5px;transition:color .15s}
.ann-right a:hover{color:#fff}
.ann-sep{color:rgba(255,255,255,.25)}

/* ╔══════════════════════════════════════════════════════╗
   ║  NAVBAR                                              ║
   ╚══════════════════════════════════════════════════════╝ */
.navbar{position:sticky;top:0;z-index:300;background:var(--w);
  border-bottom:1px solid var(--s2);box-shadow:var(--sh0)}
.nb{max-width:1340px;margin:0 auto;padding:11px 24px;
  display:flex;align-items:center;gap:18px}

/* Logo */
.nb-logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
.nb-logo svg{width:42px;height:42px}
.nb-brand b{display:block;font-size:1.08rem;font-weight:800;letter-spacing:-.4px}
.nb-brand b span{color:var(--g7)}
.nb-brand small{font-size:.58rem;letter-spacing:2px;text-transform:uppercase;color:var(--s4);font-weight:500}

/* Search */
.nb-search{flex:1;max-width:580px;display:flex;
  border:1.5px solid var(--s2);border-radius:var(--r);overflow:hidden;
  transition:border-color .2s,box-shadow .2s}
.nb-search:focus-within{border-color:var(--g6);box-shadow:0 0 0 3px rgba(61,158,78,.13)}
.nb-search input{flex:1;border:none;outline:none;padding:11px 16px;
  font-size:.88rem;color:var(--s8);font-family:inherit;background:transparent}
.nb-search input::placeholder{color:var(--s4)}
.nb-search button{background:var(--g7);color:#fff;padding:0 22px;
  display:flex;align-items:center;gap:7px;font-size:.83rem;font-weight:600;
  transition:background .18s;white-space:nowrap}
.nb-search button:hover{background:var(--g8)}

/* Actions */
.nb-actions{display:flex;align-items:center;gap:20px;margin-left:auto;flex-shrink:0}
.nb-act{display:flex;flex-direction:column;align-items:center;gap:2px;
  color:var(--s6);font-size:.68rem;font-weight:500;transition:color .15s;position:relative}
.nb-act:hover{color:var(--g7)}
.nb-act svg{width:22px;height:22px;stroke:currentColor;fill:none;stroke-width:1.8}
.nb-badge{position:absolute;top:-7px;right:-9px;background:var(--g7);color:#fff;
  font-size:.58rem;font-weight:700;min-width:17px;height:17px;border-radius:9px;
  display:flex;align-items:center;justify-content:center;padding:0 3px}

/* ╔══════════════════════════════════════════════════════╗
   ║  SECONDARY NAV (Kategori + links)                    ║
   ╚══════════════════════════════════════════════════════╝ */
.snav{background:var(--w);border-bottom:1px solid var(--s2);
  position:sticky;top:65px;z-index:290}
.snav-inner{max-width:1340px;margin:0 auto;padding:0 24px;
  display:flex;align-items:stretch}
.snav-kat{background:var(--g7);color:#fff;border:none;
  display:flex;align-items:center;gap:10px;padding:13px 22px;
  font-size:.875rem;font-weight:700;white-space:nowrap;
  transition:background .18s;position:relative}
.snav-kat:hover{background:var(--g8)}
.snav-kat svg{width:17px;height:17px;stroke:currentColor;fill:none;stroke-width:2.5}
.snav-links{display:flex}
.snav-links a{display:flex;align-items:center;padding:0 17px;
  font-size:.875rem;font-weight:500;color:var(--s6);height:100%;
  position:relative;transition:color .15s;white-space:nowrap}
.snav-links a::after{content:'';position:absolute;bottom:0;left:17px;right:17px;
  height:2.5px;background:var(--g7);border-radius:2px;
  transform:scaleX(0);transition:transform .2s}
.snav-links a:hover,.snav-links a.on{color:var(--g7)}
.snav-links a:hover::after,.snav-links a.on::after{transform:scaleX(1)}

/* MEGA MENU */
.mega-menu{position:absolute;top:100%;left:0;width:240px;z-index:400;
  background:var(--w);border:1px solid var(--s2);border-top:none;
  border-radius:0 0 var(--r) var(--r);box-shadow:var(--sh2);
  display:none;flex-direction:column;padding:8px 0}
.snav-kat:focus-within .mega-menu,
.snav-kat:hover .mega-menu{display:flex}
.mega-menu a{display:flex;align-items:center;gap:10px;padding:10px 18px;
  font-size:.84rem;color:var(--s6);transition:background .12s,color .12s}
.mega-menu a:hover{background:var(--g0);color:var(--g7)}
.mega-menu a svg{width:16px;height:16px;color:var(--g6);flex-shrink:0}

/* ╔══════════════════════════════════════════════════════╗
   ║  HERO SLIDER                                         ║
   ╚══════════════════════════════════════════════════════╝ */
.hero{position:relative;overflow:hidden;background:#1a3a1f;user-select:none}
.slides-wrap{display:flex;transition:transform .6s cubic-bezier(.4,0,.2,1)}
.slide{min-width:100%;height:430px;position:relative;
  display:flex;align-items:center;overflow:hidden}
.slide-bg{position:absolute;inset:0;background-size:cover;background-position:center;
  transition:transform 6s ease;will-change:transform}
.slide.active .slide-bg{transform:scale(1.04)}
.slide-overlay{position:absolute;inset:0;
  background:linear-gradient(90deg,rgba(10,30,12,.72) 0%,rgba(10,30,12,.4) 55%,rgba(0,0,0,.1) 100%)}
.slide-body{position:relative;z-index:2;padding:0 80px;color:#fff;max-width:580px}
.slide-tag{display:inline-block;background:var(--g5);color:#fff;
  font-size:.68rem;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;
  padding:4px 12px;border-radius:20px;margin-bottom:14px}
.slide-body h1{font-size:2.65rem;font-weight:800;line-height:1.12;
  margin-bottom:10px;text-shadow:0 2px 16px rgba(0,0,0,.35)}
.slide-body h2{font-size:1.3rem;font-weight:600;color:var(--g5);margin-bottom:14px}
.slide-body p{font-size:.88rem;color:rgba(255,255,255,.8);line-height:1.75;
  margin-bottom:28px;max-width:400px}
.btn-hero{display:inline-flex;align-items:center;gap:9px;
  background:var(--g7);color:#fff;padding:13px 30px;
  border-radius:var(--r);font-weight:700;font-size:.88rem;
  border:none;transition:background .18s,transform .12s,box-shadow .18s;
  box-shadow:0 4px 14px rgba(45,122,58,.4)}
.btn-hero:hover{background:var(--g8);transform:translateY(-2px);box-shadow:0 6px 20px rgba(45,122,58,.5)}
.btn-hero svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:2.5}

.sl-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:10;
  width:46px;height:46px;border-radius:50%;
  background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
  border:1.5px solid rgba(255,255,255,.3);color:#fff;
  display:flex;align-items:center;justify-content:center;
  transition:background .18s,transform .12s}
.sl-arrow:hover{background:rgba(255,255,255,.32);transform:translateY(-50%) scale(1.06)}
.sl-arrow.prev{left:18px}
.sl-arrow.next{right:18px}
.sl-arrow svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:2.5}

.sl-dots{position:absolute;bottom:18px;left:50%;transform:translateX(-50%);
  display:flex;gap:8px;z-index:10}
.sl-dot{width:9px;height:9px;border-radius:5px;background:rgba(255,255,255,.4);
  border:none;transition:background .2s,width .3s;cursor:pointer}
.sl-dot.on{background:#fff;width:26px}

/* ╔══════════════════════════════════════════════════════╗
   ║  TRUST BAR                                           ║
   ╚══════════════════════════════════════════════════════╝ */
.trust-wrap{max-width:1340px;margin:18px auto;padding:0 24px}
.trust-bar{background:var(--w);border:1px solid var(--s2);border-radius:var(--rl);
  display:grid;grid-template-columns:repeat(5,1fr)}
.trust-item{display:flex;align-items:center;gap:12px;padding:16px 18px;
  border-right:1px solid var(--s2)}
.trust-item:last-child{border-right:none}
.trust-ic{width:38px;height:38px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  background:var(--g0);border-radius:9px;color:var(--g7)}
.trust-ic svg{width:20px;height:20px;stroke:currentColor;fill:none;stroke-width:1.8}
.trust-txt b{display:block;font-size:.78rem;font-weight:700;color:var(--s8)}
.trust-txt span{font-size:.7rem;color:var(--s4)}

/* ╔══════════════════════════════════════════════════════╗
   ║  LAYOUT CONTAINER                                    ║
   ╚══════════════════════════════════════════════════════╝ */
.wrap{max-width:1340px;margin:0 auto;padding:0 24px}
.sec{padding:26px 0}
.sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.sec-title{font-size:1rem;font-weight:800;text-transform:uppercase;
  letter-spacing:.5px;color:var(--s8);display:flex;align-items:center;gap:8px}
.sec-title::before{content:'';display:inline-block;width:4px;height:18px;
  background:var(--g7);border-radius:2px}
.sec-more{display:flex;align-items:center;gap:4px;font-size:.78rem;
  font-weight:600;color:var(--g7);transition:gap .18s}
.sec-more:hover{gap:7px}
.sec-more svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5}

/* ╔══════════════════════════════════════════════════════╗
   ║  KATEGORI GRID                                       ║
   ╚══════════════════════════════════════════════════════╝ */
.kat-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:12px}
.kat-card{background:var(--w);border:1.5px solid var(--s2);border-radius:var(--r);
  padding:18px 8px 13px;text-align:center;
  transition:border-color .18s,transform .18s,box-shadow .18s;cursor:pointer}
.kat-card:hover{border-color:var(--g6);transform:translateY(-4px);box-shadow:var(--sh1)}
.kat-img-wrap{width:72px;height:72px;margin:0 auto 10px;border-radius:9px;
  overflow:hidden;background:var(--s1)}
.kat-img-wrap img{width:100%;height:100%;object-fit:cover;
  transition:transform .3s}
.kat-card:hover .kat-img-wrap img{transform:scale(1.08)}
.kat-lbl{font-size:.71rem;font-weight:600;color:var(--s6);line-height:1.3}

/* ╔══════════════════════════════════════════════════════╗
   ║  BANNER PROMO                                        ║
   ╚══════════════════════════════════════════════════════╝ */
.promo-row{display:grid;grid-template-columns:2fr 1fr 1fr;gap:14px;margin-bottom:8px}
.promo-card{position:relative;border-radius:var(--rl);overflow:hidden;
  cursor:pointer;transition:transform .2s,box-shadow .2s}
.promo-card:hover{transform:translateY(-3px);box-shadow:var(--sh2)}
.promo-card img{width:100%;height:100%;object-fit:cover;display:block;
  transition:transform .4s}
.promo-card:hover img{transform:scale(1.04)}
.promo-card.big{min-height:180px}
.promo-card.sm{min-height:140px}
.promo-shade{position:absolute;inset:0;
  background:linear-gradient(to top,rgba(0,0,0,.68) 0%,rgba(0,0,0,.05) 60%)}
.promo-txt{position:absolute;bottom:0;left:0;right:0;z-index:2;padding:16px 18px;color:#fff}
.promo-txt em{font-style:normal;display:block;font-size:.68rem;font-weight:700;
  letter-spacing:1.5px;text-transform:uppercase;color:var(--g5);margin-bottom:4px}
.promo-txt strong{font-size:1rem;font-weight:800;display:block}
.promo-txt span{font-size:.75rem;color:rgba(255,255,255,.75)}

/* ╔══════════════════════════════════════════════════════╗
   ║  PRODUK CARD                                         ║
   ╚══════════════════════════════════════════════════════╝ */
.prod-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
.prod-card{background:var(--w);border:1.5px solid var(--s2);border-radius:var(--r);
  overflow:hidden;transition:border-color .2s,transform .2s,box-shadow .2s;position:relative}
.prod-card:hover{border-color:var(--g5);transform:translateY(-5px);box-shadow:var(--sh2)}

.prod-pic{position:relative;aspect-ratio:1;overflow:hidden;background:var(--s1)}
.prod-pic img{width:100%;height:100%;object-fit:cover;
  transition:transform .38s;display:block}
.prod-card:hover .prod-pic img{transform:scale(1.07)}

.prod-badge{position:absolute;top:8px;left:8px;z-index:2;
  padding:3px 8px;border-radius:5px;font-size:.67rem;font-weight:700}
.bg-baru{background:var(--g7);color:#fff}
.bg-disc{background:var(--red);color:#fff}
.bg-top {background:#f59e0b;color:#fff}

.prod-wish{position:absolute;top:8px;right:8px;z-index:2;
  width:30px;height:30px;border-radius:50%;
  background:rgba(255,255,255,.88);border:none;
  display:flex;align-items:center;justify-content:center;
  transition:background .15s,transform .15s;backdrop-filter:blur(4px)}
.prod-wish:hover{background:var(--w);transform:scale(1.12)}
.prod-wish svg{width:14px;height:14px;stroke:var(--s4);fill:none;stroke-width:2;transition:stroke .15s,fill .15s}
.prod-wish.on svg{stroke:var(--red);fill:var(--red)}

.prod-body{padding:12px}
.prod-kat{font-size:.64rem;font-weight:700;letter-spacing:.8px;
  text-transform:uppercase;color:var(--s4);margin-bottom:3px}
.prod-nama{font-size:.8rem;font-weight:600;color:var(--s8);line-height:1.35;
  margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;
  -webkit-box-orient:vertical;overflow:hidden;min-height:2.1em}
.prod-prices{display:flex;align-items:baseline;gap:6px;flex-wrap:wrap;margin-bottom:5px}
.prod-harga{font-size:.92rem;font-weight:800;color:var(--s9)}
.prod-coret{font-size:.72rem;color:var(--s4);text-decoration:line-through}
.prod-rating{display:flex;align-items:center;gap:4px;margin-bottom:10px}
.stars{color:var(--gold);font-size:.8rem;letter-spacing:-.5px}
.prod-rating span{font-size:.68rem;color:var(--s5)}
.btn-add{width:100%;padding:8px;border-radius:7px;
  background:var(--g0);color:var(--g7);
  border:1.5px solid #c3e8c9;
  font-size:.74rem;font-weight:700;
  display:flex;align-items:center;justify-content:center;gap:6px;
  transition:background .18s,color .18s,border-color .18s}
.btn-add:hover{background:var(--g7);color:#fff;border-color:var(--g7)}
.btn-add svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2.5}

/* ╔══════════════════════════════════════════════════════╗
   ║  ARTIKEL / TIPS                                      ║
   ╚══════════════════════════════════════════════════════╝ */
.artikel-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.art-card{background:var(--w);border:1.5px solid var(--s2);border-radius:var(--r);
  overflow:hidden;transition:transform .2s,box-shadow .2s;cursor:pointer}
.art-card:hover{transform:translateY(-4px);box-shadow:var(--sh2)}
.art-img{height:140px;overflow:hidden}
.art-img img{width:100%;height:100%;object-fit:cover;transition:transform .35s}
.art-card:hover .art-img img{transform:scale(1.05)}
.art-body{padding:14px}
.art-tag{font-size:.66rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;
  color:var(--g7);margin-bottom:5px}
.art-title{font-size:.82rem;font-weight:700;color:var(--s8);line-height:1.4;
  margin-bottom:6px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.art-meta{font-size:.68rem;color:var(--s4)}

/* ╔══════════════════════════════════════════════════════╗
   ║  FOOTER                                              ║
   ╚══════════════════════════════════════════════════════╝ */
footer{background:var(--s8);color:var(--s2);margin-top:40px}
.ft-top{max-width:1340px;margin:0 auto;padding:40px 24px;
  display:grid;grid-template-columns:2.2fr 1fr 1fr 1fr;gap:40px}
.ft-logo{display:flex;align-items:center;gap:10px;margin-bottom:14px}
.ft-logo b{font-size:1.05rem;font-weight:800;color:#fff}
.ft-logo b span{color:var(--g5)}
.ft-logo small{font-size:.57rem;letter-spacing:2px;text-transform:uppercase;color:var(--s5)}
.ft-desc{font-size:.78rem;color:var(--s4);line-height:1.75;margin-bottom:18px}
.ft-socials{display:flex;gap:9px}
.ft-soc{width:34px;height:34px;border-radius:8px;background:#374151;
  display:flex;align-items:center;justify-content:center;
  color:var(--s3);transition:background .15s,color .15s}
.ft-soc:hover{background:var(--g7);color:#fff}
.ft-soc svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:1.8}
.ft-col h4{font-size:.75rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
  color:#fff;margin-bottom:14px}
.ft-col li{margin-bottom:8px}
.ft-col a{font-size:.78rem;color:var(--s4);transition:color .13s}
.ft-col a:hover{color:var(--g5)}
.ft-bottom{border-top:1px solid #374151;padding:16px 24px;
  max-width:1340px;margin:0 auto;
  display:flex;align-items:center;justify-content:space-between;
  font-size:.73rem;color:var(--s4)}

/* ╔══════════════════════════════════════════════════════╗
   ║  TOAST NOTIFICATION                                  ║
   ╚══════════════════════════════════════════════════════╝ */
#toast{position:fixed;bottom:26px;right:26px;z-index:9999;
  background:var(--g8);color:#fff;padding:13px 20px;border-radius:var(--r);
  font-size:.82rem;font-weight:600;box-shadow:var(--sh3);
  display:flex;align-items:center;gap:9px;
  transform:translateY(80px);opacity:0;transition:transform .3s cubic-bezier(.34,1.56,.64,1),opacity .25s}
#toast.show{transform:translateY(0);opacity:1}
#toast svg{width:16px;height:16px;stroke:#fff;fill:none;stroke-width:2.5;flex-shrink:0}

/* ╔══════════════════════════════════════════════════════╗
   ║  RESPONSIVE                                          ║
   ╚══════════════════════════════════════════════════════╝ */
@media(max-width:1100px){
  .prod-grid{grid-template-columns:repeat(4,1fr)}
  .kat-grid{grid-template-columns:repeat(4,1fr)}
  .trust-bar{grid-template-columns:repeat(3,1fr)}
  .trust-item:nth-child(3){border-right:none}
  .ft-top{grid-template-columns:1fr 1fr;gap:28px}
  .promo-row{grid-template-columns:1fr 1fr}
  .promo-row .promo-card.big{grid-column:span 2}
}
@media(max-width:768px){
  .ann-right,.ft-top .ft-col{display:none}
  .nb{padding:10px 16px;gap:12px}
  .snav-inner{overflow-x:auto;-webkit-overflow-scrolling:touch}
  .prod-grid,.kat-grid{grid-template-columns:repeat(2,1fr)}
  .trust-bar{grid-template-columns:repeat(2,1fr)}
  .trust-item:nth-child(2){border-right:none}
  .trust-item:nth-child(3){border-right:1px solid var(--s2)}
  .promo-row{grid-template-columns:1fr;display:none}
  .slide-body{padding:0 44px}
  .slide-body h1{font-size:1.8rem}
  .artikel-grid{grid-template-columns:repeat(2,1fr)}
}
</style>
</head>
<body>

<!-- ═══ ANNOUNCEMENT BAR ═══════════════════════════════ -->
<div class="ann">
  <div class="ann-left">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    Gratis Ongkir untuk semua pesanan di Indonesia
  </div>
  <div class="ann-right">
    <a href="#"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Lacak Pesanan</a>
    <span class="ann-sep">|</span>
    <a href="#"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Bantuan</a>
    <span class="ann-sep">|</span>
    <a href="#"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> Kontak Kami</a>
  </div>
</div>

<!-- ═══ NAVBAR ══════════════════════════════════════════ -->
<header class="navbar">
  <div class="nb">
    <a href="beranda.php" class="nb-logo">
      <svg viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="44" height="44" rx="9" fill="#1a5c2a"/>
        <path d="M7 34 L18 13 L25 23 L29 16 L37 34Z" fill="#4caf63"/>
        <path d="M7 34 L18 13 L24 34Z" fill="white" opacity=".1"/>
      </svg>
      <div class="nb-brand">
        <b>HIKE<span>GEAR</span></b>
        <small>Adventure Starts Here</small>
      </div>
    </a>

    <div class="nb-search">
      <input type="text" id="q" placeholder="Cari produk pendakian..." autocomplete="off"/>
      <button onclick="cari()">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        Cari
      </button>
    </div>

    <div class="nb-actions">
      <a href="wishlist.php" class="nb-act">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        Wishlist
      </a>
      <a href="login.php" class="nb-act">
        <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Masuk / Daftar
      </a>
      <a href="keranjang.php" class="nb-act">
        <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="nb-badge" id="cartBadge">3</span>
        Keranjang
      </a>
    </div>
  </div>
</header>

<!-- ═══ SECONDARY NAV ═══════════════════════════════════ -->
<nav class="snav">
  <div class="snav-inner">
    <button class="snav-kat">
      <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      KATEGORI
      <div class="mega-menu">
        <?php foreach($kategori as $k): ?>
        <a href="kategori.php?nama=<?=urlencode($k['nama'])?>">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
          <?=$k['nama']?>
        </a>
        <?php endforeach; ?>
      </div>
    </button>
    <div class="snav-links">
      <a href="beranda.php" class="on">Beranda</a>
      <a href="kategori.php">Kategori</a>
      <a href="produk.php">Produk</a>
      <a href="promo.php">Promo</a>
      <a href="artikel.php">Artikel</a>
      <a href="tentang.php">Tentang Kami</a>
    </div>
  </div>
</nav>

<!-- ═══ MAIN CONTENT ════════════════════════════════════ -->
<main>

<!-- ── HERO SLIDER ───────────────────────────────────── -->
<div class="hero" id="heroBox">
  <div class="slides-wrap" id="slidesWrap">

    <div class="slide active">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1600&q=80')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-body">
        <span class="slide-tag">🏔️ Koleksi Terbaru 2025</span>
        <h1>PERLENGKAPAN<br>PENDAKIAN TERBAIK</h1>
        <h2>Untuk Setiap Petualanganmu</h2>
        <p>Temukan perlengkapan berkualitas tinggi untuk mendukung setiap langkah petualanganmu dengan aman dan nyaman.</p>
        <a href="produk.php" class="btn-hero">
          BELANJA SEKARANG
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1551632811-561732d1e306?w=1600&q=80')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-body">
        <span class="slide-tag">🔥 Flash Sale Hari Ini</span>
        <h1>DISKON HINGGA<br>50% OFF</h1>
        <h2>Tas Carrier &amp; Tenda Pilihan</h2>
        <p>Penawaran terbatas hari ini! Stok terbatas, segera dapatkan sebelum kehabisan.</p>
        <a href="promo.php" class="btn-hero">
          LIHAT PROMO
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?w=1600&q=80')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-body">
        <span class="slide-tag">⛺ Camping Season</span>
        <h1>PAKET CAMPING<br>LENGKAP</h1>
        <h2>Siap Untuk Alam Terbuka</h2>
        <p>Lengkapi perjalanan campingmu dengan paket tenda, sleeping bag, dan alat masak terbaik.</p>
        <a href="kategori.php?cat=camping" class="btn-hero">
          LIHAT PAKET
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

    <div class="slide">
      <div class="slide-bg" style="background-image:url('https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?w=1600&q=80')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-body">
        <span class="slide-tag">🧭 Member Exclusive</span>
        <h1>EIGER × HIKEGEAR<br>EXCLUSIVE</h1>
        <h2>Koleksi Eksklusif Member</h2>
        <p>Daftar sebagai member dan dapatkan akses eksklusif ke koleksi terbatas Eiger × HikeGear.</p>
        <a href="daftar.php" class="btn-hero">
          DAFTAR SEKARANG
          <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>

  </div><!-- /.slides-wrap -->

  <button class="sl-arrow prev" onclick="slide(-1)" aria-label="Sebelumnya">
    <svg viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
  </button>
  <button class="sl-arrow next" onclick="slide(1)" aria-label="Berikutnya">
    <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
  </button>
  <div class="sl-dots" id="dots">
    <button class="sl-dot on" onclick="goto(0)"></button>
    <button class="sl-dot" onclick="goto(1)"></button>
    <button class="sl-dot" onclick="goto(2)"></button>
    <button class="sl-dot" onclick="goto(3)"></button>
  </div>
</div>

<!-- ── TRUST BAR ─────────────────────────────────────── -->
<div class="trust-wrap">
  <div class="trust-bar">
    <?php
    $trusts = [
      ['<path d="M1 3h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',     'GRATIS ONGKIR',      'Min. belanja Rp200.000'],
      ['<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>','PRODUK ORIGINAL','100% Original'],
      ['<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013 4.18 2 2 0 014.18 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91A16 16 0 0014.09 16l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>','CUSTOMER SERVICE','Siap melayani 24/7'],
      ['<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',                          'GARANSI 100%',       'Uang kembali jika bermasalah'],
      ['<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>','PENGIRIMAN CEPAT','1–3 Hari Sampai'],
    ];
    foreach($trusts as $t): ?>
    <div class="trust-item">
      <div class="trust-ic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><?=$t[0]?></svg></div>
      <div class="trust-txt"><b><?=$t[1]?></b><span><?=$t[2]?></span></div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<div class="wrap">

  <!-- ── KATEGORI POPULER ─────────────────────────────── -->
  <div class="sec">
    <div class="sec-head">
      <h2 class="sec-title">Kategori Populer</h2>
      <a href="kategori.php" class="sec-more">Lihat Semua <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
    </div>
    <div class="kat-grid">
      <?php foreach($kategori as $k): ?>
      <a href="kategori.php?nama=<?=urlencode($k['nama'])?>" class="kat-card">
        <div class="kat-img-wrap">
          <img src="<?=$k['img']?>" alt="<?=$k['nama']?>" loading="lazy"/>
        </div>
        <div class="kat-lbl"><?=$k['nama']?></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── BANNER PROMO ──────────────────────────────────── -->
  <div class="promo-row">
    <div class="promo-card big">
      <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80" alt="Flash Sale"/>
      <div class="promo-shade"></div>
      <div class="promo-txt">
        <em>Flash Sale</em>
        <strong>Tas Carrier Diskon Hingga 40%</strong>
        <span>Stok terbatas, dapatkan sekarang</span>
      </div>
    </div>
    <div class="promo-card sm">
      <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=600&q=80" alt="Tenda"/>
      <div class="promo-shade"></div>
      <div class="promo-txt">
        <em>Weekend Deal</em>
        <strong>Tenda Mulai Rp500.000</strong>
      </div>
    </div>
    <div class="promo-card sm">
      <img src="https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=600&q=80" alt="Jaket"/>
      <div class="promo-shade"></div>
      <div class="promo-txt">
        <em>New Arrival</em>
        <strong>Jaket Terbaru 2025</strong>
      </div>
    </div>
  </div>

  <!-- ── PRODUK TERBARU ────────────────────────────────── -->
  <div class="sec">
    <div class="sec-head">
      <h2 class="sec-title">Produk Terbaru</h2>
      <a href="produk.php?sort=terbaru" class="sec-more">Lihat Semua <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
    </div>
    <div class="prod-grid">
      <?php foreach($produk_terbaru as $p): ?>
      <div class="prod-card">
        <div class="prod-pic">
          <?php if($p['badge']): ?>
          <span class="prod-badge <?= str_contains($p['badge'],'%')?'bg-disc':($p['badge']==='TERLARIS'?'bg-top':'bg-baru') ?>"><?=$p['badge']?></span>
          <?php endif; ?>
          <button class="prod-wish" onclick="wish(this,<?=$p['id']?>)" title="Wishlist">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          </button>
          <img src="<?=$p['gambar']?>" alt="<?=htmlspecialchars($p['nama'])?>" loading="lazy"/>
        </div>
        <div class="prod-body">
          <div class="prod-kat"><?=$p['kategori']?></div>
          <div class="prod-nama"><?=htmlspecialchars($p['nama'])?></div>
          <div class="prod-prices">
            <span class="prod-harga"><?=rupiah($p['harga'])?></span>
            <?php if($p['harga_coret']): ?>
            <span class="prod-coret"><?=rupiah($p['harga_coret'])?></span>
            <?php endif; ?>
          </div>
          <div class="prod-rating">
            <span class="stars">★★★★★</span>
            <span>(<?=$p['ulasan']?> ulasan)</span>
          </div>
          <button class="btn-add" onclick="addCart(<?=$p['id']?>,'<?=addslashes($p['nama'])?>')">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            + Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── PRODUK TERLARIS ───────────────────────────────── -->
  <div class="sec">
    <div class="sec-head">
      <h2 class="sec-title">Produk Terlaris</h2>
      <a href="produk.php?sort=terlaris" class="sec-more">Lihat Semua <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
    </div>
    <div class="prod-grid">
      <?php foreach($produk_terlaris as $p): ?>
      <div class="prod-card">
        <div class="prod-pic">
          <?php if($p['badge']): ?>
          <span class="prod-badge <?= str_contains($p['badge'],'%')?'bg-disc':($p['badge']==='TERLARIS'?'bg-top':'bg-baru') ?>"><?=$p['badge']?></span>
          <?php endif; ?>
          <button class="prod-wish" onclick="wish(this,<?=$p['id']?>)" title="Wishlist">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          </button>
          <img src="<?=$p['gambar']?>" alt="<?=htmlspecialchars($p['nama'])?>" loading="lazy"/>
        </div>
        <div class="prod-body">
          <div class="prod-kat"><?=$p['kategori']?></div>
          <div class="prod-nama"><?=htmlspecialchars($p['nama'])?></div>
          <div class="prod-prices">
            <span class="prod-harga"><?=rupiah($p['harga'])?></span>
            <?php if($p['harga_coret']): ?>
            <span class="prod-coret"><?=rupiah($p['harga_coret'])?></span>
            <?php endif; ?>
          </div>
          <div class="prod-rating">
            <span class="stars">★★★★★</span>
            <span>(<?=$p['ulasan']?> ulasan)</span>
          </div>
          <button class="btn-add" onclick="addCart(<?=$p['id']?>,'<?=addslashes($p['nama'])?>')">
            <svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            + Keranjang
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ── TIPS & ARTIKEL ────────────────────────────────── -->
  <div class="sec">
    <div class="sec-head">
      <h2 class="sec-title">Tips &amp; Artikel Pendakian</h2>
      <a href="artikel.php" class="sec-more">Lihat Semua <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg></a>
    </div>
    <div class="artikel-grid">
      <?php
      $artikels = [
        ['tag'=>'Tips Pendakian','judul'=>'10 Perlengkapan Wajib untuk Pendaki Pemula','tgl'=>'5 Jun 2025','img'=>'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=500&q=80'],
        ['tag'=>'Review Produk','judul'=>'Review Lengkap Eiger Mountaineer Jacket 2025','tgl'=>'1 Jun 2025','img'=>'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=500&q=80'],
        ['tag'=>'Destinasi','judul'=>'5 Gunung Terbaik untuk Pendaki Pemula di Jawa','tgl'=>'28 Mei 2025','img'=>'https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?w=500&q=80'],
        ['tag'=>'Keselamatan','judul'=>'Panduan Lengkap Keselamatan Pendakian Gunung','tgl'=>'20 Mei 2025','img'=>'https://images.unsplash.com/photo-1486870591958-9b9d0d1dda99?w=500&q=80'],
      ];
      foreach($artikels as $a): ?>
      <a href="artikel-detail.php" class="art-card">
        <div class="art-img"><img src="<?=$a['img']?>" alt="<?=htmlspecialchars($a['judul'])?>" loading="lazy"/></div>
        <div class="art-body">
          <div class="art-tag"><?=$a['tag']?></div>
          <div class="art-title"><?=htmlspecialchars($a['judul'])?></div>
          <div class="art-meta"><?=$a['tgl']?></div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

</div><!-- /.wrap -->
</main>

<!-- ═══ FOOTER ══════════════════════════════════════════ -->
<footer>
  <div class="ft-top">
    <div>
      <div class="ft-logo">
        <svg width="36" height="36" viewBox="0 0 44 44" fill="none"><rect width="44" height="44" rx="8" fill="#2d7a3a"/><path d="M7 34 L18 13 L25 23 L29 16 L37 34Z" fill="#4caf63"/></svg>
        <div><b>HIKE<span>GEAR</span></b><small style="display:block;font-size:.57rem;letter-spacing:2px;text-transform:uppercase;color:#6b7280">Adventure Starts Here</small></div>
      </div>
      <p class="ft-desc">Platform terpercaya untuk perlengkapan pendakian dan outdoor berkualitas. Temukan ribuan produk pilihan dari brand terbaik Indonesia dan dunia.</p>
      <div class="ft-socials">
        <a href="#" class="ft-soc"><svg viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
        <a href="#" class="ft-soc"><svg viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg></a>
        <a href="#" class="ft-soc"><svg viewBox="0 0 24 24"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg></a>
      </div>
    </div>
    <div class="ft-col"><h4>Produk</h4><ul><li><a href="#">Jaket Outdoor</a></li><li><a href="#">Tas Carrier</a></li><li><a href="#">Sepatu Gunung</a></li><li><a href="#">Tenda & Bivak</a></li><li><a href="#">Sleeping Bag</a></li><li><a href="#">Aksesoris</a></li></ul></div>
    <div class="ft-col"><h4>Layanan</h4><ul><li><a href="#">Tentang Kami</a></li><li><a href="#">Blog & Artikel</a></li><li><a href="#">Promo</a></li><li><a href="#">Lacak Pesanan</a></li><li><a href="#">Pusat Bantuan</a></li><li><a href="#">Kontak Kami</a></li></ul></div>
    <div class="ft-col"><h4>Informasi</h4><ul><li><a href="#">Kebijakan Privasi</a></li><li><a href="#">Syarat & Ketentuan</a></li><li><a href="#">Cara Pemesanan</a></li><li><a href="#">Metode Pembayaran</a></li><li><a href="#">Pengiriman</a></li><li><a href="#">Pengembalian</a></li></ul></div>
  </div>
  <div class="ft-bottom">
    <span>© <?=date('Y')?> HikeGear. Semua hak dilindungi.</span>
    <span>Made with ❤️ for adventurers across Indonesia</span>
  </div>
</footer>

<!-- TOAST -->
<div id="toast">
  <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
  <span id="toastTxt"></span>
</div>

<script>
/* ── HERO SLIDER ─────────────────────────────────────── */
let cur = 0, total = 4, timer;
const wrap  = document.getElementById('slidesWrap');
const dotEls= document.querySelectorAll('.sl-dot');
const slides= document.querySelectorAll('.slide');

function updateSlider(){
  wrap.style.transform = `translateX(-${cur*100}%)`;
  dotEls.forEach((d,i)=>d.classList.toggle('on',i===cur));
  slides.forEach((s,i)=>s.classList.toggle('active',i===cur));
}
function slide(d){ cur=(cur+d+total)%total; updateSlider(); resetTimer(); }
function goto(i){ cur=i; updateSlider(); resetTimer(); }
function resetTimer(){ clearInterval(timer); timer=setInterval(()=>slide(1),5500); }
resetTimer();

/* Touch swipe */
let tx=0;
document.getElementById('heroBox').addEventListener('touchstart',e=>tx=e.changedTouches[0].screenX,{passive:true});
document.getElementById('heroBox').addEventListener('touchend',e=>{
  const dx=tx-e.changedTouches[0].screenX;
  if(Math.abs(dx)>45) slide(dx>0?1:-1);
},{passive:true});

/* ── CART ───────────────────────────────────────────── */
let cnt=3;
function addCart(id,nama){
  cnt++;
  document.getElementById('cartBadge').textContent=cnt;
  toast('✔ '+nama+' ditambahkan ke keranjang');
  // fetch('api/cart.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,qty:1})});
}

/* ── WISHLIST ────────────────────────────────────────── */
function wish(btn,id){
  btn.classList.toggle('on');
  toast(btn.classList.contains('on')?'❤ Disimpan ke Wishlist':'Dihapus dari Wishlist');
}

/* ── SEARCH ─────────────────────────────────────────── */
function cari(){
  const q=document.getElementById('q').value.trim();
  if(q) location.href='produk.php?q='+encodeURIComponent(q);
}
document.getElementById('q').addEventListener('keydown',e=>{if(e.key==='Enter')cari();});

/* ── TOAST ──────────────────────────────────────────── */
let tTimer;
function toast(msg){
  const el=document.getElementById('toast');
  document.getElementById('toastTxt').textContent=msg;
  el.classList.add('show');
  clearTimeout(tTimer);
  tTimer=setTimeout(()=>el.classList.remove('show'),3000);
}
</script>
</body>
</html>