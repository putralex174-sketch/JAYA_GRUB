<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - HikeGear</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{
background:#f5f5f5;
}

/* =====================
NAVBAR
===================== */

.navbar{
background:white;
height:80px;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 50px;
box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.logo{
display:flex;
align-items:center;
gap:10px;
}

.logo h2{
color:#0d6b22;
}

.logo span{
color:black;
}

.menu{
display:flex;
gap:35px;
}

.menu a{
text-decoration:none;
color:#222;
font-weight:500;
}

.menu a:hover{
color:#0d6b22;
}

.right-menu{
display:flex;
align-items:center;
gap:25px;
}

.right-menu a{
text-decoration:none;
color:#222;
}

/* =====================
HERO
===================== */

.hero{
height:calc(100vh - 80px);
background:url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1600') center/cover;
position:relative;
display:flex;
align-items:center;
justify-content:space-between;
padding:60px;
}

.overlay{
position:absolute;
inset:0;
background:rgba(0,0,0,.45);
}

.left-content{
position:relative;
color:white;
width:45%;
z-index:2;
}

.left-content h1{
font-size:60px;
margin-bottom:15px;
}

.left-content h1 span{
color:#4CAF50;
}

.left-content p{
font-size:22px;
line-height:1.7;
margin-bottom:40px;
}

.features{
display:flex;
flex-direction:column;
gap:25px;
}

.feature{
display:flex;
gap:15px;
align-items:flex-start;
}

.feature i{
width:50px;
height:50px;
background:rgba(76,175,80,.2);
border-radius:10px;
display:flex;
justify-content:center;
align-items:center;
font-size:20px;
color:#4CAF50;
}

/* =====================
LOGIN CARD
===================== */

.login-card{
position:relative;
z-index:2;
width:650px;
background:white;
padding:50px;
border-radius:25px;
box-shadow:0 10px 30px rgba(0,0,0,.2);
}

.login-card h2{
text-align:center;
font-size:40px;
margin-bottom:10px;
}

.login-card p{
text-align:center;
color:#666;
margin-bottom:35px;
}

.form-group{
margin-bottom:20px;
}

.form-group label{
display:block;
margin-bottom:8px;
font-weight:600;
}

.input-box{
position:relative;
}

.input-box input{
width:100%;
padding:15px 50px;
border:1px solid #ddd;
border-radius:10px;
font-size:16px;
}

.input-box i{
position:absolute;
left:15px;
top:17px;
color:#888;
}

.toggle{
position:absolute;
right:15px;
top:17px;
cursor:pointer;
}

.row{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:25px;
}

.row a{
color:#0d6b22;
text-decoration:none;
}

.btn-login{
width:100%;
padding:16px;
border:none;
background:#0d6b22;
color:white;
font-size:18px;
font-weight:bold;
border-radius:10px;
cursor:pointer;
transition:.3s;
}

.btn-login:hover{
background:#095119;
}

.or{
text-align:center;
margin:25px 0;
color:#777;
position:relative;
}

.or::before{
content:'';
position:absolute;
width:35%;
height:1px;
background:#ddd;
left:0;
top:50%;
}

.or::after{
content:'';
position:absolute;
width:35%;
height:1px;
background:#ddd;
right:0;
top:50%;
}

.social{
display:flex;
gap:15px;
}

.social button{
flex:1;
padding:14px;
background:white;
border:1px solid #ddd;
border-radius:10px;
cursor:pointer;
font-size:16px;
}

.register{
text-align:center;
margin-top:25px;
font-size:18px;
}

.register a{
color:#0d6b22;
font-weight:bold;
text-decoration:none;
}

/* =====================
BOTTOM INFO
===================== */

.info{
background:white;
padding:30px;
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
}

.info-box{
display:flex;
align-items:center;
gap:15px;
}

.info-box i{
font-size:35px;
color:#0d6b22;
}

/* =====================
RESPONSIVE
===================== */

@media(max-width:1200px){

.hero{
flex-direction:column;
height:auto;
gap:40px;
padding:40px 20px;
}

.left-content{
width:100%;
}

.login-card{
width:100%;
}

.info{
grid-template-columns:1fr 1fr;
}
}

@media(max-width:768px){

.navbar{
flex-direction:column;
height:auto;
padding:20px;
gap:20px;
}

.menu{
flex-wrap:wrap;
justify-content:center;
}

.left-content h1{
font-size:40px;
}

.info{
grid-template-columns:1fr;
}

.social{
flex-direction:column;
}
}

</style>
</head>

<body>

<!-- NAVBAR -->

<nav class="navbar">

<div class="logo">
<h2>HIKE<span>GEAR</span></h2>
</div>

<div class="menu">
<a href="#">Beranda</a>
<a href="#">Kategori</a>
<a href="#">Produk</a>
<a href="#">Promo</a>
<a href="#">Artikel</a>
<a href="#">Tentang Kami</a>
</div>

<div class="right-menu">
<a href="#"><i class="fa fa-search"></i></a>
<a href="#"><i class="fa-regular fa-heart"></i></a>
<a href="#">Masuk / Daftar</a>
<a href="#"><i class="fa fa-cart-shopping"></i></a>
</div>

</nav>

<!-- HERO -->

<section class="hero">

<div class="overlay"></div>

<div class="left-content">

<h1>Selamat Datang Kembali!</h1>

<p>
Masuk untuk melanjutkan
<span style="color:#4CAF50">petualanganmu</span>
</p>

<div class="features">

<div class="feature">
<i class="fa-solid fa-shield-halved"></i>
<div>
<h3>Aman & Terpercaya</h3>
<p>Belanja aman dengan sistem terbaik.</p>
</div>
</div>

<div class="feature">
<i class="fa-solid fa-tags"></i>
<div>
<h3>Promo Eksklusif</h3>
<p>Dapatkan promo spesial member.</p>
</div>
</div>

<div class="feature">
<i class="fa-solid fa-box"></i>
<div>
<h3>Pesanan Mudah</h3>
<p>Lacak pesanan kapan saja.</p>
</div>
</div>

</div>

</div>

<!-- LOGIN -->

<div class="login-card">

<h2>Masuk ke Akun Anda</h2>
<p>Silakan masuk untuk melanjutkan</p>

<form method="POST">

<div class="form-group">
<label>Email atau Nomor HP</label>

<div class="input-box">
<i class="fa fa-envelope"></i>
<input type="text" name="email"
placeholder="contoh@email.com">
</div>
</div>

<div class="form-group">
<label>Password</label>

<div class="input-box">
<i class="fa fa-lock"></i>

<input type="password"
id="password"
name="password"
placeholder="Masukkan password">

<i class="fa-solid fa-eye toggle"
onclick="togglePassword()"></i>

</div>
</div>

<div class="row">
<label>
<input type="checkbox">
Ingat Saya
</label>

<a href="#">Lupa Password?</a>
</div>

<button class="btn-login">
Masuk
</button>

</form>

<div class="or">
atau masuk dengan
</div>

<div class="social">

<button>
<i class="fab fa-google"></i>
Google
</button>

<button>
<i class="fab fa-facebook"></i>
Facebook
</button>

<button>
<i class="fab fa-apple"></i>
Apple
</button>

</div>

<div class="register">
Belum punya akun?
<a href="#">Daftar Sekarang</a>
</div>

</div>

</section>

<!-- INFO -->

<section class="info">

<div class="info-box">
<i class="fa-solid fa-shield"></i>
<div>
<h3>100% Produk Original</h3>
<p>Produk resmi dan berkualitas</p>
</div>
</div>

<div class="info-box">
<i class="fa-solid fa-truck"></i>
<div>
<h3>Pengiriman Cepat</h3>
<p>1-3 Hari Seluruh Indonesia</p>
</div>
</div>

<div class="info-box">
<i class="fa-solid fa-award"></i>
<div>
<h3>Garansi Resmi</h3>
<p>Garansi resmi produk</p>
</div>
</div>

<div class="info-box">
<i class="fa-solid fa-headset"></i>
<div>
<h3>Customer Service 24/7</h3>
<p>Siap membantu kapan saja</p>
</div>
</div>

</section>

<script>

function togglePassword(){

let password =
document.getElementById("password");

if(password.type === "password"){
password.type = "text";
}else{
password.type = "password";
}

}

</script>

</body>
</html>