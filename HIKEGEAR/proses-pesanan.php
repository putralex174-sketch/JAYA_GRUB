<?php
// ============================================================
//  proses-pesanan.php — HikeGear | Proses Buat Pesanan
//  Menerima form dari checkout.php & simpan ke database
//  Actual DB schema: pesanan(nama_penerima, hp_penerima, alamat_lengkap,
//                     kota, provinsi, kode_pos, kecamatan, kode_pesanan, ...)
// ============================================================
session_start();
require_once __DIR__ . '/KONEKSI.PHP';
require_once __DIR__ . '/auth_guard.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/model_keranjang.php';

$user_id = (int)$_SESSION['user_id'];

// ── Validasi Method ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash'] = ['msg' => 'Metode request tidak valid.', 'type' => 'error'];
    header('Location: checkout.php');
    exit;
}

// ── Ambil & Validasi Input ──────────────────────────────────
$nama_penerima = trim($_POST['nama_penerima'] ?? '');
$hp            = trim($_POST['hp'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');
$provinsi      = trim($_POST['provinsi'] ?? '');
$kota          = trim($_POST['kota'] ?? '');
$kecamatan     = trim($_POST['kecamatan'] ?? '');
$kodepos       = trim($_POST['kodepos'] ?? '');
$ekspedisi_kode= trim($_POST['ekspedisi'] ?? '');
$metode_bayar  = trim($_POST['metode_bayar'] ?? '');
$catatan       = trim($_POST['catatan'] ?? '');

// Validasi wajib
$errors = [];
if (!$nama_penerima) $errors[] = 'Nama penerima wajib diisi.';
if (!$hp) $errors[] = 'Nomor HP wajib diisi.';
if (!$alamat) $errors[] = 'Alamat wajib diisi.';
if (!$provinsi) $errors[] = 'Provinsi wajib dipilih.';
if (!$kota) $errors[] = 'Kota wajib dipilih.';
if (!$kodepos) $errors[] = 'Kode pos wajib diisi.';
if (!$ekspedisi_kode) $errors[] = 'Pilih ekspedisi pengiriman.';
if (!$metode_bayar) $errors[] = 'Pilih metode pembayaran.';

if (!empty($errors)) {
    $_SESSION['flash'] = ['msg' => implode('<br>', $errors), 'type' => 'error'];
    $_SESSION['checkout_data'] = $_POST;
    header('Location: checkout.php');
    exit;
}

// ── Ambil Data Keranjang ────────────────────────────────────
$cart_items = getCartItems($user_id);
if (empty($cart_items)) {
    $_SESSION['flash'] = ['msg' => 'Keranjang belanja kosong.', 'type' => 'error'];
    header('Location: KERANJANG.PHP');
    exit;
}

// ── Hitung Subtotal ──────────────────────────────────────────
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += (int)$item['harga'] * (int)$item['qty'];
}

// ── Tentukan Ongkir berdasarkan ekspedisi ────────────────────
$ekspedisi_list = [
    'jne_reg'   => ['nama' => 'JNE', 'layanan' => 'REG', 'ongkir' => 18000, 'estimasi' => '2-3 Hari'],
    'jne_yes'   => ['nama' => 'JNE', 'layanan' => 'YES', 'ongkir' => 28000, 'estimasi' => '1 Hari'],
    'jnt_reg'   => ['nama' => 'J&T', 'layanan' => 'Express', 'ongkir' => 15000, 'estimasi' => '2-3 Hari'],
    'sicepat'   => ['nama' => 'SiCepat', 'layanan' => 'REG', 'ongkir' => 16000, 'estimasi' => '2-3 Hari'],
    'anteraja'  => ['nama' => 'AnterAja', 'layanan' => 'Reguler', 'ongkir' => 12000, 'estimasi' => '3-5 Hari'],
];

$ekspedisi_nama = $ekspedisi_kode;
$ekspedisi_layanan = '';
$estimasi = '';
$ongkir = 0;
if (isset($ekspedisi_list[$ekspedisi_kode])) {
    $ekspedisi_nama = $ekspedisi_list[$ekspedisi_kode]['nama'];
    $ekspedisi_layanan = $ekspedisi_list[$ekspedisi_kode]['layanan'];
    $estimasi = $ekspedisi_list[$ekspedisi_kode]['estimasi'];
    $ongkir = $ekspedisi_list[$ekspedisi_kode]['ongkir'];
}

// ── Hitung Diskon ────────────────────────────────────────────
$diskon = 0;
$kode_voucher = null;

// ── Hitung Total ─────────────────────────────────────────────
$total = $subtotal + $ongkir - $diskon;
if ($total < 0) $total = 0;

// ── Generate Kode Pesanan Unik ───────────────────────────────
$tgl = date('Ymd');
$acak = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
$kode_pesanan = 'HG-' . $tgl . '-' . $acak;

// Cek unik di kolom kode_pesanan
$cek = mysqli_query($conn, "SELECT id FROM pesanan WHERE kode_pesanan = '$kode_pesanan'");
while (mysqli_num_rows($cek) > 0) {
    $acak = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
    $kode_pesanan = 'HG-' . $tgl . '-' . $acak;
    $cek = mysqli_query($conn, "SELECT id FROM pesanan WHERE kode_pesanan = '$kode_pesanan'");
}

// ── Map metode_bayar ke ENUM database ────────────────────────
// DB: enum('transfer','cod','ewallet','kartu')
$metode_bayar_map = [
    'transfer_bca' => 'transfer',
    'transfer_bni' => 'transfer',
    'transfer_bri' => 'transfer',
    'gopay'        => 'ewallet',
    'ovo'          => 'ewallet',
    'dana'         => 'ewallet',
    'qris'         => 'transfer',
    'cod'          => 'cod',
];
$metode_bayar_db = $metode_bayar_map[$metode_bayar] ?? 'transfer';

// ── Simpan metode asli ke catatan untuk referensi ────────────
if (empty($catatan)) {
    $catatan = 'Metode: ' . $metode_bayar;
}

// ── Tentukan batas bayar (24 jam) ────────────────────────────
$bayar_sebelum = date('Y-m-d H:i:s', strtotime('+24 hours'));

// ── Mulai Transaction ───────────────────────────────────────
mysqli_begin_transaction($conn);
try {
    // Kolom 'kode' = UNIQUE, isi dengan nilai yg sama seperti kode_pesanan
    $sql = "INSERT INTO pesanan (kode, kode_pesanan, user_id, nama_penerima, hp_penerima, alamat_lengkap, 
                                 kota, provinsi, kode_pos, kecamatan, 
                                 ekspedisi, layanan, ongkir, estimasi, 
                                 subtotal, diskon, kode_voucher, total, 
                                 metode_bayar, status_bayar, bayar_sebelum, status, catatan)
            VALUES ('$kode_pesanan', '$kode_pesanan', $user_id, 
                    '" . mysqli_real_escape_string($conn, $nama_penerima) . "',
                    '" . mysqli_real_escape_string($conn, $hp) . "',
                    '" . mysqli_real_escape_string($conn, $alamat) . "',
                    '" . mysqli_real_escape_string($conn, $kota) . "',
                    '" . mysqli_real_escape_string($conn, $provinsi) . "',
                    '" . mysqli_real_escape_string($conn, $kodepos) . "',
                    '" . mysqli_real_escape_string($conn, $kecamatan) . "',
                    '" . mysqli_real_escape_string($conn, $ekspedisi_nama) . "',
                    '" . mysqli_real_escape_string($conn, $ekspedisi_layanan) . "',
                    $ongkir,
                    '" . mysqli_real_escape_string($conn, $estimasi) . "',
                    $subtotal, $diskon, " . ($kode_voucher ? "'$kode_voucher'" : "NULL") . ", $total,
                    '" . mysqli_real_escape_string($conn, $metode_bayar_db) . "',
                    'pending', '$bayar_sebelum', 'pending',
                    " . ($catatan ? "'" . mysqli_real_escape_string($conn, $catatan) . "'" : "NULL") . ")";

    if (!mysqli_query($conn, $sql)) {
        throw new Exception('Gagal menyimpan pesanan: ' . mysqli_error($conn));
    }

    $pesanan_id = mysqli_insert_id($conn);

    // 2. Simpan detail pesanan
    foreach ($cart_items as $item) {
        $produk_id  = (int)$item['produk_id'];
        $nama_produk= mysqli_real_escape_string($conn, $item['nama']);
        $gambar     = mysqli_real_escape_string($conn, $item['gambar_utama'] ?? '');
        $harga      = (int)$item['harga'];
        $qty        = (int)$item['qty'];
        $sub        = $harga * $qty;

        $sql_detail = "INSERT INTO pesanan_detail (pesanan_id, produk_id, nama_produk, foto_produk, harga, qty, subtotal)
                       VALUES ($pesanan_id, $produk_id, '$nama_produk', '$gambar', $harga, $qty, $sub)";
        if (!mysqli_query($conn, $sql_detail)) {
            throw new Exception('Gagal menyimpan detail pesanan: ' . mysqli_error($conn));
        }

        // 3. Kurangi stok produk
        $sql_stok = "UPDATE produk SET stok = stok - $qty WHERE id = $produk_id AND stok >= $qty";
        if (!mysqli_query($conn, $sql_stok) || mysqli_affected_rows($conn) == 0) {
            throw new Exception('Stok produk ' . $item['nama'] . ' tidak mencukupi.');
        }
    }

    // 4. Kosongkan keranjang
    clearCart($user_id);

    // ── Commit ────────────────────────────────────────────────
    mysqli_commit($conn);

    // ── Simpan ke session untuk pembayaran ────────────────────
    $_SESSION['order_success'] = [
        'kode_pesanan' => $kode_pesanan,
        'total'        => $total,
        'metode_bayar' => $metode_bayar,
    ];

    // ── Redirect ke halaman pembayaran ────────────────────────
    $redirect = 'pembayaran.php?kode=' . urlencode($kode_pesanan);
    header('Location: ' . $redirect);
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['flash'] = ['msg' => $e->getMessage(), 'type' => 'error'];
    $_SESSION['checkout_data'] = $_POST;
    header('Location: checkout.php');
    exit;
}
