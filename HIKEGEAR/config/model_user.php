<?php
/**
 * model_user.php – Query CRUD User & Auth
 */

// Cari user berdasarkan email atau HP
function find_user(PDO $pdo, string $identifier): ?array {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? OR hp=? LIMIT 1");
    $stmt->execute([$identifier, $identifier]);
    return $stmt->fetch() ?: null;
}

// Login – verifikasi password
function login_user(PDO $pdo, string $identifier, string $password): array {
    $user = find_user($pdo, $identifier);
    if (!$user) return ['ok'=>false, 'msg'=>'Akun tidak ditemukan.'];
    if ($user['status'] !== 'aktif') return ['ok'=>false, 'msg'=>'Akun dinonaktifkan.'];
    if (!password_verify($password, $user['password'])) return ['ok'=>false, 'msg'=>'Password salah.'];

    // Set session
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama']    = $user['nama'];
    $_SESSION['email']   = $user['email'];
    $_SESSION['role']    = $user['role'];
    return ['ok'=>true, 'user'=>$user];
}

// Register user baru
function register_user(PDO $pdo, array $data): array {
    // Cek duplikat email
    $cek = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $cek->execute([$data['email']]);
    if ($cek->fetch()) return ['ok'=>false, 'field'=>'email', 'msg'=>'Email sudah terdaftar.'];

    // Cek duplikat HP
    $cekHp = $pdo->prepare("SELECT id FROM users WHERE hp=? LIMIT 1");
    $cekHp->execute([$data['hp']]);
    if ($cekHp->fetch()) return ['ok'=>false, 'field'=>'hp', 'msg'=>'Nomor HP sudah terdaftar.'];

    $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (nama,email,hp,gender,password) VALUES (?,?,?,?,?)";
    $pdo->prepare($sql)->execute([
        $data['nama'], $data['email'], $data['hp'],
        $data['gender'] ?? '', $hashed,
    ]);
    return ['ok'=>true, 'id'=>(int)$pdo->lastInsertId()];
}

// Update profil
function update_user(PDO $pdo, int $id, array $data): bool {
    $sql = "UPDATE users SET nama=?, hp=?, gender=? WHERE id=?";
    return $pdo->prepare($sql)->execute([$data['nama'],$data['hp'],$data['gender']??'',$id]);
}

// Ganti password
function ganti_password(PDO $pdo, int $id, string $pass_lama, string $pass_baru): array {
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row || !password_verify($pass_lama, $row['password']))
        return ['ok'=>false,'msg'=>'Password lama salah.'];
    $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($pass_baru, PASSWORD_DEFAULT), $id]);
    return ['ok'=>true];
}