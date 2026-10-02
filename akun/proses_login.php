<?php
header('Content-Type: application/json');
require __DIR__ . '/../includes/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = $data['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'pesan' => 'Username dan password wajib diisi.']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM petugas WHERE username = :u");
$stmt->execute(['u' => $username]);
$petugas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$petugas || !password_verify($password, $petugas['password'])) {
    echo json_encode(['success' => false, 'pesan' => 'Username atau password salah.']);
    exit;
}

echo json_encode(['success' => true, 'nama' => $petugas['username']]);