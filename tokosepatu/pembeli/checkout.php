<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit;
}

/* hitung total keranjang */
$keranjang = mysqli_query($conn,"
SELECT keranjang.qty,
       produk.harga
FROM keranjang
JOIN produk ON keranjang.produk_id = produk.id
");

$totalBelanja = 0;

while($k = mysqli_fetch_assoc($keranjang)){
    $totalBelanja += $k['harga'] * $k['qty'];
}

/* proses checkout */
if(isset($_POST['checkout'])){

    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $nohp       = $_POST['nohp'];
    $pembayaran = $_POST['pembayaran'];

    mysqli_query($conn,"
    INSERT INTO pesanan
    (nama_pelanggan,alamat,no_hp,pembayaran,total,status)
    VALUES
    ('$nama','$alamat','$nohp','$pembayaran','$totalBelanja','Menunggu')
    ");

    mysqli_query($conn,"DELETE FROM keranjang");

    header("Location: checkout.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Checkout</title>

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
max-width:800px;
margin:auto;
padding:30px;
}

.card{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

h2{
margin-bottom:20px;
}

input, textarea, select{
width:100%;
padding:14px;
margin-top:10px;
margin-bottom:18px;
border:1px solid #d1d5db;
border-radius:12px;
font-size:15px;
}

button{
width:100%;
padding:14px;
background:#2563eb;
color:white;
border:none;
border-radius:12px;
font-size:16px;
font-weight:600;
cursor:pointer;
}

.total{
font-size:28px;
font-weight:bold;
color:#2563eb;
margin-bottom:20px;
}
.success{
background:#dcfce7;
color:#166534;
padding:15px;
border-radius:12px;
margin-bottom:20px;
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

<h2>Checkout Pesanan</h2>

<?php if(isset($_GET['success'])): ?>
<div class="success">
Pesanan berhasil dibuat 🎉
</div>
<?php endif; ?>

<div class="total">
Total Bayar:
Rp <?= number_format($totalBelanja,0,',','.'); ?>
</div>

<form method="POST">

<input
type="text"
name="nama"
placeholder="Nama Lengkap"
required
>

<textarea
name="alamat"
placeholder="Alamat Lengkap"
required
></textarea>

<input
type="text"
name="nohp"
placeholder="Nomor HP / WhatsApp"
required
>

<select name="pembayaran" required>
<option value="">Pilih Pembayaran</option>
<option>Transfer Bank</option>
<option>COD</option>
<option>E-Wallet</option>
</select>

<button
type="submit"
name="checkout"
>
Buat Pesanan
</button>

</form>

</div>
</div>

</body>
</html>