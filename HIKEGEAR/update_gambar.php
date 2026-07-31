<?php
require_once __DIR__.'/KONEKSI.PHP';

// Mapping produk_id => nama file gambar
$gambar_map = [
    1 => 'jaket-mountaineer.jpg',   // Eiger Mountaineer Jacket
    2 => 'celana-outdoor.jpg',       // Consina Celana Panjang Pria
    3 => 'kaos-quick-dry.jpg',       // Eiger Quick Dry Tee
    4 => 'sepatu-hiking.jpg',        // Rei Venturer Hiking Boots
    5 => 'carrier-60l.jpg',          // Consina Carrier 60L
    6 => 'tenda-dome.jpg',           // Eiger X-Trail 4P
    7 => 'sleeping-bag.jpg',         // Rei Sleeping Bag Polar
    8 => 'alat-masak.jpg',           // Naturehike Alat Masak Set
    9 => 'headlamp.jpg',             // Eiger Headlamp 500 Lumens - fallback to jaket if not exist
    10 => 'sarung-tangan.jpg',       // Consina Winter Glove
];

// Cek file headlamp.jpg, jika tidak ada pakai fallback
$headlamp_path = __DIR__.'/uploads/products/headlamp.jpg';
if (!file_exists($headlamp_path)) {
    $gambar_map[9] = 'jaket-mountaineer.jpg'; // fallback
}

$success = 0;
$errors = 0;

foreach ($gambar_map as $id => $gambar) {
    // Cek apakah file gambar benar-benar ada
    $file_path = __DIR__.'/uploads/products/'.$gambar;
    if (!file_exists($file_path)) {
        echo "❌ Produk ID $id: File '$gambar' tidak ditemukan!<br>";
        $errors++;
        continue;
    }
    
    $gambar_esc = mysqli_real_escape_string($conn, $gambar);
    $query = "UPDATE produk SET gambar_utama = '$gambar_esc' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo "✅ Produk ID $id => $gambar berhasil diupdate<br>";
            $success++;
        } else {
            echo "⚠️ Produk ID $id tidak ditemukan di database<br>";
            $errors++;
        }
    } else {
        echo "❌ Gagal update produk ID $id: " . mysqli_error($conn) . "<br>";
        $errors++;
    }
}

// Update juga harga_coret untuk produk yang belum punya
mysqli_query($conn, "UPDATE produk SET harga_coret = 265000 WHERE id = 3");
mysqli_query($conn, "UPDATE produk SET harga_coret = 1500000 WHERE id = 5");

echo "<hr>";
echo "<h3>✅ Selesai! $success produk berhasil diupdate, $errors error.</h3>";
echo "<p><a href='beranda.php' style='padding:10px 20px;background:#27ae60;color:white;text-decoration:none;border-radius:8px;'>Lihat Website Sekarang</a></p>";
?>

