<?php
session_start();

/* =========================
   INIT
========================= */

if (!isset($_SESSION['users'])) {

    $_SESSION['users'] = [
        "admin" => password_hash("12345", PASSWORD_DEFAULT)
    ];
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   LOGOUT
========================= */

if (isset($_GET['logout'])) {

    session_destroy();

    header("Location:index.php");
    exit;
}

/* =========================
   USER
========================= */

$user = $_SESSION['user'] ?? null;

/* =========================
   LOGIN
========================= */

$error = "";
$reg_error = "";

if (isset($_POST['login'])) {

    $u = trim($_POST['username']);
    $p = trim($_POST['password']);

    if (
        isset($_SESSION['users'][$u]) &&
        password_verify($p, $_SESSION['users'][$u])
    ) {

        session_regenerate_id(true);

        $_SESSION['login'] = true;
        $_SESSION['user'] = $u;

        if (!isset($_SESSION['cart'][$u])) {
            $_SESSION['cart'][$u] = [];
        }

        header("Location:index.php");
        exit;

    } else {

        $error = "Login gagal!";
    }
}

/* =========================
   REGISTER
========================= */

if (isset($_POST['register'])) {

    $u = trim($_POST['reg_username']);
    $p = trim($_POST['reg_password']);

    if ($u == "" || $p == "") {

        $reg_error = "Isi semua field!";
    }

    elseif (isset($_SESSION['users'][$u])) {

        $reg_error = "Username sudah dipakai!";
    }

    else {

        $_SESSION['users'][$u] =
        password_hash($p, PASSWORD_DEFAULT);

        $_SESSION['login'] = true;
        $_SESSION['user'] = $u;

        $_SESSION['cart'][$u] = [];

        header("Location:index.php");
        exit;
    }
}

/* =========================
   TAMBAH USER
========================= */

if (
    isset($_POST['tambah_user']) &&
    $user == "admin"
) {

    $newUser = trim($_POST['new_username']);
    $newPass = trim($_POST['new_password']);

    if (
        $newUser != "" &&
        $newPass != "" &&
        !isset($_SESSION['users'][$newUser])
    ) {

        $_SESSION['users'][$newUser] =
        password_hash($newPass, PASSWORD_DEFAULT);

        $_SESSION['cart'][$newUser] = [];
    }
}

/* =========================
   HAPUS USER
========================= */

if (
    isset($_GET['hapus_user']) &&
    $user == "admin"
) {

    $hapus = $_GET['hapus_user'];

    if ($hapus != "admin") {

        unset($_SESSION['users'][$hapus]);
        unset($_SESSION['cart'][$hapus]);
    }

    header("Location:index.php");
    exit;
}

/* =========================
   PRODUK
========================= */

$produk = [];

for ($i=1; $i<=10; $i++) {

    $produk["hp$i"] = [
        "nama" => "HP Android $i",
        "harga" => 1500000 + ($i * 250000),
        "kat" => "HP",
        "img" => "https://picsum.photos/400/300?random=$i"
    ];
}

for ($i=1; $i<=10; $i++) {

    $produk["lap$i"] = [
        "nama" => "Laptop $i",
        "harga" => 3000000 + ($i * 500000),
        "kat" => "Laptop",
        "img" => "https://picsum.photos/400/300?random=".($i+20)
    ];
}

for ($i=1; $i<=10; $i++) {

    $produk["fas$i"] = [
        "nama" => "Fashion $i",
        "harga" => 50000 + ($i * 10000),
        "kat" => "Fashion",
        "img" => "https://picsum.photos/400/300?random=".($i+40)
    ];
}

for ($i=1; $i<=10; $i++) {

    $produk["elec$i"] = [
        "nama" => "Elektronik $i",
        "harga" => 100000 + ($i * 50000),
        "kat" => "Elektronik",
        "img" => "https://picsum.photos/400/300?random=".($i+60)
    ];
}

/* =========================
   SEARCH + FILTER
========================= */

$q = $_GET['q'] ?? "";
$filter = $_GET['kat'] ?? "ALL";

/* =========================
   ADD TO CART
========================= */

if (isset($_POST['add']) && $user) {

    $id = $_POST['add'];

    if (isset($produk[$id])) {

        $_SESSION['cart'][$user][] = $id;
    }

    header("Location:index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

<title>BekasMart</title>

<meta name="viewport"
content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:Poppins;
    background:#f5f6f7;
}

/* HEADER */

.header{
    background:#ff5722;
    color:white;
    padding:12px 20px;
    display:flex;
    align-items:center;
    gap:15px;
    position:sticky;
    top:0;
    z-index:100;
}

.logo{
    font-size:22px;
    font-weight:bold;
}

.search{
    flex:1;
}

.search input{
    width:100%;
    padding:10px;
    border:none;
    border-radius:8px;
}

.user{
    white-space:nowrap;
}

/* SIDEBAR */

.sidebar{
    position:fixed;
    top:60px;
    left:0;
    width:200px;
    height:100%;
    background:#111827;
    padding:15px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:10px;
    margin-bottom:10px;
    border-radius:8px;
}

.sidebar a:hover{
    background:#ff5722;
}

/* MAIN */

.main{
    margin-left:220px;
    padding:20px;
}

/* BANNER */

.banner{
    background:linear-gradient(135deg,#ff9800,#ff5722);
    color:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:20px;
}

/* GRID */

.grid{
    display:grid;
    grid-template-columns:
    repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

/* CARD */

.card{
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-5px);
}

.card img{
    width:100%;
    height:180px;
    object-fit:cover;
}

.card-content{
    padding:15px;
}

.price{
    color:#ff5722;
    font-size:20px;
    font-weight:bold;
    margin:10px 0;
}

button{
    width:100%;
    border:none;
    background:#ff5722;
    color:white;
    padding:10px;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#e64a19;
}

/* LOGIN */

.login-box{
    width:320px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.login-box input{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:10px;
}

/* ADMIN */

.admin-panel{
    background:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.admin-panel input{
    width:100%;
    padding:10px;
    margin-bottom:10px;
    border:1px solid #ddd;
    border-radius:8px;
}

table{
    width:100%;
}

td,th{
    padding:10px;
}

/* MOBILE */

@media(max-width:768px){

    .sidebar{
        position:relative;
        width:100%;
        height:auto;
        top:0;
        display:flex;
        overflow:auto;
    }

    .main{
        margin-left:0;
    }

    .header{
        flex-direction:column;
        align-items:stretch;
    }
}

</style>

</head>

<body>

<?php if(!isset($_SESSION['login'])){ ?>

<div class="login-box">

<h2>Login / Register</h2>

<?php if($error){ ?>
<p style="color:red">
<?= $error ?>
</p>
<?php } ?>

<form method="POST">

<input
type="text"
name="username"
placeholder="Username"
required
>

<input
type="password"
name="password"
placeholder="Password"
required
>

<button
type="submit"
name="login"
>
Login
</button>

</form>

<hr>

<?php if($reg_error){ ?>
<p style="color:red">
<?= $reg_error ?>
</p>
<?php } ?>

<form method="POST">

<input
type="text"
name="reg_username"
placeholder="Username baru"
required
>

<input
type="password"
name="reg_password"
placeholder="Password baru"
required
>

<button
type="submit"
name="register"
>
Daftar
</button>

</form>

</div>

<?php } else { ?>

<!-- HEADER -->

<div class="header">

<div class="logo">
🛒 BekasMart
</div>

<div class="search">

<form method="GET">

<input
type="text"
name="q"
placeholder="Cari produk..."
value="<?= htmlspecialchars($q) ?>"
>

</form>

</div>

<div class="user">

👤 <?= htmlspecialchars($user) ?>

|

🛍️
<?= count($_SESSION['cart'][$user] ?? []) ?>

|

<a
href="?logout=true"
style="color:white"
>
Logout
</a>

</div>

</div>

<!-- SIDEBAR -->

<div class="sidebar">

<a href="?kat=ALL">🏠 Home</a>

<a href="?kat=HP">📱 HP</a>

<a href="?kat=Laptop">💻 Laptop</a>

<a href="?kat=Fashion">👕 Fashion</a>

<a href="?kat=Elektronik">🎧 Elektronik</a>

</div>

<!-- MAIN -->

<div class="main">

<?php if($user == "admin"){ ?>

<div class="admin-panel">

<h2>⚙️ Admin Panel</h2>

<form method="POST">

<h3>Tambah User</h3>

<input
type="text"
name="new_username"
placeholder="Username baru"
required
>

<input
type="password"
name="new_password"
placeholder="Password baru"
required
>

<button
type="submit"
name="tambah_user"
>
Tambah User
</button>

</form>

<hr>

<h3>Daftar User</h3>

<table>

<tr style="background:#f5f5f5">

<th align="left">Username</th>
<th align="left">Action</th>

</tr>

<?php foreach($_SESSION['users'] as $uname => $upass){ ?>

<tr>

<td>
<?= htmlspecialchars($uname) ?>
</td>

<td>

<?php if($uname != "admin"){ ?>

<a
href="?hapus_user=<?= $uname ?>"
onclick="return confirm('Hapus user ini?')"
>

<button style="
background:red;
width:auto;
padding:8px 15px;
">
Hapus
</button>

</a>

<?php } else { ?>

<b>SUPER ADMIN</b>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } ?>

<!-- BANNER -->

<div class="banner">

<h2>🔥 Promo Hari Ini</h2>

<p>Diskon hingga 70%</p>

</div>

<h2>Produk</h2>

<div class="grid">

<?php foreach($produk as $id => $p){ ?>

<?php

$cocokKategori =
($filter == "ALL" || $p['kat'] == $filter);

$cocokSearch =
($q == "" ||
stripos($p['nama'],$q) !== false);

if($cocokKategori && $cocokSearch){

?>

<div class="card">

<img src="<?= $p['img'] ?>">

<div class="card-content">

<h3>
<?= htmlspecialchars($p['nama']) ?>
</h3>

<div class="price">

Rp
<?= number_format($p['harga']) ?>

</div>

<form method="POST">

<input
type="hidden"
name="add"
value="<?= $id ?>"
>

<button type="submit">
+ Keranjang
</button>

</form>

</div>

</div>

<?php } ?>

<?php } ?>

</div>

</div>

<?php } ?>

</body>
</html>