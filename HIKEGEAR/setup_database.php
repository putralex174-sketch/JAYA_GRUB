q   aq  <?php
// ============================================================
//  setup_database.php — HikeGear Database Setup
//  Jalankan: http://localhost/HIKEGEAR/USER/setup_database.php
//  Akan membuat database, tabel, dan data sample.
// ============================================================

// Koneksi tanpa database dulu
$conn = mysqli_connect('localhost', 'root', '');
if (!$conn) {
    die("❌ Koneksi MySQL gagal: " . mysqli_connect_error());
}

echo "✅ Terkoneksi ke MySQL.<br>";

// Buat database
$query = "CREATE DATABASE IF NOT EXISTS HIKEGEAR CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $query)) {
    echo "✅ Database HIKEGEAR siap.<br>";
} else {
    echo "❌ Gagal buat database: " . mysqli_error($conn) . "<br>";
}

// Pilih database
mysqli_select_db($conn, 'HIKEGEAR');

// ============================================================
//  TABEL USERS
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    hp VARCHAR(15) NOT NULL UNIQUE,
    gender ENUM('pria','wanita','') DEFAULT '',
    tanggal_lahir DATE DEFAULT NULL,
    kota VARCHAR(100) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    foto_profil VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_hp (hp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel users siap.<br>";
} else {
    echo "❌ Gagal buat users: " . mysqli_error($conn) . "<br>";
}

// Cek apakah kolom role sudah ada, jika belum tambahkan
$check = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'role'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user' AFTER password");
    echo "✅ Kolom 'role' ditambahkan ke users.<br>";
}

// ============================================================
//  TABEL KATEGORI
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS kategori (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT DEFAULT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel kategori siap.<br>";
} else {
    echo "❌ Gagal buat kategori: " . mysqli_error($conn) . "<br>";
}

// Cek kolom is_active di kategori (mungkin tabel lama tidak punya)
$check = mysqli_query($conn, "SHOW COLUMNS FROM kategori LIKE 'is_active'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE kategori ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1");
    echo "✅ Kolom 'is_active' ditambahkan ke kategori.<br>";
}

// ============================================================
//  TABEL PRODUK
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS produk (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT UNSIGNED NOT NULL,
    nama VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    deskripsi TEXT DEFAULT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    harga_coret DECIMAL(12,2) DEFAULT NULL,
    stok INT NOT NULL DEFAULT 0,
    berat_gram INT DEFAULT NULL,
    gambar_utama VARCHAR(255) DEFAULT NULL,
    badge VARCHAR(20) DEFAULT NULL,
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_ulasan INT DEFAULT 0,
    total_terjual INT DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_kategori (kategori_id),
    INDEX idx_harga (harga),
    INDEX idx_featured (is_featured),
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel produk siap.<br>";
} else {
    echo "❌ Gagal buat produk: " . mysqli_error($conn) . "<br>";
}

// Cek kolom yang mungkin hilang di tabel produk (tabel lama)
$produk_columns = ['is_active', 'is_featured', 'berat_gram', 'gambar_utama', 'badge', 'rating', 'total_ulasan', 'total_terjual', 'harga_coret'];
foreach ($produk_columns as $col) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM produk LIKE '$col'");
    if (mysqli_num_rows($check) == 0) {
        $type = 'TINYINT(1) NOT NULL DEFAULT 1';
        if ($col === 'is_featured') $type = 'TINYINT(1) NOT NULL DEFAULT 0';
        elseif ($col === 'berat_gram') $type = 'INT DEFAULT NULL';
        elseif ($col === 'gambar_utama') $type = 'VARCHAR(255) DEFAULT NULL';
        elseif ($col === 'badge') $type = 'VARCHAR(20) DEFAULT NULL';
        elseif ($col === 'rating') $type = 'DECIMAL(3,2) DEFAULT 0.00';
        elseif ($col === 'total_ulasan' || $col === 'total_terjual') $type = 'INT DEFAULT 0';
        elseif ($col === 'harga_coret') $type = 'DECIMAL(12,2) DEFAULT NULL';
        mysqli_query($conn, "ALTER TABLE produk ADD COLUMN $col $type");
        echo "✅ Kolom '$col' ditambahkan ke produk.<br>";
    }
}

// ============================================================
//  TABEL PRODUK_GAMBAR
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS produk_gambar (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produk_id INT UNSIGNED NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    urutan INT DEFAULT 0,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel produk_gambar siap.<br>";
} else {
    echo "❌ Gagal buat produk_gambar: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL KERANJANG
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS keranjang (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    produk_id INT UNSIGNED NOT NULL,
    variasi VARCHAR(100) DEFAULT NULL,
    qty INT NOT NULL DEFAULT 1,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_produk (user_id, produk_id, variasi),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel keranjang siap.<br>";
} else {
    echo "❌ Gagal buat keranjang: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL USER_ALAMAT
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS user_alamat (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    label VARCHAR(50) DEFAULT 'Rumah',
    nama_penerima VARCHAR(100) NOT NULL,
    hp_penerima VARCHAR(15) NOT NULL,
    alamat TEXT NOT NULL,
    kecamatan VARCHAR(100) DEFAULT NULL,
    kota VARCHAR(100) NOT NULL,
    provinsi VARCHAR(100) NOT NULL,
    kode_pos CHAR(5) NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel user_alamat siap.<br>";
} else {
    echo "❌ Gagal buat user_alamat: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL PESANAN
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS pesanan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_pesanan VARCHAR(20) NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    nama_penerima VARCHAR(100) NOT NULL,
    hp_penerima VARCHAR(15) NOT NULL,
    alamat_lengkap TEXT NOT NULL,
    kota VARCHAR(100) NOT NULL,
    provinsi VARCHAR(100) NOT NULL,
    kode_pos CHAR(5) NOT NULL,
    kecamatan VARCHAR(100) DEFAULT NULL,
    ekspedisi VARCHAR(50) NOT NULL,
    layanan VARCHAR(50) NOT NULL,
    ongkir DECIMAL(12,2) NOT NULL DEFAULT 0,
    estimasi VARCHAR(50) DEFAULT NULL,
    no_resi VARCHAR(100) DEFAULT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    diskon DECIMAL(12,2) NOT NULL DEFAULT 0,
    kode_voucher VARCHAR(30) DEFAULT NULL,
    total DECIMAL(12,2) NOT NULL,
    metode_bayar VARCHAR(50) NOT NULL,
    status_bayar ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    bukti_pembayaran VARCHAR(255) DEFAULT NULL,
    bayar_sebelum DATETIME DEFAULT NULL,
    dibayar_at DATETIME DEFAULT NULL,
    status ENUM('pending','konfirmasi','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
    catatan TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_kode (kode_pesanan),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel pesanan siap.<br>";
} else {
    echo "❌ Gagal buat pesanan: " . mysqli_error($conn) . "<br>";
}

// Cek kolom status_bayar (mungkin tabel lama tidak punya)
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'status_bayar'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN status_bayar ENUM('pending','paid','failed','refunded') DEFAULT 'pending'");
    echo "✅ Kolom 'status_bayar' ditambahkan ke pesanan.<br>";
}

// Cek kolom bukti_pembayaran
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'bukti_pembayaran'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN bukti_pembayaran VARCHAR(255) DEFAULT NULL");
    echo "✅ Kolom 'bukti_pembayaran' ditambahkan ke pesanan.<br>";
}

// Cek kolom dibayar_at
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'dibayar_at'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN dibayar_at DATETIME DEFAULT NULL");
    echo "✅ Kolom 'dibayar_at' ditambahkan ke pesanan.<br>";
}

// Cek kolom bayar_sebelum
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'bayar_sebelum'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN bayar_sebelum DATETIME DEFAULT NULL");
    echo "✅ Kolom 'bayar_sebelum' ditambahkan ke pesanan.<br>";
}

// Cek kolom kode_voucher
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'kode_voucher'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN kode_voucher VARCHAR(30) DEFAULT NULL");
    echo "✅ Kolom 'kode_voucher' ditambahkan ke pesanan.<br>";
}

// Cek kolom no_resi
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'no_resi'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN no_resi VARCHAR(100) DEFAULT NULL");
    echo "✅ Kolom 'no_resi' ditambahkan ke pesanan.<br>";
}

// Cek kolom diskon
$check = mysqli_query($conn, "SHOW COLUMNS FROM pesanan LIKE 'diskon'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN diskon DECIMAL(12,2) NOT NULL DEFAULT 0");
    echo "✅ Kolom 'diskon' ditambahkan ke pesanan.<br>";
}

// ============================================================
//  TABEL PESANAN_DETAIL
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS pesanan_detail (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT UNSIGNED NOT NULL,
    produk_id INT UNSIGNED NOT NULL,
    nama_produk VARCHAR(200) NOT NULL,
    variasi VARCHAR(100) DEFAULT NULL,
    harga DECIMAL(12,2) NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel pesanan_detail siap.<br>";
} else {
    echo "❌ Gagal buat pesanan_detail: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL ULASAN
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS ulasan (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produk_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    pesanan_id INT UNSIGNED DEFAULT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    judul VARCHAR(100) DEFAULT NULL,
    isi TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_produk (user_id, produk_id),
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel ulasan siap.<br>";
} else {
    echo "❌ Gagal buat ulasan: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL WISHLIST
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS wishlist (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    produk_id INT UNSIGNED NOT NULL,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_wish (user_id, produk_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel wishlist siap.<br>";
} else {
    echo "❌ Gagal buat wishlist: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL LOGIN_LOG
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS login_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    identifier VARCHAR(150) NOT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    status ENUM('success','failed','blocked') DEFAULT 'failed',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_identifier (identifier),
    INDEX idx_ip (ip_address),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel login_log siap.<br>";
} else {
    echo "❌ Gagal buat login_log: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  TABEL VOUCHER
// ============================================================
$query = "CREATE TABLE IF NOT EXISTS voucher (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(30) NOT NULL UNIQUE,
    tipe ENUM('persen','nominal','ongkir') NOT NULL,
    nilai DECIMAL(12,2) NOT NULL DEFAULT 0,
    min_belanja DECIMAL(12,2) DEFAULT 0,
    maks_diskon DECIMAL(12,2) DEFAULT NULL,
    kuota INT DEFAULT NULL,
    terpakai INT DEFAULT 0,
    berlaku_dari DATE DEFAULT NULL,
    berlaku_sampai DATE DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (mysqli_query($conn, $query)) {
    echo "✅ Tabel voucher siap.<br>";
} else {
    echo "❌ Gagal buat voucher: " . mysqli_error($conn) . "<br>";
}

// ============================================================
//  DATA SAMPLE
// ============================================================

// --- Users ---
$result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
$row = mysqli_fetch_assoc($result);
if ($row['cnt'] == 0) {
    $admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
    $user_pass = password_hash('user123', PASSWORD_BCRYPT);
    
    mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, hp, gender, password, role, is_active, email_verified) VALUES
        ('Admin HikeGear', 'admin@hikegear.com', '081200000000', 'pria', '$admin_pass', 'admin', 1, 1),
        ('Budi Santoso', 'budi@email.com', '081234567890', 'pria', '$user_pass', 'user', 1, 1),
        ('Siti Nurhaliza', 'siti@email.com', '081234567891', 'wanita', '$user_pass', 'user', 1, 1)");
    echo "✅ Sample users inserted.<br>";
}

// --- Kategori ---
$result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM kategori");
$row = mysqli_fetch_assoc($result);
if ($row['cnt'] == 0) {
    mysqli_query($conn, "INSERT INTO kategori (nama, slug, deskripsi, urutan) VALUES
        ('Pakaian Pria', 'pakaian-pria', 'T-shirt, kemeja, celana outdoor pria', 1),
        ('Pakaian Wanita', 'pakaian-wanita', 'Pakaian outdoor khusus wanita', 2),
        ('Jaket Outdoor', 'jaket-outdoor', 'Jaket waterproof, windbreaker, dan lainnya', 3),
        ('Celana Outdoor', 'celana-outdoor', 'Celana hiking, trekking, dan lainnya', 4),
        ('Tas Carrier', 'tas-carrier', 'Carrier 20L hingga 90L', 5),
        ('Sepatu Gunung', 'sepatu-gunung', 'Hiking, trekking, boots', 6),
        ('Tenda', 'tenda', 'Dome, tunnel, dan berbagai tipe tenda', 7),
        ('Sleeping Bag', 'sleeping-bag', 'Sleeping bag untuk berbagai suhu', 8),
        ('Alat Masak', 'alat-masak', 'Kompor, nesting, cookware outdoor', 9),
        ('Aksesoris', 'aksesoris', 'Carabiner, tali, matras, dll', 10)");
    echo "✅ Sample kategori inserted.<br>";
}

// --- Produk ---
$result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM produk");
$row = mysqli_fetch_assoc($result);
if ($row['cnt'] == 0) {
    mysqli_query($conn, "INSERT INTO produk (kategori_id, nama, slug, deskripsi, harga, stok, badge, rating, total_ulasan, total_terjual, is_active, is_featured) VALUES
        (3, 'Eiger Mountaineer Jacket', 'eiger-mountaineer-jacket', 'Jaket gunung berkualitas tinggi, tahan angin dan air.', 1250000, 34, 'BARU', 4.5, 120, 85, 1, 1),
        (4, 'Consina Celana Panjang Pria', 'consina-celana-panjang-pria', 'Celana panjang outdoor yang nyaman dan cepat kering.', 650000, 50, 'BARU', 4.5, 86, 62, 1, 1),
        (1, 'Eiger Quick Dry Tee', 'eiger-quick-dry-tee', 'Kaos cepat kering untuk aktivitas outdoor.', 199000, 100, '-10%', 4.5, 47, 120, 1, 1),
        (6, 'Rei Venturer Hiking Boots', 'rei-venturer-hiking-boots', 'Sepatu hiking profesional dengan grip kuat.', 1350000, 22, 'BARU', 4.5, 68, 45, 1, 1),
        (5, 'Consina Carrier 60L', 'consina-carrier-60l', 'Tas carrier 60L untuk pendakian multi-hari.', 1275000, 12, '-15%', 4.5, 39, 28, 1, 1),
        (7, 'Eiger X-Trail 4P', 'eiger-x-trail-4p', 'Tenda dome untuk 4 orang', 2250000, 8, 'BARU', 4.5, 55, 33, 1, 1),
        (8, 'Rei Sleeping Bag Polar', 'rei-sleeping-bag-polar', 'Sleeping bag polar untuk cuaca dingin', 650000, 15, NULL, 4.0, 32, 52, 1, 0),
        (9, 'Naturehike Alat Masak Set', 'naturehike-alat-masak-set', 'Set alat masak portable untuk camping', 550000, 25, NULL, 4.5, 21, 38, 1, 0),
        (10, 'Eiger Headlamp 500 Lumens', 'eiger-headlamp-500l', 'Headlamp terang 500 lumen', 325000, 40, NULL, 4.5, 19, 67, 1, 0),
        (10, 'Consina Winter Glove', 'consina-winter-glove', 'Sarung tangan winter', 275000, 30, NULL, 4.5, 27, 44, 1, 0)");
    echo "✅ Sample produk inserted.<br>";
}

// --- Voucher ---
$result = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM voucher");
$row = mysqli_fetch_assoc($result);
if ($row['cnt'] == 0) {
    mysqli_query($conn, "INSERT INTO voucher (kode, tipe, nilai, min_belanja, maks_diskon, kuota, berlaku_sampai) VALUES
        ('HIKE10', 'persen', 10, 0, 50000, NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR)),
        ('NEWMEMBER', 'nominal', 50000, 100000, NULL, 1000, DATE_ADD(NOW(), INTERVAL 1 YEAR)),
        ('ONGKIR0', 'ongkir', 0, 200000, NULL, NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR))");
    echo "✅ Sample voucher inserted.<br>";
}

echo "<hr>";
echo "<h3>✅ Setup Database Selesai!</h3>";
echo "<p>Akun login:</p>";
echo "<ul>";
echo "<li><b>Admin:</b> admin@hikegear.com / admin123</li>";
echo "<li><b>User:</b> budi@email.com / user123</li>";
echo "<li><b>User:</b> siti@email.com / user123</li>";
echo "</ul>";
echo "<p><a href='LOGIN.PHP' style='padding:10px 20px;background:#1e6b3c;color:white;text-decoration:none;border-radius:8px;'>Login Sekarang</a></p>";
echo "<p><a href='BRANDA.PHP' style='padding:10px 20px;background:#27ae60;color:white;text-decoration:none;border-radius:8px;'>Lihat Website</a></p>";
