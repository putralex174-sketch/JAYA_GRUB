<?php
/**
 * model_keranjang.php – Query keranjang belanja
 */

// Ambil isi keranjang user
function get_keranjang(PDO $pdo, int $user_id): array {
    $stmt = $pdo->prepare("SELECT k.id, k.qty, k.produk_id,
                                   p.nama, p.harga, p.stok, p.foto_utama,
                                   b.nama AS brand_nama
                            FROM keranjang k
                            JOIN produk p ON k.produk_id=p.id
                            JOIN brand  b ON p.brand_id=b.id
                            WHERE k.user_id=?
                            ORDER BY k.created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Tambah / update qty ke keranjang
function tambah_keranjang(PDO $pdo, int $user_id, int $produk_id, int $qty=1): bool {
    $sql = "INSERT INTO keranjang (user_id,produk_id,qty)
            VALUES (?,?,?)
            ON DUPLICATE KEY UPDATE qty=qty+?";
    return $pdo->prepare($sql)->execute([$user_id,$produk_id,$qty,$qty]);
}

// Update qty item
function update_keranjang(PDO $pdo, int $id, int $user_id, int $qty): bool {
    if ($qty <= 0) return hapus_keranjang($pdo,$id,$user_id);
    return $pdo->prepare("UPDATE keranjang SET qty=? WHERE id=? AND user_id=?")->execute([$qty,$id,$user_id]);
}

// Hapus item dari keranjang
function hapus_keranjang(PDO $pdo, int $id, int $user_id): bool {
    return $pdo->prepare("DELETE FROM keranjang WHERE id=? AND user_id=?")->execute([$id,$user_id]);
}

// Hitung total keranjang
function hitung_keranjang(array $items): array {
    $subtotal = 0;
    foreach ($items as $item) $subtotal += $item['harga'] * $item['qty'];
    return [
        'subtotal'  => $subtotal,
        'jumlah'    => array_sum(array_column($items,'qty')),
    ];
}