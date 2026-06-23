<?php
session_start();

$error = '';
$success = '';

// ── Handle login submission ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';
    $remember   = isset($_POST['remember']);

    // Dummy user database (gantilah dengan query ke database sungguhan)
    $dummy_users = [
        ['email'=>'budi@gmail.com', 'hp'=>'081234567890', 'password'=>'password123', 'nama'=>'Budi Santoso'],
    ];

    $found = null;
    foreach ($dummy_users as $u) {
        if ($identifier === $u['email'] || $identifier === $u['hp']) { $found = $u; break; }
    }

    if (!$identifier || !$password) {
        $error = 'Email/No. HP dan password wajib diisi.';
    } elseif (!$found) {
        $error = 'Akun tidak ditemukan. Periksa kembali email atau nomor HP Anda.';
    } elseif ($found['password'] !== $password) {
        $error = 'Password yang Anda masukkan salah.';
    } else {
        $_SESSION['user'] = $found['nama'];
        if ($remember) {
setcookie('remember_identifier', $identifier, time() + (86400 * 30), "/");
        }
        header('Location: /'); // arahkan ke beranda setelah berhasil login
        exit;
    }
}

$remembered = $_COOKIE['remember_identifier'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk – HikeGear</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --green-dark:#1a4731;--green:#1e6b3c;--green-mid:#2d8653;--green-light:#e8f5ee;
  --accent:#27ae60;--accent-hover:#1f9e53;
  --white:#fff;--gray-50:#f8fafb;--gray-100:#f0f4f2;--gray-200:#e0e8e4;
  --gray-400:#9ab0a4;--gray-600:#5a7568;--gray-800:#2c3e35;--text:#1a2e25;
  --red:#e74c3c;--red-light:#fdecea;
  --radius:14px;--shadow-sm:0 1px 3px rgba(0,0,0,.08);
  --shadow-lg:0 20px 50px rgba(0,0,0,.18);
  --tr:.22s ease;--font:'Segoe UI',system-ui,sans-serif;
}
html{scroll-behavior:smooth}
body{font-family:var(--font);background:var(--gray-50);color:var(--text);font-size:14px;line-height:1.5}
a{text-decoration:none;color:inherit}
img{display:block;width:100%;object-fit:cover}
button{cursor:pointer;border:none;font-family:inherit}
input{font-family:inherit}

/* ── Header ── */
header{background:white;padding:16px 40px;display:flex;align-items:center;gap:30px;border-bottom:1px solid var(--gray-100)}
.logo{display:flex;align-items:center;gap:10px;flex-shrink:0}
.logo-icon{width:40px;height:40px;background:var(--green);border-radius:8px;display:flex;align-items:center;justify-content:center}
.logo-icon svg{width:22px;height:22px;fill:none;stroke:white;stroke-width:2}
.logo-text strong{font-size:17px;font-weight:800;color:var(--green-dark);letter-spacing:-.3px}
.logo-text small{font-size:9px;color:var(--gray-600);letter-spacing:1.5px;text-transform:uppercase;display:block}
.nav-links{display:flex;gap:28px}
.nav-links a{font-size:14px;font-weight:500;color:var(--gray-800);transition:color var(--tr)}
.nav-links a:hover{color:var(--accent)}
.header-actions{display:flex;align-items:center;gap:22px;margin-left:auto}
.haction{display:flex;align-items:center;gap:6px;font-size:13.5px;font-weight:500;color:var(--gray-800);transition:color var(--tr);position:relative}
.haction:hover{color:var(--accent)}
.haction svg{width:19px;height:19px;stroke:currentColor;fill:none;stroke-width:1.8}
.cart-badge{position:absolute;top:-7px;right:-9px;background:var(--accent);color:white;font-size:10px;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center}

/* ── Hero Split ── */
.hero-split{display:grid;grid-template-columns:1fr 540px;min-height:calc(100vh - 73px - 90px)}

/* left panel */
.hero-left{position:relative;overflow:hidden;display:flex;align-items:center}
.hero-left img{position:absolute;inset:0;height:100%;object-fit:cover}
.hero-left::after{content:'';position:absolute;inset:0;background:linear-gradient(to right,rgba(8,20,14,.78) 35%,rgba(8,20,14,.25))}
.hero-left-content{position:relative;z-index:2;padding:0 60px;max-width:520px}
.hero-left-content h1{font-size:36px;font-weight:900;color:white;letter-spacing:-.5px;margin-bottom:8px;line-height:1.15}
.hero-left-content .sub{font-size:18px;font-weight:700;color:#dfeee4;margin-bottom:14px}
.hero-left-content .sub span{color:var(--accent)}
.hero-left-content .desc{font-size:13.5px;color:#c2dccb;line-height:1.7;margin-bottom:34px;max-width:400px}

.feature-list{display:flex;flex-direction:column;gap:18px}
.feature-item{display:flex;align-items:flex-start;gap:13px}
.feature-icon{width:38px;height:38px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.feature-icon svg{width:18px;height:18px;stroke:var(--accent);fill:none;stroke-width:2}
.feature-text strong{font-size:13.5px;color:white;display:block;margin-bottom:2px}
.feature-text span{font-size:12px;color:#aecfbb;line-height:1.5}

/* right panel: form */
.hero-right{display:flex;align-items:center;justify-content:center;padding:50px 40px;background:var(--gray-50)}
.login-card{background:white;border-radius:var(--radius);box-shadow:var(--shadow-lg);padding:40px 44px;width:100%;max-width:460px;border:1px solid var(--gray-100)}
.login-card h2{font-size:23px;font-weight:800;color:var(--gray-800);text-align:center;margin-bottom:6px}
.login-card .lc-sub{font-size:13px;color:var(--gray-600);text-align:center;margin-bottom:26px}

.alert{padding:11px 14px;border-radius:9px;font-size:12.5px;margin-bottom:18px;display:flex;align-items:flex-start;gap:8px}
.alert svg{width:16px;height:16px;flex-shrink:0;margin-top:1px;fill:none;stroke-width:2.2}
.alert-error{background:var(--red-light);color:var(--red)}
.alert-error svg{stroke:var(--red)}
.alert-success{background:var(--green-light);color:var(--green)}
.alert-success svg{stroke:var(--green)}

.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:13px;font-weight:700;color:var(--gray-800);margin-bottom:7px}
.input-wrap{position:relative;display:flex;align-items:center}
.input-wrap svg.input-icon{position:absolute;left:13px;width:17px;height:17px;stroke:var(--gray-400);fill:none;stroke-width:1.8;pointer-events:none}
.input-wrap input{width:100%;padding:11px 14px 11px 40px;border:1.5px solid var(--gray-200);border-radius:9px;font-size:13.5px;outline:none;transition:border-color var(--tr);background:var(--gray-50)}
.input-wrap input:focus{border-color:var(--accent);background:white}
.toggle-pass{position:absolute;right:13px;width:18px;height:18px;cursor:pointer;background:none;border:none;padding:0}
.toggle-pass svg{width:18px;height:18px;stroke:var(--gray-400);fill:none;stroke-width:1.8;transition:stroke var(--tr)}
.toggle-pass:hover svg{stroke:var(--gray-600)}

.form-row-between{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.remember-check{display:flex;align-items:center;gap:7px;font-size:13px;color:var(--gray-600);cursor:pointer}
.remember-check input{width:16px;height:16px;accent-color:var(--accent);cursor:pointer}
.forgot-link{font-size:13px;font-weight:600;color:var(--green);transition:color var(--tr)}
.forgot-link:hover{color:var(--accent-hover);text-decoration:underline}

.btn-submit{width:100%;padding:13px;background:var(--green);color:white;border-radius:9px;font-size:14.5px;font-weight:700;transition:background var(--tr);margin-bottom:20px}
.btn-submit:hover{background:var(--green-mid)}

.divider{display:flex;align-items:center;gap:12px;margin-bottom:20px}
.divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--gray-200)}
.divider span{font-size:12px;color:var(--gray-400);white-space:nowrap}

.social-row{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:22px}
.social-btn{display:flex;align-items:center;justify-content:center;gap:7px;padding:10px;border:1.5px solid var(--gray-200);border-radius:9px;font-size:12.5px;font-weight:600;color:var(--gray-800);transition:all var(--tr);background:white}
.social-btn:hover{background:var(--gray-50);border-color:var(--gray-300)}
.social-btn svg{width:17px;height:17px;flex-shrink:0}

.signup-text{text-align:center;font-size:13px;color:var(--gray-600)}
.signup-text a{color:var(--green);font-weight:700;transition:color var(--tr)}
.signup-text a:hover{color:var(--accent-hover);text-decoration:underline}

/* ── Trust bar ── */
.trust-bar{background:white;border-top:1px solid var(--gray-200);padding:22px 40px}
.trust-inner{max-width:1340px;margin:0 auto;display:flex;align-items:center;justify-content:space-around;flex-wrap:wrap;gap:18px}
.trust-item{display:flex;align-items:center;gap:12px}
.trust-icon{width:38px;height:38px;background:var(--green-light);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.trust-icon svg{width:19px;height:19px;stroke:var(--green);fill:none;stroke-width:1.8}
.trust-item h5{font-size:13px;font-weight:700;color:var(--gray-800)}
.trust-item p{font-size:11.5px;color:var(--gray-600)}

/* responsive */
@media(max-width:1000px){
  .hero-split{grid-template-columns:1fr}
  .hero-left{display:none}
  .hero-right{padding:40px 20px}
}
@media(max-width:600px){
  header{padding:14px 18px;flex-wrap:wrap}
  .nav-links{display:none}
  .header-actions{gap:14px}
  .haction span{display:none}
  .login-card{padding:28px 22px}
  .social-row{grid-template-columns:1fr}
  .trust-inner{flex-direction:column;align-items:flex-start}
}
</style>
</head>
<body>

<!-- Header -->
<header>
  <a href="USER/BRANDA.PHP" class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><polyline points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg></div>
    <div class="logo-text"><strong>HIKEGEAR</strong><small>Adventure Starts Here</small></div>
  </a>
  <div class="nav-links">
    <a href="#">Beranda</a>
    <a href="kategori.php">Kategori</a>
    <a href="produk.php">Produk</a>
    <a href="promo.php">Promo</a>
    <a href="artikel.php">Artikel</a>
    <a href="tentang.php">Tentang Kami</a>
  </div>
  <div class="header-actions">
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg></a>
    <a class="haction" href="#"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><span>Wishlist</span></a>
    <a class="haction" href="LOGIN.PHP"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Masuk / Daftar</span></a>
    <a class="haction" href="#" style="position:relative"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg><span>Keranjang</span><span class="cart-badge">3</span></a>
  </div>
</header>

<!-- Hero Split -->
<div class="hero-split">

  <!-- LEFT: Image + Welcome -->
  <div class="hero-left">
    <img src="https://images.unsplash.com/photo-1551632811-561732d1e306?w=1000&q=80" alt="Pendaki Gunung">
    <div class="hero-left-content">
      <h1>Selamat Datang Kembali!</h1>
      <div class="sub">Masuk untuk melanjutkan <span>petualanganmu</span></div>
      <p class="desc">Temukan perlengkapan pendakian berkualitas untuk setiap petualanganmu bersama HikeGear.</p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></div>
          <div class="feature-text"><strong>Aman & Terpercaya</strong><span>Belanja aman dengan sistem keamanan terbaik</span></div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
          <div class="feature-text"><strong>Promo Eksklusif</strong><span>Dapatkan penawaran spesial untuk member</span></div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
          <div class="feature-text"><strong>Pesanan Mudah</strong><span>Lacak pesanan dan riwayat belanja dengan mudah</span></div>
        </div>
        <div class="feature-item">
          <div class="feature-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16"/></svg></div>
          <div class="feature-text"><strong>Layanan 24/7</strong><span>Customer service siap membantumu kapan saja</span></div>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Login Form -->
  <div class="hero-right">
    <div class="login-card">
      <h2>Masuk ke Akun Anda</h2>
      <p class="lc-sub">Silakan masuk untuk melanjutkan</p>

      <?php if ($error): ?>
      <div class="alert alert-error">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span><?= htmlspecialchars($error) ?></span>
      </div>
      <?php endif; ?>
      <?php if ($success): ?>
      <div class="alert alert-success">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        <span><?= htmlspecialchars($success) ?></span>
      </div>
      <?php endif; ?>

      <form method="POST" action="" id="loginForm">
        <div class="form-group">
          <label>Email atau Nomor HP</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="text" name="identifier" placeholder="contoh@email.com atau 0812xxxxxxx" value="<?= htmlspecialchars($remembered) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" name="password" id="passwordInput" placeholder="Masukkan password Anda" required>
            <button type="button" class="toggle-pass" onclick="togglePassword()">
              <svg id="eyeIcon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="form-row-between">
          <label class="remember-check">
            <input type="checkbox" name="remember" <?= $remembered ? 'checked' : '' ?>>
            Ingat saya
          </label>
          <a href="lupa-password.php" class="forgot-link">Lupa password?</a>
        </div>

        <button type="submit" class="btn-submit">Masuk</button>
      </form>

      <div class="divider"><span>atau masuk dengan</span></div>

      <div class="social-row">
        <a href="#" class="social-btn">
          <svg viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
          Google
        </a>
        <a href="#" class="social-btn">
          <svg viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.96H15.8c-1.5 0-1.96.93-1.96 1.89v2.26h3.32l-.53 3.49h-2.8v8.44C19.62 23.08 24 18.09 24 12.07z"/></svg>
          Facebook
        </a>
        <a href="#" class="social-btn">
          <svg viewBox="0 0 24 24"><path d="M16.365 1.43c0 1.14-.493 2.27-1.177 3.08-.744.9-1.99 1.57-2.987 1.57-.12 0-.23-.02-.3-.03-.014-.1-.04-.31-.04-.52 0-1.14.572-2.27 1.206-2.98.78-.94 2.09-1.64 3.13-1.68.027.13.04.27.04.4.014.05.014.1.014.16zm4.565 15.71c-.03.07-.463 1.58-1.518 3.12-.94 1.36-1.92 2.72-3.45 2.75-1.49.03-1.97-.88-3.68-.88-1.71 0-2.24.85-3.65.91-1.46.06-2.58-1.47-3.53-2.82-1.93-2.76-3.41-7.79-1.42-11.19.97-1.68 2.7-2.74 4.6-2.77 1.46-.02 2.83.99 3.68.99.85 0 2.5-1.22 4.21-1.04.72.03 2.73.29 4.13 2.18-.1.07-2.47 1.44-2.44 4.31.04 3.45 3.02 4.61 3.05 4.43z"/></svg>
          Apple
        </a>
      </div>

      <p class="signup-text">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
    </div>
  </div>

</div>

<!-- Trust Bar -->
<div class="trust-bar">
  <div class="trust-inner">
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
      <div><h5>100% Produk Original</h5><p>Jaminan produk original dan berkualitas</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
      <div><h5>Pengiriman Cepat</h5><p>1-3 hari sampai ke seluruh Indonesia</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg></div>
      <div><h5>Garansi Resmi</h5><p>Garansi resmi untuk semua produk</p></div>
    </div>
    <div class="trust-item">
      <div class="trust-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16"/></svg></div>
      <div><h5>Customer Service 24/7</h5><p>Siap membantu kapan saja</p></div>
    </div>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('passwordInput');
  const icon  = document.getElementById('eyeIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
  } else {
    input.type = 'password';
    icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
  }
}

// Simple client-side validation feedback
document.getElementById('loginForm').addEventListener('submit', function(e) {
  const identifier = this.identifier.value.trim();
  const password    = this.password.value;
  if (!identifier || !password) {
    e.preventDefault();
    alert('Mohon lengkapi email/nomor HP dan password.');
  }
});
</script>
</body>
</html>