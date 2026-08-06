-- ============================================================
--  HIKEGEAR — Database Schema Lengkap
--  Jalankan di phpMyAdmin atau: mysql -u root -p < HIKEGEAR_database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS HIKEGEAR
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE HIKEGEAR;

-- ── Tabel Users ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id             INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  nama_lengkap   VARCHAR(100)    NOT NULL,
  email          VARCHAR(150)    NOT NULL UNIQUE,
  hp             VARCHAR(15)     NOT NULL UNIQUE,
  gender         ENUM('pria','wanita','') DEFAULT '',
  tanggal_lahir  DATE            DEFAULT NULL,
  kota           VARCHAR(100)    DEFAULT NULL,
  password       VARCHAR(255)    NOT NULL,
  foto_profil    VARCHAR(255)    DEFAULT NULL,
  is_active      TINYINT(1)      NOT NULL DEFAULT 1,
  email_verified TINYINT(1)      NOT NULL DEFAULT 0,
  remember_token VARCHAR(100)    DEFAULT NULL,
  created_at     DATETIME        DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_hp    (hp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tabel Kategori ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS kategori (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  slug        VARCHAR(100) NOT NULL UNIQUE,
  deskripsi   TEXT         DEFAULT NULL,
  gambar      VARCHAR(255) DEFAULT NULL,
  urutan      INT          DEFAULT 0,
  is_active   TINYINT(1)   NOT NULL DEFAULT 1,
  created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO kategori (nama, slug, deskripsi, urutan) VALUES
('Pakaian Pria',   'pakaian-pria',   'T-shirt, kemeja, celana outdoor pria', 1),
('Pakaian Wanita', 'pakaian-wanita', 'Pakaian outdoor khusus wanita', 2),
('Jaket Outdoor',  'jaket-outdoor',  'Jaket waterproof, windbreaker, dan lainnya', 3),
('Celana Outdoor', 'celana-outdoor', 'Celana hiking, trekking, dan lainnya', 4),
('Tas Carrier',    'tas-carrier',    'Carrier 20L hingga 90L', 5),
('Sepatu Gunung',  'sepatu-gunung',  'Hiking, trekking, boots', 6),
('Tenda',          'tenda',          'Dome, tunnel, dan berbagai tipe tenda', 7),
('Sleeping Bag',   'sleeping-bag',   'Sleeping bag untuk berbagai suhu', 8),
('Alat Masak',     'alat-masak',     'Kompor, nesting, cookware outdoor', 9),
('Aksesoris',      'aksesoris',      'Carabiner, tali, matras, dll', 10),
('Headlamp & Senter','headlamp',     'Lampu kepala dan senter outdoor', 11),
('Sarung Tangan',  'sarung-tangan',  'Gloves hiking dan windproof', 12);

-- ── Tabel Brand ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS brand (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama        VARCHAR(100) NOT NULL,
  slug        VARCHAR(120) NOT NULL UNIQUE,
  logo        VARCHAR(255) DEFAULT NULL,
  status      ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO brand (nama, slug) VALUES
('Eiger',          'eiger'),
('Consina',        'consina'),
('Rei',            'rei'),
('The North Face', 'the-north-face'),
('Jack Wolfskin',  'jack-wolfskin'),
('Naturehike',     'naturehike'),
('Deuter',         'deuter'),
('Black Diamond',  'black-diamond');

-- ── Tabel Produk ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS produk (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  kategori_id     INT UNSIGNED    NOT NULL,
  brand_id        INT UNSIGNED    NOT NULL,
  nama            VARCHAR(200)    NOT NULL,
  slug            VARCHAR(200)    NOT NULL UNIQUE,
  deskripsi       TEXT            DEFAULT NULL,
  harga           DECIMAL(12,2)   NOT NULL DEFAULT 0,
  harga_coret     DECIMAL(12,2)   DEFAULT NULL,
  stok            INT             NOT NULL DEFAULT 0,
  berat_gram      INT             DEFAULT NULL COMMENT 'berat dalam gram, untuk hitung ongkir',
  gambar_utama    VARCHAR(255)    DEFAULT NULL,
  badge           VARCHAR(20)     DEFAULT NULL COMMENT 'BARU, TERLARIS, dsb',
  rating          DECIMAL(3,2)    DEFAULT 0.00,
  total_ulasan    INT             DEFAULT 0,
  total_terjual   INT             DEFAULT 0,
  is_active       TINYINT(1)      NOT NULL DEFAULT 1,
  is_featured     TINYINT(1)      NOT NULL DEFAULT 0,
  created_at      DATETIME        DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_kategori  (kategori_id),
  INDEX idx_brand     (brand_id),
  INDEX idx_harga     (harga),
  INDEX idx_featured  (is_featured),
  FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE RESTRICT,
  FOREIGN KEY (brand_id)    REFERENCES brand(id)    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tabel Gambar Produk ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS produk_gambar (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  produk_id   INT UNSIGNED NOT NULL,
  gambar      VARCHAR(255) NOT NULL,
  urutan      INT          DEFAULT 0,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Spesifikasi Produk ────────────────────────────────
CREATE TABLE IF NOT EXISTS produk_spesifikasi (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  produk_id       INT UNSIGNED    NOT NULL,
  nama_spesifikasi VARCHAR(100)   NOT NULL,
  nilai_spesifikasi VARCHAR(255)  NOT NULL,
  urutan          INT             DEFAULT 0,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Tabel Keranjang ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS keranjang (
  id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED    NOT NULL,
  produk_id   INT UNSIGNED    NOT NULL,
  variasi     VARCHAR(100)    DEFAULT NULL,
  qty         INT             NOT NULL DEFAULT 1,
  added_at    DATETIME        DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_produk (user_id, produk_id, variasi),
  FOREIGN KEY (user_id)   REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Alamat User ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS user_alamat (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED    NOT NULL,
  label           VARCHAR(50)     DEFAULT 'Rumah' COMMENT 'Rumah, Kantor, dll',
  nama_penerima   VARCHAR(100)    NOT NULL,
  hp_penerima     VARCHAR(15)     NOT NULL,
  alamat          TEXT            NOT NULL,
  kecamatan       VARCHAR(100)    DEFAULT NULL,
  kota            VARCHAR(100)    NOT NULL,
  provinsi        VARCHAR(100)    NOT NULL,
  kode_pos        CHAR(5)         NOT NULL,
  is_default      TINYINT(1)      NOT NULL DEFAULT 0,
  created_at      DATETIME        DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Pesanan ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS pesanan (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  kode_pesanan    VARCHAR(20)     NOT NULL UNIQUE,
  user_id         INT UNSIGNED    NOT NULL,
  -- Snapshot alamat (supaya tidak berubah jika user edit)
nama_penerima   VARCHAR(100)    NOT NULL,
  hp_penerima     VARCHAR(15)     NOT NULL,
  alamat_lengkap  TEXT            NOT NULL,
  kota            VARCHAR(100)    NOT NULL,
  provinsi        VARCHAR(100)    NOT NULL,
  kode_pos        CHAR(5)         NOT NULL,
  kecamatan       VARCHAR(100)    DEFAULT NULL,
  -- Pengiriman
  ekspedisi       VARCHAR(50)     NOT NULL,
  layanan         VARCHAR(50)     NOT NULL,
  ongkir          DECIMAL(12,2)   NOT NULL DEFAULT 0,
  estimasi        VARCHAR(50)     DEFAULT NULL,
  no_resi         VARCHAR(100)    DEFAULT NULL,
  -- Keuangan
  subtotal        DECIMAL(12,2)   NOT NULL,
  diskon          DECIMAL(12,2)   NOT NULL DEFAULT 0,
  kode_voucher    VARCHAR(30)     DEFAULT NULL,
  total           DECIMAL(12,2)   NOT NULL,
-- Pembayaran
  metode_bayar    VARCHAR(50)     NOT NULL,
  status_bayar    ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
  bukti_pembayaran VARCHAR(255)   DEFAULT NULL,
  bayar_sebelum   DATETIME        DEFAULT NULL,
  dibayar_at      DATETIME        DEFAULT NULL,
  -- Status
  status          ENUM('pending','konfirmasi','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  catatan         TEXT            DEFAULT NULL,
  created_at      DATETIME        DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_user      (user_id),
  INDEX idx_status    (status),
  INDEX idx_kode      (kode_pesanan),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ── Tabel Detail Pesanan ────────────────────────────────────
CREATE TABLE IF NOT EXISTS pesanan_detail (
  id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  pesanan_id  INT UNSIGNED    NOT NULL,
  produk_id   INT UNSIGNED    NOT NULL,
  nama_produk VARCHAR(200)    NOT NULL COMMENT 'snapshot nama saat beli',
  variasi     VARCHAR(100)    DEFAULT NULL,
  harga       DECIMAL(12,2)   NOT NULL,
  qty         INT             NOT NULL DEFAULT 1,
  subtotal    DECIMAL(12,2)   NOT NULL,
  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
  FOREIGN KEY (produk_id)  REFERENCES produk(id)  ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ── Tabel Ulasan ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS ulasan (
  id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  produk_id   INT UNSIGNED    NOT NULL,
  user_id     INT UNSIGNED    NOT NULL,
  pesanan_id  INT UNSIGNED    DEFAULT NULL,
  rating      TINYINT         NOT NULL CHECK (rating BETWEEN 1 AND 5),
  judul       VARCHAR(100)    DEFAULT NULL,
  isi         TEXT            DEFAULT NULL,
  is_active   TINYINT(1)      DEFAULT 1,
  created_at  DATETIME        DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_produk (user_id, produk_id),
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id)   REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Wishlist ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS wishlist (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  produk_id   INT UNSIGNED NOT NULL,
  added_at    DATETIME     DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_wish (user_id, produk_id),
  FOREIGN KEY (user_id)   REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Login Sessions (Remember Me) ──────────────────────
CREATE TABLE IF NOT EXISTS user_sessions (
  id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED    NOT NULL,
  token       VARCHAR(128)    NOT NULL UNIQUE,
  ip_address  VARCHAR(45)     DEFAULT NULL,
  user_agent  TEXT            DEFAULT NULL,
  expires_at  DATETIME        NOT NULL,
  created_at  DATETIME        DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Tabel Login Log ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS login_log (
  id          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED    DEFAULT NULL,
  identifier  VARCHAR(150)    NOT NULL,
  ip_address  VARCHAR(45)     DEFAULT NULL,
  status      ENUM('success','failed','blocked') DEFAULT 'failed',
  created_at  DATETIME        DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_identifier (identifier),
  INDEX idx_ip         (ip_address),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ── Tabel Voucher ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS voucher (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  kode            VARCHAR(30)     NOT NULL UNIQUE,
  tipe            ENUM('persen','nominal','ongkir') NOT NULL,
  nilai           DECIMAL(12,2)   NOT NULL DEFAULT 0,
  min_belanja     DECIMAL(12,2)   DEFAULT 0,
  maks_diskon     DECIMAL(12,2)   DEFAULT NULL,
  kuota           INT             DEFAULT NULL COMMENT 'NULL = unlimited',
  terpakai        INT             DEFAULT 0,
  berlaku_dari    DATE            DEFAULT NULL,
  berlaku_sampai  DATE            DEFAULT NULL,
  is_active       TINYINT(1)      DEFAULT 1,
  created_at      DATETIME        DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO voucher (kode, tipe, nilai, min_belanja, maks_diskon, kuota, berlaku_sampai) VALUES
('HIKE10',    'persen',  10,   0,       50000,  NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR)),
('NEWMEMBER', 'nominal', 50000, 100000, NULL,   1000, DATE_ADD(NOW(), INTERVAL 1 YEAR)),
('ONGKIR0',   'ongkir',  0,    200000,  NULL,   NULL, DATE_ADD(NOW(), INTERVAL 1 YEAR));

-- ── User contoh untuk testing ────────────────────────────────
-- Password: HikeGear123! (bcrypt, ganti saat production)
INSERT INTO users (nama_lengkap, email, hp, gender, password, is_active, email_verified) VALUES
('Admin HikeGear', 'admin@hikegear.com', '081200000000',
 'pria', '$2y$12$examplehashadmin000000000000000000000000000000000000',
 1, 1),
('Budi Santoso', 'budi@email.com', '081234567890',
 'pria', '$2y$12$examplehashuser0000000000000000000000000000000000000',
 1, 1);
