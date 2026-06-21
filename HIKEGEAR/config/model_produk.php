<?php
/**
 * model_produk.php – Query CRUD Produk
 * require_once __DIR__.'/../config/model_produk.php';
 */

// GET ALL produk dengan filter & pagination
function get_produk(PDO $pdo, array $opt = []): array {
    $where  = ['p.status != "habis"'];
    $params = [];

    if (!empty($opt['kategori'])) {
        $where[] = 'k.slug = ?';
        $params[] = $opt['kategori'];
    }
    if (!empty($opt['brand'])) {
        $where[] = 'b.slug = ?';
        $params[] = $opt['brand'];
    }
    if (!empty($opt['q'])) {
        $where[] = '(p.nama LIKE ? OR b.nama LIKE ?)';
        $params[] = '%'.$opt['q'].'%';
        $params[] = '%'.$opt['q'].'%';
    }
    if (isset($opt['min'])) { $where[] = 'p.harga >= ?'; $params[] = $opt['min']; }
    if (isset($opt['max'])) { $where[] = 'p.harga <= ?'; $params[] = $opt['max']; }

    $order = match($opt['sort'] ?? 'terbaru') {
        'termurah'  => 'p.harga ASC',
        'termahal'  => 'p.harga DESC',
        'rating'    => 'p.rating DESC',
        'terlaris'  => 'p.total_terjual DESC',
        default     => 'p.id DESC',
    };

    $limit  = (int)($opt['per_page'] ?? 10);
    $offset = ((int)($opt['page'] ?? 1) - 1) * $limit;

    $sql = "SELECT p.*, k.nama AS kategori_nama, b.nama AS brand_nama
            FROM produk p
            JOIN kategori k ON p.kategori_id = k.id
            JOIN brand    b ON p.brand_id    = b.id
            WHERE " . implode(' AND ', $where) . "
            ORDER BY $order
            LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// GET total rows (untuk pagination)
function count_produk(PDO $pdo, array $opt = []): int {
    $where  = ['p.status != "habis"'];
    $params = [];
    if (!empty($opt['kategori'])) { $where[] = 'k.slug = ?'; $params[] = $opt['kategori']; }
    if (!empty($opt['brand']))    { $where[] = 'b.slug = ?'; $params[] = $opt['brand']; }
    if (!empty($opt['q']))        { $where[]='(p.nama LIKE ? OR b.nama LIKE ?)'; $params[]='%'.$opt['q'].'%'; $params[]='%'.$opt['q'].'%'; }
    if (isset($opt['min']))       { $where[] = 'p.harga >= ?'; $params[] = $opt['min']; }
    if (isset($opt['max']))       { $where[] = 'p.harga <= ?'; $params[] = $opt['max']; }

    $sql = "SELECT COUNT(*) FROM produk p
            JOIN kategori k ON p.kategori_id=k.id
            JOIN brand b    ON p.brand_id=b.id
            WHERE " . implode(' AND ', $where);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

// GET satu produk by ID
function get_produk_by_id(PDO $pdo, int $id): ?array {
    $stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama, b.nama AS brand_nama
                            FROM produk p
                            JOIN kategori k ON p.kategori_id=k.id
                            JOIN brand    b ON p.brand_id=b.id
                            WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

// INSERT produk baru
function insert_produk(PDO $pdo, array $data): int {
    $sql = "INSERT INTO produk
            (kategori_id,brand_id,nama,slug,deskripsi,harga,harga_coret,stok,badge,status,foto_utama)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $pdo->prepare($sql)->execute([
        $data['kategori_id'], $data['brand_id'],
        $data['nama'], slug($data['nama']),
        $data['deskripsi'] ?? null,
        $data['harga'], $data['harga_coret'] ?? null,
        $data['stok'] ?? 0,
        $data['badge'] ?? null,
        $data['status'] ?? 'aktif',
        $data['foto_utama'] ?? null,
    ]);
    return (int)$pdo->lastInsertId();
}

// UPDATE produk
function update_produk(PDO $pdo, int $id, array $data): bool {
    $sql = "UPDATE produk SET
            kategori_id=?, brand_id=?, nama=?, deskripsi=?,
            harga=?, harga_coret=?, stok=?, badge=?, status=?, foto_utama=?
            WHERE id=?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['kategori_id'], $data['brand_id'],
        $data['nama'],
        $data['deskripsi'] ?? null,
        $data['harga'], $data['harga_coret'] ?? null,
        $data['stok'] ?? 0,
        $data['badge'] ?? null,
        $data['status'] ?? 'aktif',
        $data['foto_utama'] ?? null,
        $id,
    ]);
}

// DELETE produk
function delete_produk(PDO $pdo, int $id): bool {
    return $pdo->prepare("DELETE FROM produk WHERE id=?")->execute([$id]);
}
