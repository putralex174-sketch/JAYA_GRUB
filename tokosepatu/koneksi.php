<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "tokobaju"
);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>