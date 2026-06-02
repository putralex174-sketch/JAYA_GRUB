<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit;
}

$data = mysqli_query($conn,"
SELECT * FROM pesanan
ORDER BY id DESC
");

if(!$data){
    die("Query Error: ".mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pesanan</title>

<style>
body{
    font-family:Segoe UI;
    background:#f1f5f9;
    margin:0;
}

nav{
    background:#0f172a;
    padding:16px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.logo{
    color:white;
    font-size:26px;
    font-weight:bold;
}

.menu a{
    color:white;
    text-decoration:none;
    margin-left:20px;
}

.container{
    width:95%;
    margin:auto;
    padding:30px;
}

.card{
    background:white;
    border-radius:20px;
    padding:25px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:16px;
    text-align:left;
    border-bottom:1px solid #e5e7eb;
}

th{
    background:#f8fafc;
}
</style>
</head>
<body>

<nav>
<div class="logo">👕 Admin Toko Baju</div>

<div class="menu">
<a href="dashboard.php">Dashboard</a>
<a href="produk.php">Produk</a>
<a href="pesanan.php">Pesanan</a>
<a href="../logout.php">Logout</a>
</div>
</nav>

<div class="container">

<div class="card">
<h2>Data Pesanan Masuk</h2>

<table>
<tr>
<th>Nama Pelanggan</th>
<th>Pembayaran</th>
<th>Total</th>
<th>Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)): ?>

<tr>
<td><?= $row['nama_pelanggan']; ?></td>
<td><?= $row['pembayaran']; ?></td>
<td>Rp <?= number_format($row['total'],0,',','.'); ?></td>
<td><?= $row['status']; ?></td>
</tr>

<?php endwhile; ?>

</table>
</div>

</div>
</body>
</html>