<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "HIKEGEAR"
);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>