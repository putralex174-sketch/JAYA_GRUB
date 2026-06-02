<?php
session_start();

if(!isset($_SESSION['email']) || $_SESSION['role']!='admin'){
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard Admin Toko Baju</title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI;
}

body{
background:#f1f5f9;
}

nav{
background:#0f172a;
padding:18px 40px;
display:flex;
justify-content:space-between;
align-items:center;
}

nav h2{
color:white;
}

nav a{
color:white;
text-decoration:none;
margin-left:20px;
}

.container{
width:92%;
margin:auto;
padding:30px 0;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:20px;
}

.card{
background:white;
padding:25px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.card h3{
font-size:32px;
color:#2563eb;
margin-bottom:8px;
}
</style>
</head>
<body>

<nav>
<h2>👕 Admin Toko Baju</h2>

<div>
<a href="dashboard.php">Dashboard</a>
<a href="produk.php">Produk</a>
<a href="pesanan.php">Pesanan</a>
<a href="../logout.php">Logout</a>
</div>
</nav>

<div class="container">

<h1 style="margin-bottom:25px;">Dashboard Admin</h1>

<div class="grid">

<div class="card">
<h3>120</h3>
<p>Total Produk</p>
</div>

<div class="card">
<h3>35</h3>
<p>Pesanan Masuk</p>
</div>

<div class="card">
<h3>Rp 12.500.000</h3>
<p>Total Penjualan</p>
</div>

<div class="card">
<h3>24</h3>
<p>Customer Aktif</p>
</div>

</div>

</div>

</body>
</html>