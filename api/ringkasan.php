<?php
header('Content-Type: application/json');
require __DIR__ . '/../includes/koneksi.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
$sedangDipinjam = $pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status = 'dipinjam'")->fetchColumn();

echo json_encode([
    'total_buku' => (int) $totalBuku,
    'total_anggota' => (int) $totalAnggota,
    'sedang_dipinjam' => (int) $sedangDipinjam,
]);