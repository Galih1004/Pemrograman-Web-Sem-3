<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'ID buku tidak valid.'];
    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM buku WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dihapus.'];
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus buku. Buku ini mungkin masih ada dalam transaksi peminjaman.'];
}

header('Location: list.php');
exit;