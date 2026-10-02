<?php
header('Content-Type: application/json');
require __DIR__ . '/../includes/koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$errors = [];
if ($username === '') $errors[] = "Username wajib diisi.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";

if (!empty($errors)) {
    echo json_encode(['success' => false, 'pesan' => implode(' ', $errors)]);
    exit;
}

try {
    $cek = $pdo->prepare("SELECT id FROM petugas WHERE username = :u OR email = :e");
    $cek->execute(['u' => $username, 'e' => $email]);
    if ($cek->fetch()) {
        echo json_encode(['success' => false, 'pesan' => 'Username atau email sudah terdaftar.']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO petugas (username, email, password) VALUES (:u, :e, :p)");
    $stmt->execute(['u' => $username, 'e' => $email, 'p' => $hash]);

    echo json_encode(['success' => true, 'pesan' => 'Registrasi berhasil! Silakan login.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'pesan' => 'Gagal mendaftar. Coba lagi.']);
}