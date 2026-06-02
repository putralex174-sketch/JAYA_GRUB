<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit;
}

/* hapus item */
if(isset($_GET['hapus'])){
    $id = (int) $_GET['hapus'];

    mysqli_query($conn,
    "DELETE FROM keranjang WHERE id='$id'");

    header("Location: keranjang.php");
    exit;
}

/* ambil data keranjang join produk */
$data = mysqli_query($conn,"
SELECT keranjang.id,
       keranjang.qty,
       produk.nama,
       produk.harga,
       produk.gambar
FROM keranjang
JOIN produk ON keranjang.produk_id = produk.id
ORDER BY keranjang.id DESC
");

$totalBelanja = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang Belanja</title>

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

nav{
background:#111827;
padding:18px 40px;
display:flex;
justify-content:space-between;
align-items:center;
}

.logo{
font-size:28px;
font-weight:bold;
color:white;
}

.menu a{
color:white;
text-decoration:none;
margin-left:20px;
}

.container{
width:95%;
max-width:1200px;
margin:auto;
padding:30px;
}

.card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
margin-bottom:20px;
}

.item{
display:flex;
align-items:center;
justify-content:space-between;
padding:18px 0;
border-bottom:1px solid #e5e7eb;
gap:20px;
}

.item img{
width:100px;
height:100px;
object-fit:cover;
border-radius:14px;
}

.info{
flex:1;
}

.info h3{
font-size:20px;
margin-bottom:8px;
}

.price{
color:#2563eb;
font-weight:bold;
font-size:20px;
}

.qty{
font-size:15px;
color:#64748b;
margin-top:5px;
}

.subtotal{
font-weight:bold;
font-size:18px;
}

.btn-hapus{
background:#dc2626;
color:white;
padding:10px 14px;
border-radius:10px;
text-decoration:none;
}

.total-box{
text-align:right;
margin-top:20px;
}

.total-box h2{
font-size:30px;
color:#111827;
}

.checkout{
display:inline-block;
margin-top:15px;
background:#2563eb;
color:white;
padding:14px 20px;
border-radius:12px;
text-decoration:none;
font-weight:600;
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

<div class="card">
<h2>Keranjang Belanja 🛒</h2>

<?php if(mysqli_num_rows($data) > 0): ?>

<?php while($row = mysqli_fetch_assoc($data)): ?>

<?php
$subtotal = $row['harga'] * $row['qty'];
$totalBelanja += $subtotal;
?>

<div class="item">

<img src="../Gambar WhatsApp 2025-07-13 pukul 23.14.26_680c9a60.jpg" alt="Foto Baju" style="width:100px;height:100px;object-fit:cover;border-radius:14px;">

<div class="info">
<h3><?= $row['nama']; ?></h3>

<div class="price">
Rp <?= number_format($row['harga'],0,',','.'); ?>
</div>

<div class="qty">
Jumlah: <?= $row['qty']; ?>
</div>
</div>

<div class="subtotal">
Rp <?= number_format($subtotal,0,',','.'); ?>
</div>

<a
class="btn-hapus"
href="keranjang.php?hapus=<?= $row['id']; ?>"
onclick="return confirm('Hapus dari keranjang?')"
>
Hapus
</a>

</div>

<?php endwhile; ?>

<div class="total-box">

<h2>
Total:
Rp <?= number_format($totalBelanja,0,',','.'); ?>
</h2>

<a
class="checkout"
href="checkout.php"
>
Lanjut Checkout →
</a>

</div>

<?php else: ?>

<p style="margin-top:20px;">
Keranjang masih kosong.
</p>

<?php endif; ?>

</div>
</div>

</body>
</html>