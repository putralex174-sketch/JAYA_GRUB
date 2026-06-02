<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../index.php");
    exit;
}

/* HAPUS */
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];

    $cek = mysqli_query($conn,"SELECT gambar FROM produk WHERE id='$id'");
    $row = mysqli_fetch_assoc($cek);

    if(!empty($row['gambar']) && file_exists("../uploads/".$row['gambar'])){
        unlink("../uploads/".$row['gambar']);
    }

    mysqli_query($conn,"DELETE FROM produk WHERE id='$id'");
    header("Location: produk.php");
    exit;
}

/* TAMBAH */
if(isset($_POST['tambah'])){

    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    $gambar = '';

    if(!empty($_FILES['gambar']['name'])){
        $gambar = time().'_'.$_FILES['gambar']['name'];
        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            "../uploads/".$gambar
        );
    }

    mysqli_query($conn,"
    INSERT INTO produk(nama,harga,stok,gambar)
    VALUES('$nama','$harga','$stok','$gambar')
    ");

    header("Location: produk.php");
    exit;
}

/* UPDATE */
if(isset($_POST['update'])){

    $id    = $_POST['id'];
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    if(!empty($_FILES['gambar']['name'])){

        $gambar = time().'_'.$_FILES['gambar']['name'];

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

$data = mysqli_query($conn,"SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Produk</title>

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
    max-width:1250px;
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

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:16px;
    text-align:left;
    vertical-align:middle;
}

th{
    background:#f8fafc;
}

img{
    width:80px;
    height:80px;
    object-fit:cover;
    border-radius:12px;
}

.btn-edit{
    background:#2563eb;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    margin-right:8px;
}

.btn-hapus{
    background:#dc2626;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
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
<h2>Tambah Produk</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="nama" placeholder="Nama Produk" required>

<input type="number" name="harga" placeholder="Harga" required>

<input type="number" name="stok" placeholder="Stok" required>

<input type="file" name="gambar" accept="image/*">

<br><br>

<button name="tambah">Tambah Produk</button>

</form>
</div>

<?php
if(isset($_GET['edit'])):
$id = $_GET['edit'];
$edit = mysqli_query($conn,"SELECT * FROM produk WHERE id='$id'");
$e = mysqli_fetch_assoc($edit);
?>

<div class="card">
<h2>Edit Produk</h2>

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $e['id']; ?>">

<input type="text" name="nama" value="<?= $e['nama']; ?>" required>

<input type="number" name="harga" value="<?= $e['harga']; ?>" required>

<input type="number" name="stok" value="<?= $e['stok']; ?>" required>

<input type="file" name="gambar" accept="image/*">

<br><br>

<button name="update">Update Produk</button>

</form>
</div>

<?php endif; ?>

<div class="card">
<h2>Daftar Produk</h2>

<table>
<tr>
<th>Gambar</th>
<th>Nama</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>

<td>
<?php if(!empty($row['gambar'])): ?>
<img src="../uploads/<?= $row['gambar']; ?>">
<?php else: ?>
Tidak ada gambar
<?php endif; ?>
</td>

<td><?= $row['nama']; ?></td>

<td>
Rp <?= number_format($row['harga'],0,',','.'); ?>
</td>

<td><?= $row['stok']; ?></td>

<td>
<a class="btn-edit" href="produk_edit.php?edit=<?= $row['id']; ?>">
  Edit
</a>

<a class="btn-hapus"
href="produk.php?hapus=<?= $row['id']; ?>"
onclick="return confirm('Hapus produk ini?')">
  Hapus
</a>
</td>

</tr>
<?php endwhile; ?>

</table>
</div>


</div>
</body>
</html>