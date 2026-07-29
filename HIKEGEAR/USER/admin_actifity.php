<?php
/**
 * admin_activity.php – Fungsi bantu untuk mencatat aktivitas admin.
 */
function catat_log(PDO $pdo, int $admin_id, string $aksi, string $keterangan = ''): void {
    $pdo->prepare("INSERT INTO log_aktivitas (admin_id, aksi, keterangan) VALUES (?,?,?)")
        ->execute([$admin_id, $aksi, $keterangan]);
}