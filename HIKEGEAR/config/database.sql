-- ============================================================
--  HikeGear Database Schema
--  Jalankan file ini di phpMyAdmin atau MySQL CLI:
--  mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS hikegear CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hikegear;

-- ============================================================
-- 1. USERS (Pelanggan)
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL UNIQUE,
  hp          VARCHAR(20)  NOT NULL,
  gender      ENUM('pria','wanita','') DEFAULT '',
  password    VARCHAR(255) NOT NULL,
  foto        VARCHAR(255) DEFAULT NULL,
  role        ENUM('pelanggan','admin') DEFAULT 'pelanggan',
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 2. KATEGORI
-- ============================================================
CREATE TABLE IF NOT EXISTS kategori (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  slug        VARCHAR(120) NOT NULL UNIQUE,
  deskripsi   TEXT DEFAULT NULL,
  foto        VARCHAR(255) DEFAULT NULL,
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  urutan      INT DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 3. BRAND / MEREK
-- ============================================================
CREATE TABLE IF NOT EXISTS brand (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  slug        VARCHAR(120) NOT NULL UNIQUE,
  logo        VARCHAR(255) DEFAULT NULL,
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 4. PRODUK
-- ============================================================
CREATE TABLE IF NOT EXISTS produk (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kategori_id   INT UNSIGNED NOT NULL,
  brand_id      INT UNSIGNED NOT NULL,
  nama          VARCHAR(200) NOT NULL,
  slug          VARCHAR(220) NOT NULL UNIQUE,
  deskripsi     TEXT DEFAULT NULL,
  harga         DECIMAL(12,2) NOT NULL DEFAULT 0,
  harga_coret   DECIMAL(12,2) DEFAULT NULL,
  stok          INT DEFAULT 0,
  berat         INT DEFAULT 0 COMMENT 'gram',
  foto_utama    VARCHAR(255) DEFAULT NULL,
  badge         VARCHAR(30) DEFAULT NULL COMMENT 'BARU / -10% / dll',
  status        ENUM('aktif','nonaktif','habis') DEFAULT 'aktif',
  rating        DECIMAL(3,1) DEFAULT 0,
  total_ulasan  INT DEFAULT 0,
  total_terjual INT DEFAULT 0,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT,
  FOREIGN KEY (brand_id)    REFERENCES brand(id)    ON DELETE RESTRICT,
  INDEX idx_kategori (kategori_id),
  INDEX idx_brand    (brand_id),
  INDEX idx_status   (status)
) ENGINE=InnoDB;

-- ============================================================
-- 5. FOTO PRODUK (multiple)
-- ============================================================
CREATE TABLE IF NOT EXISTS produk_foto (
  id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  produk_id INT UNSIGNED NOT NULL,
  url       VARCHAR(255) NOT NULL,
  urutan    INT DEFAULT 0,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 6. PROMO / DISKON
-- ============================================================
CREATE TABLE IF NOT EXISTS promo (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(150) NOT NULL,
  kode        VARCHAR(30) DEFAULT NULL UNIQUE,
  tipe        ENUM('persen','nominal','bundling','flash') DEFAULT 'persen',
  nilai       DECIMAL(12,2) NOT NULL DEFAULT 0,
  min_belanja DECIMAL(12,2) DEFAULT 0,
  maks_diskon DECIMAL(12,2) DEFAULT NULL,
  mulai       DATE NOT NULL,
  selesai     DATE NOT NULL,
  kuota       INT DEFAULT NULL COMMENT 'NULL = unlimited',
  terpakai    INT DEFAULT 0,
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  banner      VARCHAR(255) DEFAULT NULL,
  deskripsi   TEXT DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 7. ALAMAT PENGIRIMAN
-- ============================================================
CREATE TABLE IF NOT EXISTS alamat (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  label       VARCHAR(50)  DEFAULT 'Rumah',
  nama_penerima VARCHAR(100) NOT NULL,
  hp          VARCHAR(20)  NOT NULL,
  provinsi    VARCHAR(100) NOT NULL,
  kota        VARCHAR(100) NOT NULL,
  kecamatan   VARCHAR(100) NOT NULL,
  kode_pos    VARCHAR(10)  DEFAULT NULL,
  alamat_lengkap TEXT NOT NULL,
  is_utama    TINYINT(1) DEFAULT 0,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 8. PESANAN (ORDER)
-- ============================================================
CREATE TABLE IF NOT EXISTS pesanan (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode            VARCHAR(20) NOT NULL UNIQUE COMMENT 'ORD-XXXXXX',
  user_id         INT UNSIGNED NOT NULL,
  alamat_id       INT UNSIGNED DEFAULT NULL,
  promo_id        INT UNSIGNED DEFAULT NULL,
  subtotal        DECIMAL(12,2) NOT NULL DEFAULT 0,
  diskon          DECIMAL(12,2) DEFAULT 0,
  ongkir          DECIMAL(12,2) DEFAULT 0,
  total           DECIMAL(12,2) NOT NULL DEFAULT 0,
  metode_bayar    ENUM('transfer','cod','ewallet','kartu') DEFAULT 'transfer',
  kurir           VARCHAR(50) DEFAULT NULL,
  resi            VARCHAR(100) DEFAULT NULL,
  status          ENUM('pending','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  catatan         TEXT DEFAULT NULL,
  bayar_at        TIMESTAMP NULL DEFAULT NULL,
  kirim_at        TIMESTAMP NULL DEFAULT NULL,
  selesai_at      TIMESTAMP NULL DEFAULT NULL,
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE RESTRICT,
  FOREIGN KEY (alamat_id) REFERENCES alamat(id) ON DELETE SET NULL,
  FOREIGN KEY (promo_id)  REFERENCES promo(id)  ON DELETE SET NULL,
  INDEX idx_user   (user_id),
  INDEX idx_status (status),
  INDEX idx_kode   (kode)
) ENGINE=InnoDB;

-- ============================================================
-- 9. DETAIL PESANAN
-- ============================================================
CREATE TABLE IF NOT EXISTS pesanan_detail (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pesanan_id  INT UNSIGNED NOT NULL,
  produk_id   INT UNSIGNED NOT NULL,
  nama_produk VARCHAR(200) NOT NULL COMMENT 'snapshot saat pesan',
  foto_produk VARCHAR(255) DEFAULT NULL,
  harga       DECIMAL(12,2) NOT NULL,
  qty         INT NOT NULL DEFAULT 1,
  subtotal    DECIMAL(12,2) NOT NULL,
  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
  FOREIGN KEY (produk_id)  REFERENCES produk(id)  ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- 10. KERANJANG (sementara, untuk user yg belum checkout)
-- ============================================================
CREATE TABLE IF NOT EXISTS keranjang (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  produk_id   INT UNSIGNED NOT NULL,
  qty         INT NOT NULL DEFAULT 1,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_produk (user_id, produk_id),
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 11. WISHLIST
-- ============================================================
CREATE TABLE IF NOT EXISTS wishlist (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  produk_id   INT UNSIGNED NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_wish (user_id, produk_id),
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 12. ULASAN / REVIEW
-- ============================================================
CREATE TABLE IF NOT EXISTS ulasan (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  produk_id   INT UNSIGNED NOT NULL,
  user_id     INT UNSIGNED NOT NULL,
  pesanan_id  INT UNSIGNED DEFAULT NULL,
  rating      TINYINT NOT NULL DEFAULT 5,
  isi         TEXT NOT NULL,
  foto        VARCHAR(255) DEFAULT NULL,
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (produk_id)  REFERENCES produk(id)  ON DELETE CASCADE,
  FOREIGN KEY (user_id)    REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE SET NULL,
  INDEX idx_produk (produk_id)
) ENGINE=InnoDB;

-- ============================================================
-- 13. ARTIKEL / BLOG
-- ============================================================
CREATE TABLE IF NOT EXISTS artikel (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul       VARCHAR(250) NOT NULL,
  slug        VARCHAR(280) NOT NULL UNIQUE,
  kategori    VARCHAR(60) DEFAULT 'Tips Pendakian',
  isi         LONGTEXT NOT NULL,
  foto        VARCHAR(255) DEFAULT NULL,
  penulis     VARCHAR(100) DEFAULT 'Admin HikeGear',
  views       INT DEFAULT 0,
  status      ENUM('publish','draft') DEFAULT 'publish',
  published_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug   (slug),
  INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================================
-- 14. PENGATURAN TOKO
-- ============================================================
CREATE TABLE IF NOT EXISTS pengaturan (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key`   VARCHAR(80) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- DATA AWAL (Seeder)
-- ============================================================

-- Admin user (password: Admin123)
INSERT INTO users (nama, email, hp, role, password) VALUES
('Rian Pratama', 'admin@hikegear.com', '081234567890', 'admin',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Demo pelanggan (password: User1234)
INSERT INTO users (nama, email, hp, password) VALUES
('Budi Santoso',  'budi@gmail.com',  '081234567891', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Siti Aminah',   'siti@gmail.com',  '081234567892', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Joko Widodo',   'joko@gmail.com',  '081234567893', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Kategori
INSERT INTO kategori (nama, slug, urutan) VALUES
('Pakaian Pria',      'pakaian-pria',      1),
('Pakaian Wanita',    'pakaian-wanita',    2),
('Jaket Outdoor',     'jaket-outdoor',     3),
('Celana Outdoor',    'celana-outdoor',    4),
('Tas Carrier',       'tas-carrier',       5),
('Sepatu Gunung',     'sepatu-gunung',     6),
('Tenda',             'tenda',             7),
('Sleeping Bag',      'sleeping-bag',      8),
('Alat Masak',        'alat-masak',        9),
('Aksesoris',         'aksesoris',         10),
('Headlamp & Senter', 'headlamp-senter',   11),
('Tongkat & Poles',   'tongkat-poles',     12);

-- Brand
INSERT INTO brand (nama, slug) VALUES
('Eiger',         'eiger'),
('Consina',       'consina'),
('Rei',           'rei'),
('The North Face','the-north-face'),
('Jack Wolfskin', 'jack-wolfskin'),
('Naturehike',    'naturehike'),
('Deuter',        'deuter'),
('Black Diamond', 'black-diamond');

-- Produk
INSERT INTO produk (kategori_id, brand_id, nama, slug, harga, harga_coret, stok, badge, rating, total_ulasan) VALUES
(3, 1, 'Eiger Mountaineer Jacket',   'eiger-mountaineer-jacket',   1250000, NULL,    34, 'BARU',  5.0, 120),
(4, 2, 'Consina Panjang Pria',       'consina-panjang-pria',       650000,  NULL,    52, 'BARU',  4.5, 86),
(1, 1, 'Eiger Quick Dry Tee',        'eiger-quick-dry-tee',        199000,  265000,  8,  '-25%',  4.5, 47),
(6, 3, 'Rei Venturer Hiking Boots',  'rei-venturer-hiking-boots',  1350000, NULL,    45, 'BARU',  4.5, 68),
(5, 2, 'Consina Carrier 60L',        'consina-carrier-60l',        1275000, 1500000, 12, '-15%',  4.5, 39),
(7, 1, 'Eiger X-Trail 4P',           'eiger-x-trail-4p',           2250000, NULL,    7,  'BARU',  4.5, 55),
(8, 3, 'Rei Sleeping Bag Polar',     'rei-sleeping-bag-polar',     650000,  NULL,    27, NULL,    4.0, 32),
(9, 6, 'Naturehike Alat Masak Set',  'naturehike-alat-masak-set',  550000,  NULL,    19, 'BARU',  4.5, 21),
(10,1, 'Eiger Headlamp 500 Lumens',  'eiger-headlamp-500-lumens',  325000,  NULL,    3,  NULL,    4.5, 19),
(10,2, 'Consina Winter Glove',       'consina-winter-glove',       275000,  350000,  41, '-21%',  4.5, 27);

-- Promo
INSERT INTO promo (nama, tipe, nilai, mulai, selesai, status, deskripsi) VALUES
('Diskon Hingga 50% Produk Pilihan', 'persen',   50, '2026-06-01', '2026-06-30', 'aktif', 'Promo spesial akhir bulan'),
('Bundling Carrier + Sleeping Bag',  'nominal', 300000, '2026-06-05', '2026-06-30', 'aktif', 'Hemat beli bundling'),
('Clearance Sale Tenda',             'persen',   70, '2026-06-10', '2026-06-20', 'aktif', 'Clearance stok tenda'),
('Flash Sale Harian',                'flash',    30, '2026-06-17', '2026-06-17', 'aktif', 'Flash sale tiap hari');

-- Pengaturan toko
INSERT INTO pengaturan (`key`, `value`) VALUES
('nama_toko',       'HikeGear'),
('tagline',         'Adventure Starts Here'),
('email_toko',      'info@hikegear.com'),
('hp_toko',         '0812-3456-7890'),
('min_gratis_ongkir','200000'),
('biaya_ongkir',    '15000'),
('instagram',       '@hikegear_id'),
('facebook',        'HikeGear Indonesia');

-- Artikel contoh
INSERT INTO artikel (judul, slug, kategori, isi, views) VALUES
('10 Tips Persiapan Sebelum Mendaki Gunung untuk Pemula',
 '10-tips-persiapan-mendaki',
 'Tips Pendakian',
 '<p>Persiapan yang matang adalah kunci pendakian yang aman dan menyenangkan. Berikut 10 tips yang wajib kamu ketahui sebelum mendaki gunung untuk pertama kalinya...</p>',
 12500),
('Cara Memilih Sepatu Gunung yang Tepat dan Nyaman',
 'cara-memilih-sepatu-gunung',
 'Gear Review',
 '<p>Sepatu adalah investasi terpenting dalam pendakian. Pilihan yang salah bisa berujung pada lecet, keseleo, atau bahkan cedera serius...</p>',
 8700),
('5 Gunung Terbaik untuk Pemula di Indonesia',
 '5-gunung-terbaik-pemula',
 'Destinasi',
 '<p>Indonesia memiliki ratusan gunung indah yang menanti untuk dijelajahi. Bagi pemula, memilih gunung yang tepat sangat penting untuk pengalaman pertama yang menyenangkan...</p>',
 9100);
