<?php
require_once __DIR__.'/KONEKSI.PHP';

echo "<h3>Update Gambar Kategori</h3>";

// 1. Tambah kolom gambar jika belum ada
$check = mysqli_query($conn, "SHOW COLUMNS FROM kategori LIKE 'gambar'");
if (mysqli_num_rows($check) == 0) {
    $alter = mysqli_query($conn, "ALTER TABLE kategori ADD COLUMN gambar VARCHAR(255) DEFAULT NULL AFTER deskripsi");
    if ($alter) {
        echo "✅ Kolom 'gambar' berhasil ditambahkan ke tabel kategori<br>";
    } else {
        echo "❌ Gagal tambah kolom: ".mysqli_error($conn)."<br>";
    }
} else {
    echo "✅ Kolom 'gambar' sudah ada<br>";
}

// 2. Download gambar headlamp jika belum ada
$headlamp_path = __DIR__.'/uploads/products/headlamp.jpg';
if (!file_exists($headlamp_path)) {
    $img_data = file_get_contents('https://images.unsplash.com/photo-1511497584788-876760111969?w=600&q=80');
    if ($img_data) {
        file_put_contents($headlamp_path, $img_data);
        echo "✅ Gambar headlamp.jpg berhasil di-download<br>";
    } else {
        echo "⚠️ Gagal download headlamp.jpg<br>";
    }
} else {
    echo "✅ Gambar headlamp.jpg sudah ada<br>";
}

// 3. Mapping kategori_id => gambar
$kategori_gambar = [
    1 => 'kaos-quick-dry.jpg',     // Pakaian Pria
    2 => 'kaos-quick-dry.jpg',     // Pakaian Wanita (fallback)
    3 => 'jaket-mountaineer.jpg',  // Jaket Outdoor
    4 => 'celana-outdoor.jpg',     // Celana Outdoor
    5 => 'carrier-60l.jpg',        // Tas Carrier
    6 => 'sepatu-hiking.jpg',      // Sepatu Gunung
    7 => 'tenda-dome.jpg',         // Tenda
    8 => 'sleeping-bag.jpg',       // Sleeping Bag
    9 => 'alat-masak.jpg',         // Alat Masak
    10 => 'headlamp.jpg',          // Aksesoris
    11 => 'headlamp.jpg',          // Headlamp 
    12 => 'sarung-tangan.jpg',     // Sarung Tangan
];

$success = 0;
foreach ($kategori_gambar as $id => $gambar) {
    $file_path = __DIR__.'/uploads/products/'.$gambar;
    if (!file_exists($file_path)) {
        echo "⚠️ Kategori ID $id: File '$gambar' tidak ditemukan<br>";
        continue;
    }
    
    // Simpan path relatif dari folder USER/
    $path_db = 'uploads/products/'.$gambar;
    $gambar_esc = mysqli_real_escape_string($conn, $path_db);
    $query = "UPDATE kategori SET gambar = '$gambar_esc' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        echo "✅ Kategori ID $id => $gambar berhasil diupdate<br>";
        $success++;
    } else {
        echo "❌ Gagal update kategori ID $id: ".mysqli_error($conn)."<br>";
    }
}

echo "<hr>";
echo "<h3>✅ $success kategori berhasil diupdate gambarnya!</h3>";
echo "<p><a href='KATEGORI.PHP' style='padding:10px 20px;background:#27ae60;color:white;text-decoration:none;border-radius:8px;'>Lihat Halaman Kategori</a></p>";
?>

