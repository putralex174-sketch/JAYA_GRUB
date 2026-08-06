-- ============================================================
--  HIKEGEAR — Patch Lengkapi Database
--  Menambahkan tabel & kolom yang dipakai aplikasi tapi belum
--  ada di database.sql, lalu mengisi data contoh.
--  Jalankan: mysql -u root < config/patch_lengkap.sql
-- ============================================================

USE HIKEGEAR;

-- ── Tambah kolom role di users ──────────────────────────────
SET @col_role = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='users' AND COLUMN_NAME='role');
SET @sql_role = IF(@col_role=0,
    'ALTER TABLE users ADD COLUMN role ENUM(''admin'',''user'') NOT NULL DEFAULT ''user'' AFTER password',
    'SELECT 1');
PREPARE stmt FROM @sql_role; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom kode di pesanan ────────────────────────────
SET @col_kode = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='pesanan' AND COLUMN_NAME='kode');
SET @sql_kode = IF(@col_kode=0,
    'ALTER TABLE pesanan ADD COLUMN kode VARCHAR(20) DEFAULT NULL AFTER id',
    'SELECT 1');
PREPARE stmt FROM @sql_kode; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom kecamatan di pesanan ───────────────────────
SET @col_kec = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='pesanan' AND COLUMN_NAME='kecamatan');
SET @sql_kec = IF(@col_kec=0,
    'ALTER TABLE pesanan ADD COLUMN kecamatan VARCHAR(100) DEFAULT NULL AFTER kode_pos',
    'SELECT 1');
PREPARE stmt FROM @sql_kec; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom foto_produk di pesanan_detail ──────────────
SET @col_foto = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='pesanan_detail' AND COLUMN_NAME='foto_produk');
SET @sql_foto = IF(@col_foto=0,
    'ALTER TABLE pesanan_detail ADD COLUMN foto_produk VARCHAR(255) DEFAULT NULL AFTER nama_produk',
    'SELECT 1');
PREPARE stmt FROM @sql_foto; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom kecamatan di user_alamat (jika belum ada) ──
SET @col_ua = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='user_alamat' AND COLUMN_NAME='kecamatan');
SET @sql_ua = IF(@col_ua=0,
    'ALTER TABLE user_alamat ADD COLUMN kecamatan VARCHAR(100) DEFAULT NULL AFTER alamat',
    'SELECT 1');
PREPARE stmt FROM @sql_ua; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom bukti_pembayaran di pesanan ────────────────
SET @col_bukti = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='pesanan' AND COLUMN_NAME='bukti_pembayaran');
SET @sql_bukti = IF(@col_bukti=0,
    'ALTER TABLE pesanan ADD COLUMN bukti_pembayaran VARCHAR(255) DEFAULT NULL AFTER status_bayar',
    'SELECT 1');
PREPARE stmt FROM @sql_bukti; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom dibayar_at di pesanan ──────────────────────
SET @col_dibayar = (SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA='HIKEGEAR' AND TABLE_NAME='pesanan' AND COLUMN_NAME='dibayar_at');
SET @sql_dibayar = IF(@col_dibayar=0,
    'ALTER TABLE pesanan ADD COLUMN dibayar_at DATETIME DEFAULT NULL AFTER bayar_sebelum',
    'SELECT 1');
PREPARE stmt FROM @sql_dibayar; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── Tambah kolom log_aktivitas ──────────────────────────────
CREATE TABLE IF NOT EXISTS log_aktivitas (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id    INT UNSIGNED DEFAULT NULL,
  aksi        VARCHAR(150) NOT NULL,
  keterangan  VARCHAR(255) DEFAULT NULL,
  created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── Tambah tabel pengaturan ─────────────────────────────────
CREATE TABLE IF NOT EXISTS pengaturan (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_toko         VARCHAR(150) DEFAULT 'HikeGear',
  email_toko        VARCHAR(150) DEFAULT NULL,
  telepon_toko      VARCHAR(30)  DEFAULT NULL,
  alamat_toko       TEXT         DEFAULT NULL,
  ongkir_default    INT          DEFAULT 15000,
  min_gratis_ongkir INT          DEFAULT 200000,
  created_at        DATETIME     DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO pengaturan (id, nama_toko, email_toko, telepon_toko, alamat_toko, ongkir_default, min_gratis_ongkir)
VALUES (1, 'HikeGear', 'cs@hikegear.com', '081234567890', 'Jl. Pendaki No. 88, Bandung, Jawa Barat', 15000, 200000)
ON DUPLICATE KEY UPDATE nama_toko=VALUES(nama_toko);

-- ── Tambah tabel banner ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS banner (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  judul         VARCHAR(150) NOT NULL,
  gambar        VARCHAR(255) NOT NULL,
  link_tujuan   VARCHAR(255) DEFAULT NULL,
  urutan        INT          DEFAULT 0,
  is_active     TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO banner (judul, gambar, link_tujuan, urutan, is_active) VALUES
('Petualangan Dimulai di Sini', 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=1400&q=80', 'PRODUK.PHP', 1, 1),
('Gear Outdoor Original', 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=1400&q=80', 'BRANDA.PHP', 2, 1)
ON DUPLICATE KEY UPDATE judul=VALUES(judul);

-- ── Pastikan admin & user contoh ada ────────────────────────
-- Password diisi terpisah via PHP (password_hash) karena hash
-- bcrypt harus valid. Lihat create_demo_users.php.

-- ── Seed produk contoh (jika produk kosong) ─────────────────
INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 3, 1, 'Eiger Mountaineer Jacket', 'eiger-mountaineer-jacket', 'Jaket gunung berkualitas tinggi, tahan angin dan air.', 1250000, 1400000, 34, 850, 'BARU', 4.5, 120, 85, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='eiger-mountaineer-jacket');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 4, 2, 'Consina Celana Panjang Pria', 'consina-celana-panjang-pria', 'Celana panjang outdoor yang nyaman dan cepat kering.', 650000, 720000, 50, 320, 'BARU', 4.5, 86, 62, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='consina-celana-panjang-pria');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 1, 1, 'Eiger Quick Dry Tee', 'eiger-quick-dry-tee', 'Kaos cepat kering untuk aktivitas outdoor.', 199000, 265000, 100, 150, '-10%', 4.5, 47, 120, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='eiger-quick-dry-tee');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 6, 3, 'Rei Venturer Hiking Boots', 'rei-venturer-hiking-boots', 'Sepatu hiking profesional dengan grip kuat.', 1350000, 1500000, 22, 960, 'BARU', 4.5, 68, 45, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='rei-venturer-hiking-boots');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 5, 2, 'Consina Carrier 60L', 'consina-carrier-60l', 'Tas carrier 60L untuk pendakian multi-hari.', 1275000, 1500000, 12, 1800, '-15%', 4.5, 39, 28, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='consina-carrier-60l');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 7, 1, 'Eiger X-Trail 4P', 'eiger-x-trail-4p', 'Tenda dome untuk 4 orang', 2250000, NULL, 8, 3200, 'BARU', 4.5, 55, 33, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='eiger-x-trail-4p');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 8, 3, 'Rei Sleeping Bag Polar', 'rei-sleeping-bag-polar', 'Sleeping bag polar untuk cuaca dingin', 650000, NULL, 15, 1200, NULL, 4.0, 32, 52, 1, 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='rei-sleeping-bag-polar');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 9, 6, 'Naturehike Alat Masak Set', 'naturehike-alat-masak-set', 'Set alat masak portable untuk camping', 550000, NULL, 25, 680, NULL, 4.5, 21, 38, 1, 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='naturehike-alat-masak-set');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 10, 1, 'Eiger Headlamp 500 Lumens', 'eiger-headlamp-500l', 'Headlamp terang 500 lumen', 325000, NULL, 40, 95, NULL, 4.5, 19, 67, 1, 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='eiger-headlamp-500l');

INSERT INTO produk (kategori_id, brand_id, nama, slug, deskripsi, harga, harga_coret, stok, berat_gram, badge, rating, total_ulasan, total_terjual, is_active, is_featured)
SELECT 12, 2, 'Consina Winter Glove', 'consina-winter-glove', 'Sarung tangan winter', 275000, NULL, 30, 80, NULL, 4.5, 27, 44, 1, 0
WHERE NOT EXISTS (SELECT 1 FROM produk WHERE slug='consina-winter-glove');

-- ── Set gambar produk dari file yang tersedia ───────────────
UPDATE produk SET gambar_utama = '1785581888_Eiger-Mountaineer-Jacket.jpg' WHERE slug='eiger-mountaineer-jacket' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = '1785581848_Consina-Panjang-Pria.jpg' WHERE slug='consina-celana-panjang-pria' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = '1785581792_Eiger-Quick-Dry-Tee.jpg' WHERE slug='eiger-quick-dry-tee' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = '1785581740_Rei-Venturer-Hiking-Boots.jpg' WHERE slug='rei-venturer-hiking-boots' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'carrier-60l.jpg' WHERE slug='consina-carrier-60l' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'tenda-dome.jpg' WHERE slug='eiger-x-trail-4p' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'sleeping-bag.jpg' WHERE slug='rei-sleeping-bag-polar' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'alat-masak.jpg' WHERE slug='naturehike-alat-masak-set' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'headlamp.jpg' WHERE slug='eiger-headlamp-500l' AND (gambar_utama IS NULL OR gambar_utama='');
UPDATE produk SET gambar_utama = 'sarung-tangan.jpg' WHERE slug='consina-winter-glove' AND (gambar_utama IS NULL OR gambar_utama='');

-- ── Seed voucher (jika kosong) ──────────────────────────────
INSERT INTO voucher (kode, tipe, nilai, min_belanja, maks_diskon, kuota, berlaku_sampai)
SELECT 'HIKE10', 'persen', 10, 0, 50000, NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR)
WHERE NOT EXISTS (SELECT 1 FROM voucher WHERE kode='HIKE10');

INSERT INTO voucher (kode, tipe, nilai, min_belanja, maks_diskon, kuota, berlaku_sampai)
SELECT 'NEWMEMBER', 'nominal', 50000, 100000, NULL, 1000, DATE_ADD(NOW(), INTERVAL 1 YEAR)
WHERE NOT EXISTS (SELECT 1 FROM voucher WHERE kode='NEWMEMBER');

INSERT INTO voucher (kode, tipe, nilai, min_belanja, maks_diskon, kuota, berlaku_sampai)
SELECT 'ONGKIR0', 'ongkir', 0, 200000, NULL, NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR)
WHERE NOT EXISTS (SELECT 1 FROM voucher WHERE kode='ONGKIR0');
