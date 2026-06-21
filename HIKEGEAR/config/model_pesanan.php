<?php
/**
 * model_pesanan.php – Query CRUD Pesanan
 */

// Buat pesanan baru (dari checkout)
function buat_pesanan(PDO $pdo, array $data): int {
    $kode = gen_kode();
    $sql  = "INSERT INTO pesanan
             (kode,user_id,alamat_id,promo_id,subtotal,diskon,ongkir,total,metode_bayar,catatan)
             VALUES (?,?,?,?,?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([
        $kode,
        $data['user_id'],
        $data['alamat_id']  ?? null,
        $data['promo_id']   ?? null,
        $data['subtotal'],
        $data['diskon']     ?? 0,
        $data['ongkir']     ?? 0,
        $data['total'],
        $data['metode_bayar'] ?? 'transfer',
        $data['catatan']    ?? null,
    ]);
    $pesanan_id = (int)$pdo->lastInsertId();

    // Insert detail item
    $detail_sql = "INSERT INTO pesanan_detail (pesanan_id,produk_id,nama_produk,foto_produk,harga,qty,subtotal)
                   VALUES (?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($detail_sql);
    foreach ($data['items'] as $item) {
        $stmt->execute([
            $pesanan_id,
            $item['produk_id'],
            $item['nama'],
            $item['foto']     ?? null,
            $item['harga'],
            $item['qty'],
            $item['harga'] * $item['qty'],
        ]);
        // Kurangi stok
        $pdo->prepare("UPDATE produk SET stok=stok-?, total_terjual=total_terjual+? WHERE id=?")
            ->execute([$item['qty'], $item['qty'], $item['produk_id']]);
    }
    // Kosongkan keranjang
    $pdo->prepare("DELETE FROM keranjang WHERE user_id=?")->execute([$data['user_id']]);

    return $pesanan_id;
}

// GET pesanan user
function get_pesanan_user(PDO $pdo, int $user_id, string $status=''): array {
    $where = 'p.user_id = ?';
    $params = [$user_id];
    if ($status) { $where .= ' AND p.status = ?'; $params[] = $status; }
    $stmt = $pdo->prepare("SELECT p.*, COUNT(d.id) AS jml_item
                            FROM pesanan p
                            LEFT JOIN pesanan_detail d ON d.pesanan_id=p.id
                            WHERE $where GROUP BY p.id ORDER BY p.created_at DESC");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// GET semua pesanan (admin)
function get_all_pesanan(PDO $pdo, array $opt=[]): array {
    $where = ['1=1']; $params = [];
    if (!empty($opt['status']))  { $where[]='p.status=?';     $params[]=$opt['status']; }
    if (!empty($opt['q']))       { $where[]='(p.kode LIKE ? OR u.nama LIKE ?)'; $params[]='%'.$opt['q'].'%'; $params[]='%'.$opt['q'].'%'; }
    $limit  = (int)($opt['per_page'] ?? 15);
    $offset = ((int)($opt['page'] ?? 1)-1)*$limit;
    $sql = "SELECT p.*, u.nama AS nama_user, u.email
            FROM pesanan p JOIN users u ON p.user_id=u.id
            WHERE ".implode(' AND ',$where)."
            ORDER BY p.created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Update status pesanan
function update_status_pesanan(PDO $pdo, int $id, string $status): bool {
    $col_time = match($status) {
        'dikirim' => ', kirim_at=NOW()',
        'selesai' => ', selesai_at=NOW()',
        default   => '',
    };
    return $pdo->prepare("UPDATE pesanan SET status=? $col_time WHERE id=?")->execute([$status, $id]);
}
