<?php
header('Content-Type: application/json');
require __DIR__ . '/../includes/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$newPassword = $data['newPassword'] ?? '';

if ($username === '') {
    echo json_encode(['success' => false, 'pesan' => 'Username wajib diisi.']);
    exit;
}
if (strlen($newPassword) < 6) {
    echo json_encode(['success' => false, 'pesan' => 'Password baru minimal 6 karakter.']);
    exit;
}

$cek = $pdo->prepare("SELECT id FROM petugas WHERE username = :u");
$cek->execute(['u' => $username]);

if (!$cek->fetch()) {
    echo json_encode(['success' => false, 'pesan' => 'Username tidak ditemukan.']);
    exit;
}

$hash = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE petugas SET password = :p WHERE username = :u");
$stmt->execute(['p' => $hash, 'u' => $username]);

echo json_encode(['success' => true, 'pesan' => 'Password berhasil diubah. Silakan login dengan password baru.']);