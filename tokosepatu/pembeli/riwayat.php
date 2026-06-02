<?php
session_start();
include '../koneksi.php';
?>

<h2>Riwayat Pesanan Saya</h2>

<?php
$data=mysqli_query($conn,"SELECT * FROM pesanan ORDER BY id DESC");

while($row=mysqli_fetch_assoc($data)){
?>

<p>
<?= $row['nama_pelanggan']; ?>
-
Rp <?= number_format($row['total']); ?>
-
<?= $row['status']; ?>
</p>

<?php } ?>