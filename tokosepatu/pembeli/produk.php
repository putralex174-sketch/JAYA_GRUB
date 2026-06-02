<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit;
}

/* tambah ke keranjang */
if(isset($_GET['beli'])){
    $id = (int) $_GET['beli'];

    $cek = mysqli_query($conn,
    "SELECT * FROM keranjang WHERE produk_id='$id'");

    if(mysqli_num_rows($cek) > 0){
        mysqli_query($conn,"
        UPDATE keranjang
        SET qty = qty + 1
        WHERE produk_id='$id'
        ");
    }else{
        mysqli_query($conn,"
        INSERT INTO keranjang(produk_id,qty)
        VALUES('$id','1')
        ");
    }

    header("Location: produk.php");
    exit;
}

$data = mysqli_query($conn,
"SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Produk Toko Baju</title>

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Segoe UI;
}

body{
background:#f8fafc;
}

/* NAVBAR */
nav{
background:#111827;
padding:18px 40px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logo{
color:white;
font-size:28px;
font-weight:bold;
}

.menu a{
color:white;
text-decoration:none;
margin-left:20px;
font-size:15px;
}

.menu a:hover{
color:#60a5fa;
}

/* CONTAINER */
.container{
width:95%;
max-width:1300px;
margin:auto;
padding:35px 0;
}

/* JUDUL */
.title{
font-size:34px;
margin-bottom:25px;
}

/* GRID PRODUK */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:25px;
}

/* CARD */
.card{
background:white;
border-radius:20px;
overflow:hidden;
box-shadow:0 10px 25px rgba(0,0,0,.08);
transition:.25s;
}

.card:hover{
transform:translateY(-8px);
}

.card img{
width:100%;
height:240px;
object-fit:cover;
}

.card-body{
padding:20px;
}

.card-body h3{
font-size:20px;
margin-bottom:10px;
}

.price{
font-size:24px;
font-weight:bold;
color:#2563eb;
margin-bottom:10px;
}

.stock{
font-size:14px;
color:#64748b;
margin-bottom:16px;
}

.btn{
display:block;
text-align:center;
background:#2563eb;
color:white;
padding:12px;
border-radius:10px;
text-decoration:none;
font-weight:600;
}

.btn:hover{
background:#1d4ed8;
}
</style>
</head>
<body>

<nav>
<div class="logo">👕 Toko Baju</div>

<div class="menu">
<a href="dashboard.php">Beranda</a>
<a href="produk.php">Produk</a>
<a href="keranjang.php">Keranjang 🛒</a>
<a href="checkout.php">Checkout</a>
<a href="../logout.php">Logout</a>
</div>
</nav>

<div class="container">

<h1 class="title">Koleksi Baju Terbaru</h1>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($data)): ?>

<div class="card">

<?php
// Semua produk ditampilkan dengan foto baju (bukan sepatu)
// Catatan: per file gambar baju bisa diganti sesuai kebutuhan.
$bajuGambar = "../Gambar WhatsApp 2025-07-13 pukul 23.14.26_680c9a60.jpg";
?>
<img src="<?= $bajuGambar; ?>" alt="Foto Baju">

<div class="card-body">

<h3><?= $row['nama']; ?></h3>

<div class="price">
Rp <?= number_format($row['harga'],0,',','.'); ?>
</div>

<div class="stock">
Stok tersedia: <?= $row['stok']; ?>
</div>

<a
class="btn"
href="produk.php?beli=<?= $row['id']; ?>"
>
Tambah ke Keranjang 🛒
</a>

</div>
</div>

<?php endwhile; ?>

</div>
</div>

</body>
</html>