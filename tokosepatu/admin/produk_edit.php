<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: ../index.php");
    exit;
}

$id = isset($_GET['edit']) ? (int)$_GET['edit'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
if($id <= 0){
    header("Location: produk.php");
    exit;
}

// Ambil data produk
$edit = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'" );
$e = mysqli_fetch_assoc($edit);
if(!$e){
    header("Location: produk.php");
    exit;
}

// UPDATE
if(isset($_POST['update'])){
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    if(!empty($_FILES['gambar']['name'])){
        $gambar = time().'_'.$_FILES['gambar']['name'];

        // Hapus gambar lama (opsional)
        if(!empty($e['gambar']) && file_exists("../uploads/".$e['gambar'])){
            @unlink("../uploads/".$e['gambar']);
        }

        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../uploads/".$gambar
        );

        mysqli_query($conn,"
            UPDATE produk SET
                nama='$nama',
                harga='$harga',
                stok='$stok',
                gambar='$gambar'
            WHERE id='$id'
        ");

    }else{
        mysqli_query($conn,"
            UPDATE produk SET
                nama='$nama',
                harga='$harga',
                stok='$stok'
            WHERE id='$id'
        ");
    }

    header("Location: produk.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Produk</title>

<style>
nav{
    background:#0f172a;
    padding:16px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:999;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
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
    font-size:15px;
    padding:8px 14px;
    border-radius:8px;
    transition:.2s;
}

.menu a:hover{
    background:#2563eb;
}
body{
    font-family:Segoe UI;
    background:#f1f5f9;
    margin:0;
}

.container{
    width:95%;
    max-width:850px;
    margin:auto;
    padding:30px;
}

.card{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    margin-bottom:25px;
}

input{
    width:100%;
    padding:14px;
    margin-top:10px;
    border:1px solid #d1d5db;
    border-radius:10px;
}

button{
    background:#2563eb;
    color:white;
    border:none;
    padding:12px 18px;
    border-radius:10px;
    cursor:pointer;
}

a.btn-back{
    display:inline-block;
    margin-top:15px;
    background:#334155;
    color:white;
    text-decoration:none;
    padding:10px 16px;
    border-radius:10px;
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
    <h2>Edit Produk</h2>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)$e['id']; ?>">

        <input type="text" name="nama" value="<?= htmlspecialchars($e['nama']); ?>" required>

        <input type="number" name="harga" value="<?= (int)$e['harga']; ?>" required>

        <input type="number" name="stok" value="<?= (int)$e['stok']; ?>" required>

        <label style="display:block;margin-top:15px;color:#334155;">Pilih Gambar (opsional)</label>
        <input type="file" name="gambar" accept="image/*">

        <br><br>

        <button name="update" type="submit">Update Produk</button>

        <a class="btn-back" href="produk.php">Kembali</a>
    </form>
</div>

</div>
</body>
</html>

